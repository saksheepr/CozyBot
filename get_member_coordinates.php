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

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

// Fetch member coordinates from the members table
$sql_members = "SELECT MemberID, MemberName, Latitude, Longitude FROM members WHERE UserId = ?";
$stmt_members = $conn->prepare($sql_members);
$stmt_members->bind_param("i", $current_userid);
$stmt_members->execute();
$result_members = $stmt_members->get_result();

// Fetch home location coordinates from the user table
$sql_home = "SELECT home_lat, home_long FROM user WHERE UserId = ?";
$stmt_home = $conn->prepare($sql_home);
$stmt_home->bind_param("i", $current_userid);
$stmt_home->execute();
$result_home = $stmt_home->get_result();
$homeLocation = $result_home->fetch_assoc();

// Update member status based on distance from home
while ($row = $result_members->fetch_assoc()) {
    $latitude = $row['Latitude'];
    $longitude = $row['Longitude'];
    $distance = calculateDistance($homeLocation['home_lat'], $homeLocation['home_long'], $latitude, $longitude);
    if ($distance > 10) {
        // Member is more than 10km away from home
        // Update status to "away"
        $sql_update_status = "UPDATE members SET Status = 'away' WHERE MemberID = ?";
        $stmt_update_status = $conn->prepare($sql_update_status);
        $stmt_update_status->bind_param("i", $row['MemberID']);
        $stmt_update_status->execute();
        $stmt_update_status->close();
    } else {
        // Member is within 10km of home
        // Update status to "home"
        $sql_update_status = "UPDATE members SET Status = 'home' WHERE MemberID = ?";
        $stmt_update_status = $conn->prepare($sql_update_status);
        $stmt_update_status->bind_param("i", $row['MemberID']);
        $stmt_update_status->execute();
        $stmt_update_status->close();
    }
}

// Fetch updated member coordinates with status
$sql_updated_members = "SELECT MemberID, MemberName, Latitude, Longitude, Status FROM members WHERE UserId = ?";
$stmt_updated_members = $conn->prepare($sql_updated_members);
$stmt_updated_members->bind_param("i", $current_userid);
$stmt_updated_members->execute();
$result_updated_members = $stmt_updated_members->get_result();

// Store member coordinates and status in the same array
$coordinates = array();
while ($row = $result_updated_members->fetch_assoc()) {
    $coordinates[] = $row;
}

// Include home coordinates in the response
if ($homeLocation) {
    $homeLocation['isHomeLocation'] = true;
    $coordinates[] = $homeLocation;
}

// Convert the associative array to JSON format and output it
echo json_encode($coordinates);

$stmt_members->close();
$stmt_home->close();
$stmt_updated_members->close();
$conn->close();

// Function to calculate distance between two coordinates using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Radius of the earth in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius * $c; // Distance in kilometers
    return $distance;
}
?>
