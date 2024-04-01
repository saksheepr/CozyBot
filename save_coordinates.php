<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CozyBot";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve latitude, longitude, and UserID from the POST request
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';
$userId = $_POST['userid'] ?? '';

// Update the user table with the new coordinates
$sql = "UPDATE user SET home_lat = '$latitude', home_long = '$longitude' WHERE UserID = '$userId'";

if ($conn->query($sql) === TRUE) {
    echo "Coordinates saved successfully.";
} else {
    echo "Error updating coordinates: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
