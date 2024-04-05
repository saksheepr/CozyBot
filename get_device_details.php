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

// Query to fetch device details
$sql = "SELECT DeviceId,DeviceName, DeviceType, DeviceStatus, RoomName
        FROM Device
        INNER JOIN Room ON Device.RoomID = Room.RoomID
        WHERE DeviceID = $deviceId AND Device.UserID = $current_userid";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "Device not found";
}
