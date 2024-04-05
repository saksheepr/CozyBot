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

// Fetch existing schedules from the database
$query = "SELECT ScheduleID,ScheduleName, DeviceID, StartTime, EndTime, DaysOfWeek, SettingID, SettingValue, Status FROM Schedules WHERE DeviceID IN (SELECT DeviceID FROM Device WHERE UserID = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_userid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any schedules
if ($result->num_rows > 0) {
    $schedules = array();
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    echo json_encode($schedules);
} else {
    echo json_encode(array('message' => 'No schedules found'));
}

// Close the database connection
$stmt->close();
$conn->close();

