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

$current_userid = $_SESSION['userid'];
$memberID = $_POST['memberid'] ?? '';

// Prepare SQL statement to delete the user
$sql = "DELETE FROM member WHERE MemberID = $memberID AND UserID = $current_userid";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
