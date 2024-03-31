<?php

// Start the session
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = []; // Array to store error messages
if (empty($errors)) {
    // Sign-up logic
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

        $sql = "INSERT INTO User (FirstName, LastName, PhoneNo, Email, Username, Password) VALUES ('$firstName', '$lastName', '$phone', '$email', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['userid'] = $row["UserID"];
            $_SESSION['username'] = $username; // Set the session variable
            $_SESSION['firstname'] = $firstName;
            $display= "Please Login to continue!!";
            echo "<script>alert('$display');</script>";
            echo "<script>window.location.href = 'Sign_Up.html';</script>";
        } else {
            $errors[] = "Error in creating Account!";
        }
    }

    // Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $loginUsername = $_POST["loginUsername"];
    $loginPassword = $_POST["loginPassword"];

    $sql = "SELECT * FROM User WHERE Username = '$loginUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($loginPassword, $row["Password"])) {
            // Update last login timestamp
            $userId = $row["UserID"];
            $updateSql = "UPDATE User SET lastlogin = NOW() WHERE UserID = $userId";
            $conn->query($updateSql);

            // Set session variables
            $_SESSION['userid'] = $userId;
            $_SESSION['username'] = $loginUsername;
            $_SESSION['firstname'] = $row["FirstName"];
            header("Location: Dashboard.php");
            exit;
        } else {
            $errors[] = "Incorrect password!";
        }
    } else {
        $errors[] = "User not found!";
    }
} 
}

// Display error messages
foreach ($errors as $error) {
    echo "<script>alert('$error');</script>";
}

// Redirect back to the form
echo "<script>window.location.href = 'Sign_Up.html';</script>";

// Close the database connection
$conn->close();
