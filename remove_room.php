<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

// Retrieve room IDs from JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Remove rooms from the database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM Room WHERE UserID = ? AND RoomID = ?");
$stmt->bind_param("ii", $current_userid, $roomId);

foreach ($data as $roomId) {
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode("success");

