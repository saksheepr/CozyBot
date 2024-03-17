<?php
session_start(); // Start session to access session variables

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cozybot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Assuming $user_id is retrieved from the database or some other authentication method
// Example: Retrieving user_id based on username
$username = $_POST['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row["id"];
    // Set user_id in the session
    $_SESSION['user_id'] = $user_id;
} else {
    echo "User not found.";
    exit; // Exit script if user is not found
}

// Now $user_id is defined and set in the session

$username = $_POST['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$access_control = $_POST['access_control'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE users SET username=?, firstname=?, lastname=?, email=?, password=?, access_control=? WHERE id=?");
$stmt->bind_param("ssssssi", $username, $firstname, $lastname, $email, $password, $access_control, $user_id);
$stmt->execute();

echo "Updated successfully";

$stmt->close();
$conn->close();
?>
