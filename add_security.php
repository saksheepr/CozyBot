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

$currentUserId = $_SESSION['userid'];

// Insert data into Device table
$deviceName = 'Main Gate';
$deviceType = 'Doors';
$deviceStatus = 'On';

$sqlDevice = "INSERT INTO Device (DeviceName, DeviceType, DeviceStatus, UserID) 
              VALUES ('$deviceName', '$deviceType', '$deviceStatus', $currentUserId)";

if ($conn->query($sqlDevice) === TRUE) {
    // Insertion into Device table successful, now get the last inserted DeviceID
    $deviceId = $conn->insert_id;

    // Insert data into DeviceSettings table
    $settingName = 'Mode';
    $settingValue = 'stay'; // Default mode
    $sqlSettings = "INSERT INTO DeviceSettings (DeviceID, UserID, SettingName, SettingValue) 
                    VALUES ($deviceId, $currentUserId, '$settingName', '$settingValue')";
    
    if ($conn->query($sqlSettings) === TRUE) {
        // Insertion into DeviceSettings table successful
        echo json_encode(array("status" => "success"));
    } else {
        // Failed to insert into DeviceSettings table
        echo json_encode(array("status" => "error", "message" => "Failed to insert into DeviceSettings table"));
    }
} else {
    // Failed to insert into Device table
    echo json_encode(array("status" => "error", "message" => "Failed to insert into Device table"));
}

$conn->close();
