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
    $roomName = $_POST['roomName'];
    
    $insertQuery = "INSERT INTO Room(UserID,RoomName) VALUES (?,?)";
    $stmt_insert = $conn->prepare($insertQuery);
    $stmt_insert->bind_param("is",  $current_userid,$roomName);

    if ($stmt_insert->execute()) {
        header("Location: Rooms.php");
        exit;
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    // Close the statement
    $stmt_insert->close();
}

// Close the database connection
$conn->close();
?>