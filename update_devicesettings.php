<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user's ID from the session
$currentUserId = $_SESSION['userid'];

// Query to find the DeviceID for the device named "Main Gate"
$sql = "SELECT DeviceID FROM Device WHERE DeviceName = 'Main Gate' AND UserID = ?";

// Prepare and execute the statement
$statement = $conn->prepare($sql);
$statement->bind_param("i", $currentUserId);
$statement->execute();
$result = $statement->get_result();

// Check if a record was found
if ($result->num_rows > 0) {
    // Fetch the DeviceID from the result
    $row = $result->fetch_assoc();
    $deviceID = $row['DeviceID'];

    // Close the statement
    $statement->close();

    // Retrieve the selected mode ID from the POST request
    $data = json_decode(file_get_contents("php://input"), true);
    $selectedModeId = $data['mode'];

    // Update the devicesettings table with the selected mode ID
    $sqlUpdate = "UPDATE devicesettings SET SettingValue = ? WHERE DeviceID = ? AND UserID = ? AND SettingName = 'Mode'";

    $statementUpdate = $conn->prepare($sqlUpdate);
    $statementUpdate->bind_param("sii", $selectedModeId, $deviceID, $currentUserId);

    if ($statementUpdate->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to update device setting"));
    }

    $statementUpdate->close();
} else {
    // No device named "Main Gate" found for the current user
    echo json_encode(array("status" => "error", "message" => "No device named 'Main Gate' found for the current user"));
}

$conn->close();
