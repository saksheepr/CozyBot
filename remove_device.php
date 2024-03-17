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

// Check if device ID is provided in the request
if (!isset($_POST['deviceId'])) {
    die("Device ID not provided.");
}

$deviceId = $_POST['deviceId'];

// Create a database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to delete the device from the database
$query = "DELETE FROM Device WHERE DeviceID = ? AND UserID = ?";
$stmt = $conn->prepare($query);

// Bind parameters
$stmt->bind_param("ii", $deviceId, $current_userid);

// Execute the statement
if ($stmt->execute()) {
    // Device successfully removed
    echo "Device removed successfully.";
} else {
    // Error in removal
    echo "Error removing device: " . $stmt->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
