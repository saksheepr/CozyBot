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


// Retrieve device IDs from JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

    // Connect to the database
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

$stmt = $conn->prepare("DELETE FROM Device WHERE DeviceID = ? AND UserID = ?");
$stmt->bind_param("ii", $deviceId, $current_userid);

foreach ($data as $deviceId) {
    $stmt->execute();
}
$stmt->close();
$conn->close();

echo json_encode("success");
