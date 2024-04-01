<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CozyBot";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query to alter the table and add the new columns
$sql = "ALTER TABLE user
        ADD COLUMN home_long DECIMAL(10,8),
        ADD COLUMN home_lat DECIMAL(10,8)";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Columns 'home_long' and 'home_lat' added successfully.";
} else {
    echo "Error adding columns: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
