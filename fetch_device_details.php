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

// Fetch existing devices from the database
$query = "SELECT DeviceName, DeviceType FROM Device WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $current_userid);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any devices
if ($result->num_rows > 0) {
    $devices = array();
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
    echo json_encode($devices);
} else {
    echo json_encode(array('message' => 'No devices found'));
}

// Close the database connection
$conn->close();
