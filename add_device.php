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
    header("Location: Dashboard.php");
    // Get form data
    $deviceName = $_POST['deviceName'];
    $deviceType = $_POST['deviceType'];
    $existingRoom = $_POST['existingRoom'];

    echo $deviceName;
    echo $deviceType;
    echo $existingRoom;

    // Prepare and execute query to retrieve RoomID
    $query = "SELECT RoomID FROM Room WHERE RoomName = ? AND UserID = ?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("si", $existingRoom, $current_userid);

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($roomID);

    // Fetch the result
    $stmt->fetch();

    // Insert device data into the Device table using prepared statements
    $insertQuery = "INSERT INTO Device (DeviceName, DeviceType, UserID, RoomID) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insertQuery);
    $stmt_insert->bind_param("ssii", $deviceName, $deviceType, $current_userid, $roomID);

    if ($stmt_insert->execute()) {
        header("Location: Dashboard.php");
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
