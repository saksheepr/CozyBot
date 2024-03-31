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

    // Extract deviceId and settings from the decoded JSON data
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
    foreach ($settings as $settingName => $settingValue) {

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

    // All settings saved successfully, send success response
    echo json_encode(array("status" => "success", "message" => "Settings saved successfully."));
} else {
    // Invalid request method, send error response
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

// Close connection
$conn->close();
