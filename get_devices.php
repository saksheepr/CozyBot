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
$current_userid = $_SESSION['userid'];

// Check if room is set in the request
if (isset($_GET['room']) && isset($_GET['deviceType'])) {
    $room = $_GET['room'];
    $deviceType = $_GET['deviceType'];
    // Prepare and execute query to retrieve RoomID
    $query = "SELECT RoomID FROM Room WHERE RoomName = ? AND UserID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("si", $room, $current_userid);

    $stmt->execute();

    // Store the result set
    $stmt->store_result();

    // Bind the result variables
    $stmt->bind_result($roomID);

    // Fetch the result
    $stmt->fetch();
    if ($deviceType == "All Devices") {
        // Prepare and execute SQL query to fetch devices based on the selected room's roomid
        $stmt = $conn->prepare("SELECT DeviceName FROM Device WHERE RoomID = ? AND UserID = ?");
        $stmt->bind_param("ii", $roomID, $current_userid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch devices into an associative array
        $devices = array();
        while ($row = $result->fetch_assoc()) {
            $devices[] = $row;
        }

        // Close statement
        $stmt->close();

        // Return devices as JSON response
        echo json_encode($devices);

    } else {
        // Prepare and execute SQL query to fetch devices based on the selected room's roomid
        $stmt = $conn->prepare("SELECT DeviceName FROM Device WHERE DeviceType = ? AND RoomID = ? AND UserID = ?");
        $stmt->bind_param("sii", $deviceType, $roomID, $current_userid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch devices into an associative array
        $devices = array();
        while ($row = $result->fetch_assoc()) {
            $devices[] = $row;
        }

        // Close statement
        $stmt->close();

        // Return devices as JSON response
        echo json_encode($devices);

    }
}

// Close connection
$conn->close();

