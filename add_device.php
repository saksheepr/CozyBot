<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

// Establish connection to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_userid = $_SESSION['userid'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted values from the form
    $deviceName = $_POST["deviceName"];
    $deviceType = $_POST["deviceType"];
    $existingRoomName = $_POST["existingRoom"];

    // Prepare and execute statement to retrieve roomid of the existing room
    $stmt_get_roomid = $conn->prepare("SELECT RoomID FROM Room WHERE RoomName = ? AND UserID = ?");
    $stmt_get_roomid->bind_param("si", $existingRoomName, $current_userid);
    $stmt_get_roomid->execute();
    $result_roomid = $stmt_get_roomid->get_result();

    // Check if roomid was retrieved successfully
    if ($result_roomid->num_rows > 0) {
        $row_roomid = $result_roomid->fetch_assoc();
        $roomid = $row_roomid["RoomID"];

        // Insert into Device table using prepared statement
        $stmt_device = $conn->prepare("INSERT INTO Device (DeviceName, DeviceType, UserID, RoomID) VALUES (?, ?, ?, ?)");
        $stmt_device->bind_param("ssii", $deviceName, $deviceType, $current_userid, $roomid);

        if ($stmt_device->execute()) {
            echo "New record created successfully for Device<br>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Room not found";
    }

    // Close prepared statements
    $stmt_get_roomid->close();
    $stmt_device->close();
}

// Close connection
$conn->close();
?>
