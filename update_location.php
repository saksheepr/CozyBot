<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['userid'])) {
    die("User ID not set in session.");
}

$current_userid = $_SESSION['userid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberID = $_POST['memberid'] ?? '';
    
    // Get user's current location using HTML5 Geolocation API
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        
        // Update the location in the members table
        $sql = "UPDATE members SET Latitude = '$latitude', Longitude = '$longitude' WHERE MemberID = '$memberID'";
        
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error updating location: " . $conn->error;
        }
    } else {
        echo "Latitude and Longitude not provided.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
