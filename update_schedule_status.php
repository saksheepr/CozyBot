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
    // Decode the JSON data sent in the request body
    $data = json_decode(file_get_contents("php://input"));

    // Validate the data
    if (isset($data->scheduleID) && isset($data->newStatus)) {
        $scheduleID = $data->scheduleID;
        $newStatus = $data->newStatus;

        // Prepare and execute the SQL update statement
        $stmt = $conn->prepare("UPDATE Schedules SET Status = ? WHERE ScheduleID = ?");
        $stmt->bind_param("si", $newStatus, $scheduleID);

        if ($stmt->execute()) {
            // Status updated successfully
            echo json_encode(array("status" => "success"));
        } else {
            // Failed to update status
            echo json_encode(array("status" => "error", "message" => "Failed to update status"));
        }
    } else {
        // Invalid data received
        echo json_encode(array("status" => "error", "message" => "Invalid data received"));
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method not allowed"));
}

