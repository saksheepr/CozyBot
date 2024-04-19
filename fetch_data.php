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

// SQL query to fetch data from TimeConsumption and Device tables
$sql = "SELECT TC.DeviceID, D.DeviceName, SUM(TC.DurationMinutes) AS TotalDuration 
        FROM TimeConsumption TC
        INNER JOIN Device D ON TC.DeviceID = D.DeviceID
        WHERE TC.UserID = $current_userid
        GROUP BY TC.DeviceID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $labels = array();
    $values = array();

    // Fetching data from each row
    while ($row = $result->fetch_assoc()) {
        // Store DeviceName as label
        $labels[] = $row["DeviceName"];
        // Store TotalDuration as value
        $values[] = $row["TotalDuration"];
    }

    // Constructing JSON response
    $response = array(
        "labels" => $labels,
        "values" => $values
    );

    // Sending JSON response
    echo json_encode($response);
} else {
    // If no data found
    echo json_encode(array("status" => "error", "message" => "No data found"));
}

// Close connection
$conn->close();

