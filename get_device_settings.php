<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

// Retrieve deviceId from GET parameter
$deviceId = $_GET['deviceId'];

// Query to fetch device type
$typeQuery = "SELECT DeviceType FROM Device WHERE DeviceID = $deviceId";
$typeResult = $conn->query($typeQuery);

if ($typeResult->num_rows > 0) {
    $row = $typeResult->fetch_assoc();
    $deviceType = $row['DeviceType'];

    // Query to fetch settings based on device type
    $sql = "";
    if ($deviceType == 'Lights' || $deviceType == 'Fans' || $deviceType == 'Thermostat' || $deviceType == 'Geyser' 
    || $deviceType == 'Ac' || $deviceType == 'Doors') {
        $sql = "SELECT SettingName, SettingValue FROM DeviceSettings WHERE DeviceID = $deviceId";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create an associative array to store settings
        $settings = array();

        // Loop through each row and add settings to the array
        while ($row = $result->fetch_assoc()) {
            $settings[$row['SettingName']] = $row['SettingValue'];
        }

        // Add device type to settings
        $settings['DeviceType'] = $deviceType;

        // Output settings as JSON
        echo json_encode($settings);
    } else {
        echo "No settings found for this device.";
    }
} else {
    echo "Device not found.";
}

$conn->close();