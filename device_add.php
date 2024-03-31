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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $deviceName = $_POST['deviceName'];
    $deviceType = $_POST['deviceType'];
    $existingRoom = $_POST['existingRoom'];

    // Prepare and execute query to retrieve RoomID
    $query = "SELECT RoomID FROM Room WHERE RoomName = ? AND UserID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("si", $existingRoom, $current_userid);

    $stmt->execute();

    // Store the result set
    $stmt->store_result();

    // Bind the result variables
    $stmt->bind_result($roomID);

    // Fetch the result
    $stmt->fetch();

    // Insert device data into the Device table using prepared statements
    $insertQuery = "INSERT INTO Device (DeviceName, DeviceType, UserID, RoomID) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insertQuery);
    $stmt_insert->bind_param("ssii", $deviceName, $deviceType, $current_userid, $roomID);

    if ($stmt_insert->execute()) {
        // Insert default settings based on device type
        $deviceID = $stmt_insert->insert_id; // Get the last inserted device ID
        if ($deviceType == 'Lights') {
            // Insert default settings for lights
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Brightness', '50'), 
            (NULL, ?, ?, 'Mode', 'Morning'), 
            (NULL, ?, ?, 'Shade', 'white')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiiiii", $deviceID, $current_userid, $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        } elseif ($deviceType == 'Fans') {
            // Insert default settings for fans
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Fan_Speed', '1'), 
            (NULL, ?, ?, 'Mode', 'Morning'), 
            (NULL, ?, ?, 'Direction', 'clockwise')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiiiii", $deviceID, $current_userid, $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        } elseif ($deviceType == 'Thermostat') {
            // Insert default settings for thermostat
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Temperature', '45'), 
            (NULL, ?, ?, 'Mode', 'Cooling'), 
            (NULL, ?, ?, 'FanControl', 'Off')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiiiii", $deviceID, $current_userid, $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        } elseif ($deviceType == 'Ac') {
            // Insert default settings for AC
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Temperature', '22'), 
            (NULL, ?, ?, 'Mode', 'cool')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiii", $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        }elseif ($deviceType == 'Geyser') {
            // Insert default settings for AC
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Temperature', '40'), 
            (NULL, ?, ?, 'Mode', 'comfort')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiii", $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        }elseif ($deviceType == 'Doors') {
            // Insert default settings for thermostat
            $insertSettingsQuery = "INSERT INTO devicesettings (SettingID, DeviceID, UserID, SettingName, SettingValue) VALUES 
            (NULL, ?, ?, 'Lock Status', 'locked'), 
            (NULL, ?, ?, 'Mode', 'stay'), 
            (NULL, ?, ?, 'Locking Preference', 'manual')";
            $stmt_settings = $conn->prepare($insertSettingsQuery);
            $stmt_settings->bind_param("iiiiii", $deviceID, $current_userid, $deviceID, $current_userid, $deviceID, $current_userid);
            $stmt_settings->execute();
            $stmt_settings->close();
        } 

        header("Location: Devices.php");
        exit;
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    // Close the statement
    $stmt_insert->close();
}

// Close the database connection
$conn->close();
