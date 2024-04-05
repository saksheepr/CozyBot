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
  $deviceStatus = $requestData['deviceStatus'];

  // Update DeviceStatus in the Device table
  $stmt = $conn->prepare("UPDATE Device SET DeviceStatus = ? WHERE DeviceID = ?");
  $stmt->bind_param("si", $deviceStatus, $deviceId);
  if (!$stmt->execute()) {
    // Error occurred, send error response
    echo json_encode(array("status" => "error", "message" => "Failed to update device status."));
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
?>
