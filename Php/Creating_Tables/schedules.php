<?php
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

// sql to create table
$sql = "CREATE TABLE Schedules (
    ScheduleID INT PRIMARY KEY AUTO_INCREMENT,
    ScheduleName VARCHAR(255) NOT NULL,
    DeviceID INT,
    StartTime TIME NOT NULL,
    EndTime TIME NOT NULL,
    DaysOfWeek VARCHAR(255),
    SettingID INT,
    SettingValue VARCHAR(255),
    Status ENUM('On', 'Off') NOT NULL DEFAULT 'On',
    FOREIGN KEY (DeviceID) REFERENCES Device(DeviceID),
    FOREIGN KEY (SettingID) REFERENCES DeviceSettings(SettingID))";

if ($conn->query($sql) === TRUE) {
  echo "Table Schedules created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();
