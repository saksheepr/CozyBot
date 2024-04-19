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

// SQL query to create table
$sql = "CREATE TABLE TimeConsumption (
    ConsumptionID INT PRIMARY KEY AUTO_INCREMENT,
    DeviceID INT,
    UserID INT,
    StartTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    EndTime DATETIME,
    DurationMinutes DECIMAL(10,2),
    ConsumedUnits DECIMAL(10,2),
    FOREIGN KEY (UserID) REFERENCES User(UserID),
    FOREIGN KEY (DeviceID) REFERENCES Device(DeviceID) ON DELETE CASCADE)";

// Execute SQL query
if ($conn->query($sql) === TRUE) {
  echo "Table DeviceConsumption created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

// Close connection
$conn->close();
