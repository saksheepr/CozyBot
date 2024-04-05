<?php
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
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $ScheduleName = $_POST['ScheduleName'];
    $deviceName = $_POST['DeviceName'];
    $settingName = $_POST['SettingsName'];
    $settingValue = $_POST['SettingsValue'];
    $startTime = $_POST['stime'];
    $endTime = $_POST['etime'];
    $daysOfWeek = implode(",", $_POST['days']); // Convert array to comma-separated string
    
    // Validate and sanitize input
    $deviceName = mysqli_real_escape_string($conn, $deviceName);
    $settingName = mysqli_real_escape_string($conn, $settingName);
    $settingValue = mysqli_real_escape_string($conn, $settingValue);
    $startTime = mysqli_real_escape_string($conn, $startTime);
    $endTime = mysqli_real_escape_string($conn, $endTime);

    // Get DeviceID based on DeviceName
    $deviceID = null;
    $sql_device = "SELECT DeviceID FROM Device WHERE DeviceName = ?";
    $stmt_device = $conn->prepare($sql_device);
    $stmt_device->bind_param("s", $deviceName);
    $stmt_device->execute();
    $result_device = $stmt_device->get_result();
    if ($result_device->num_rows > 0) {
        $row_device = $result_device->fetch_assoc();
        $deviceID = $row_device['DeviceID'];
    }
    $stmt_device->close();

    // Get SettingID based on SettingName
    $settingID = null;
    $sql_setting = "SELECT SettingID FROM DeviceSettings WHERE DeviceID = ? AND SettingName = ?";
    $stmt_setting = $conn->prepare($sql_setting);
    $stmt_setting->bind_param("is", $deviceID, $settingName);
    $stmt_setting->execute();
    $result_setting = $stmt_setting->get_result();
    if ($result_setting->num_rows > 0) {
        $row_setting = $result_setting->fetch_assoc();
        $settingID = $row_setting['SettingID'];
    }
    $stmt_setting->close();

    // SQL to insert data into Schedules table
    $sql = "INSERT INTO Schedules (ScheduleName,DeviceID, StartTime, EndTime, DaysOfWeek, SettingID, SettingValue) VALUES (?,?, ?, ?, ?, ?, ?)";
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("sisssis", $ScheduleName,$deviceID, $startTime, $endTime, $daysOfWeek, $settingID, $settingValue);
    // Execute the statement
    if ($stmt->execute()) {
        header("Location: Scheduling.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // Close the statement
    $stmt->close();
}

$conn->close();