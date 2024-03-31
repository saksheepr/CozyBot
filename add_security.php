<?php
// add_security.php

// Start the session
session_start();

// Include database connection
include_once "db_connection.php";

// Check if the request is a POST request and if the action parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "addSecurity") {
    // Check if the user is logged in
    if (!isset($_SESSION['userid'])) {
        echo json_encode(array("status" => "error", "message" => "User is not logged in."));
        exit;
    }

    // Get the current user ID
    $currentUserId = $_SESSION['userid'];

    // Prepare and execute the SQL query to insert security for the main door
    $insertDeviceQuery = "INSERT INTO device(DeviceName, DeviceType, DeviceStatus, UserID) VALUES ('Main Door', 'Doors', 'On', $currentUserId)";

    if ($conn->query($insertDeviceQuery) === TRUE) {
        // Insert settings for the main door
        $deviceID = $conn->insert_id; // Get the ID of the inserted device
        $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES (NULL, $deviceID, $currentUserId, 'Mode', 'stay')";

        if ($conn->query($insertSettingsQuery) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Security added to Main Door."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Failed to add settings for Main Door."));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to add security for Main Door."));
    }

    // Close the database connection
    $conn->close();
} else {
    // If the action parameter is not set or if it's not a POST request, return an error
    echo json_encode(array("status" => "error", "message" => "Invalid request."));
}
