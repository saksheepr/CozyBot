<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CozyBot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
  die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $requestData = json_decode(file_get_contents("php://input"), true);

  // Extract deviceId, settings and deviceStatus from the decoded JSON data
  $deviceId = $requestData['deviceId'];
  $settings = $requestData['settings'];
  $deviceType = $requestData['deviceType'];
  $deviceStatus = $requestData['deviceStatus'];

  // Update DeviceStatus in the Device table
  $stmt = $conn->prepare("UPDATE Device SET DeviceStatus = ? WHERE DeviceID = ?");
  $stmt->bind_param("si", $deviceStatus, $deviceId);
  if (!$stmt->execute()) {
    // Error occurred, send error response
    echo json_encode(array("status" => "error", "message" => "Failed to update device status."));
    exit;
  }
date_default_timezone_set("Asia/Kolkata");
  // If device is turned on, insert a new record with start time
  if ($deviceStatus === 'On') {
    // Insert start timestamp
    $startTimestamp = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO TimeConsumption (DeviceID, UserID, StartTime,EndTime) VALUES (?, ?, ?,null)");
    $stmt->bind_param("iis", $deviceId, $current_userid, $startTimestamp);
    if (!$stmt->execute()) {
      // Error occurred, send error response
      echo json_encode(array("status" => "error", "message" => "Failed to insert start timestamp."));
      exit;
    }
  } else if ($deviceStatus === 'Off') {
    // If device is turned off, update the end time of the latest record with the same device ID and user ID
    $endTimestamp = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("UPDATE TimeConsumption SET EndTime = ? WHERE DeviceID = ? AND UserID = ?  ORDER BY ConsumptionID DESC LIMIT 1");
    $stmt->bind_param("sii", $endTimestamp, $deviceId, $current_userid);
    if (!$stmt->execute()) {
      // Error occurred, send error response
      echo json_encode(array("status" => "error", "message" => "Failed to update end timestamp."));
      exit;
    }
  }
  // Retrieve the start time and end time from the table
$stmt = $conn->prepare("SELECT StartTime, EndTime FROM TimeConsumption WHERE DeviceID = ? AND UserID = ? AND EndTime IS NOT NULL ORDER BY ConsumptionID DESC LIMIT 1");
$stmt->bind_param("ii", $deviceId, $current_userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $startTime = $row['StartTime'];
    $endTime = $row['EndTime'];

    // Calculate the duration in minutes
    $durationMinutes = round((strtotime($endTime) - strtotime($startTime)) / 3600, 2); // Convert seconds to minutes

    // Update the DurationMinutes column
    $stmt = $conn->prepare("UPDATE TimeConsumption SET DurationMinutes = ? WHERE DeviceID = ? AND UserID = ? AND EndTime IS NOT NULL ORDER BY ConsumptionID DESC LIMIT 1");
    $stmt->bind_param("dii", $durationMinutes, $deviceId, $current_userid);
    if (!$stmt->execute()) {
        // Error occurred, send error response
        echo json_encode(array("status" => "error", "message" => "Failed to update duration."));
        exit;
    }
} else {
    // No records found, handle accordingly
    echo json_encode(array("status" => "error", "message" => "No records found."));
    exit;
}

// Define the PowerConsumptionRate values for each device type
    $rates = array(
        'Lights' => 0.5, 
        'Doors' => 0,    
        'Fans' => 1.0,  
        'Thermostat' => 0.8, 
        'Ac' => 1.5,    
        'Geyser' => 2.0, 
    );
// Calculate ConsumedUnits based on device type and power consumption rates
if ($deviceType === 'Lights') {
    $consumedUnits = $rates['Lights'] * $durationMinutes; // Convert minutes to hours
} elseif ($deviceType === 'Doors') {
    $consumedUnits = 0;
} elseif ($deviceType === 'Fans') {
    $consumedUnits = $rates['Fans'] * $durationMinutes; // Convert minutes to hours
} elseif ($deviceType === 'Thermostat') {
    $consumedUnits = $rates['Thermostat'] * $durationMinutes; // Convert minutes to hours
} elseif ($deviceType === 'Ac') {
    $consumedUnits = $rates['Ac'] * $durationMinutes; // Convert minutes to hours
} elseif ($deviceType === 'Geyser') {
    $consumedUnits = $rates['Geyser'] * $durationMinutes; // Convert minutes to hours
} else {
    // Handle invalid device type
    echo json_encode(array("status" => "error", "message" => "Invalid device type."));
    exit;
}

// SQL query to update ConsumedUnits
$updateConsumedUnitsSQL = "
    UPDATE TimeConsumption 
    SET ConsumedUnits = ?
    WHERE DeviceID = ? AND UserID = ? AND EndTime IS NOT NULL ORDER BY StartTime DESC
    LIMIT 1";

// Execute SQL query to update ConsumedUnits
$stmt = $conn->prepare($updateConsumedUnitsSQL);
$stmt->bind_param("dii", $consumedUnits, $deviceId, $current_userid);
if (!$stmt->execute()) {
    // Error occurred, send error response
    echo json_encode(array("status" => "error", "message" => "Failed to update consumed Units."));
    exit;
}

  // Loop through the settings data and update the DeviceSettings table
  $notificationMessage = "Device settings updated:\n";
  $isFirstChange = true;
  foreach ($settings as $settingName => $settingValue) {
    if (!$isFirstChange) {
      $notificationMessage .= ",\n";
    }
    $notificationMessage .= "$settingName : $settingValue";
    $isFirstChange = false;

    // Prepare SQL statement
    $stmt = $conn->prepare("UPDATE DeviceSettings SET SettingValue = ? WHERE DeviceID = ? AND SettingName = ? AND UserID = ?");
    $stmt->bind_param("sisi", $settingValue, $deviceId, $settingName, $current_userid);

    // Execute the statement
    if (!$stmt->execute()) {
      // Error occurred, send error response
      echo json_encode(array("status" => "error", "message" => "Failed to save settings."));
      exit;
    }
  }

  // Check if notification already exists for this user and message
  $checkStmt = $conn->prepare("SELECT * FROM notifications WHERE UserID = ? AND Message = ?");
  $checkStmt->bind_param("is", $current_userid, $notificationMessage);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();

  // If notification doesn't exist, insert it
  if ($checkResult->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO notifications (UserID, Message) VALUES (?, ?)");
    $stmt->bind_param("is", $current_userid, $notificationMessage);

    // Insert notification
    if (!$stmt->execute()) {
      // Error occurred, log the error but continue with success response for settings update
      error_log("Failed to create notification: " . $conn->error);
    }
  }

  // All settings saved successfully, send success response
  echo json_encode(array("status" => "success", "message" => "Settings saved successfully."));
} else {
  // Invalid request method, send error response
  echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

// Close connection
$conn->close();