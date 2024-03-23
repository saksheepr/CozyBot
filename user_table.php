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
$sql = "CREATE TABLE User (
    UserID INT PRIMARY KEY auto_increment,
    Username VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    PhoneNo VARCHAR(20) UNIQUE NOT NULL,
    CreatedOn TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    LastLogin TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UserImage VARCHAR(255) DEFAULT 'profile.png')";

if ($conn->query($sql) === TRUE) {
  echo "Table User created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();
