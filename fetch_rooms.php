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

// Fetch existing rooms with room ID, room name, and room image
$sql = "SELECT RoomID, RoomName, RoomImage FROM Room WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_userid);
$stmt->execute();
$result = $stmt->get_result();

$rooms = array();
while ($row = $result->fetch_assoc()) {
    $rooms[] = array(
        'RoomID' => $row['RoomID'],
        'RoomName' => $row['RoomName'],
        'RoomImage' => $row['RoomImage']
    );
}

// Return rooms as JSON
echo json_encode($rooms);

$stmt->close();
$conn->close();
