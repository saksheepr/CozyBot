<?php

session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the notification ID from the request
    $requestData = json_decode(file_get_contents("php://input"), true);
    $notificationID = $requestData['notificationID'];

    // Prepare and execute SQL query to delete the notification
    $stmt = $conn->prepare("DELETE FROM notifications WHERE NotificationID = ? AND UserID = ?");
    $stmt->bind_param("ii", $notificationID, $current_userid);
    if ($stmt->execute()) {
        // Notification deleted successfully
        echo json_encode(array("status" => "success", "message" => "Notification removed successfully."));
    } else {
        // Error occurred while deleting notification
        echo json_encode(array("status" => "error", "message" => "Failed to remove notification."));
    }
} else {
    // Invalid request method
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

// Close connection
$conn->close();
?>
