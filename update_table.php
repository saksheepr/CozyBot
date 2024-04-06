<?php
session_start(); // Start session to access session variables

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Cozybot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Assuming $user_id is retrieved from the database or some other authentication method
// Example: Retrieving user_id based on username
$username = $_POST['username'];
$stmt = $conn->prepare("SELECT userid FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row["userid"];
    // Set user_id in the session
    $_SESSION['userid'] = $user_id;
} else {
    echo "User not found.";
    exit; // Exit script if user is not found
}

// Now $user_id is defined and set in the session

$username = $_POST['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE user SET username=?, firstname=?, lastname=?, email=? WHERE userid=?");
$stmt->bind_param("ssssi", $username, $firstname, $lastname, $email, $user_id);
$stmt->execute();

echo "Updated successfully";

$stmt->close();
$conn->close();
