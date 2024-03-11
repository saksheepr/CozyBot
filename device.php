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

// Create Room table
$sqlRoom = "CREATE TABLE Room (
        RoomID INT PRIMARY KEY auto_increment,
        UserID int,
        RoomName VARCHAR(255) NOT NULL,
        FOREIGN KEY (UserID) REFERENCES User(UserID))";

if ($conn->query($sqlRoom) === TRUE) {
  echo "Table Room created successfully<br>";
} else {
  echo "Error creating table Room: " . $conn->error . "<br>";
}

// Create Device table
$sqlDevice = "CREATE TABLE Device (
        DeviceID INT PRIMARY KEY auto_increment,
        DeviceName VARCHAR(255) NOT NULL,
        DeviceType VARCHAR(255) NOT NULL,
        DeviceStatus enum('On','Off') NOT NULL DEFAULT 'Off',
        UserID INT,
        RoomID INT,
        FOREIGN KEY (UserID) REFERENCES User(UserID),
        FOREIGN KEY (RoomID) REFERENCES Room(RoomID))";

if ($conn->query($sqlDevice) === TRUE) {
  echo "Table Device created successfully";
} else {
  echo "Error creating table Device: " . $conn->error;
}

$conn->close();
?>
