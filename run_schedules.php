<?php

session_start(); // Start the session

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

// Fetch schedules based on current time and day
$currentDay = date('l'); // Get the current day
$currentTime = date('H:i:s'); // Get the current time

echo('day'.$currentDay);
echo('tim'.$currentTime);

// Prepare the SQL statement to fetch active schedules for the current day and time
$sql = "SELECT ScheduleID, DeviceID, SettingID, SettingValue 
        FROM Schedules 
        WHERE DaysOfWeek LIKE '%$currentDay%' 
        AND StartTime <= '$currentTime' 
        AND EndTime >= '$currentTime' 
        AND Status = 'On'";

// Execute the query to fetch schedules
$result = $conn->query($sql);

// Check if there are any active schedules
if ($result->num_rows > 0) {
    // Loop through each active schedule
    while ($row = $result->fetch_assoc()) {
        $scheduleID = $row['ScheduleID'];
        $deviceID = $row['DeviceID'];
        $settingID = $row['SettingID'];
        $settingValue = $row['SettingValue'];

        // Prepare the SQL statement to update device settings
        $updateSql = "UPDATE DeviceSettings 
                      SET SettingValue = '$settingValue' 
                      WHERE DeviceID = $deviceID 
                      AND SettingID = $settingID";

        // Execute the update query
        if ($conn->query($updateSql) === TRUE) {
            echo "Device settings updated successfully for schedule ID: $scheduleID\n";
            
            // Insert message in success condition
            $current_userid = $_SESSION['userid'];
            $notificationMessage = "Device settings updated successfully for schedule ID: ".$scheduleID;
            $stmt = $conn->prepare("INSERT INTO notifications (UserID, Message) VALUES (?, ?)");
            $stmt->bind_param("is", $current_userid, $notificationMessage);

            // Execute the statement
            if (!$stmt->execute()) {
                // Error occurred, send error response
                echo json_encode(array("status" => "error", "message" => "Failed to save settings."));
                exit;
            }
        } else {
            echo "Error updating device settings: " . $conn->error . "\n";
        }
    }
} else {
    echo "No active schedules found for the current time and day.\n";
}
// Close the database connection
$conn->close();