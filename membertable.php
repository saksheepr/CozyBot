<?php


$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the table
$sql = "CREATE TABLE members (
    MemberID INT(11) NOT NULL AUTO_INCREMENT,
    MemberName VARCHAR(255) NOT NULL,
    Role VARCHAR(100),
    STATUS ENUM('Home', 'Away'),
    UserId INT,
    FOREIGN KEY (UserId) REFERENCES UserTable(UserId)
)";

// Execute query
if ($conn->query($sql) === TRUE) {
    echo "Table members created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close connection
$conn->close();

?>
