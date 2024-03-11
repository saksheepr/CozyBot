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

$pass = isset($_POST["pass"]) ? $_POST["pass"] : '';

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
        $_SESSION['userid']=$row["UserID"];
        $_SESSION['username'] = $username; // Set the session variable
        $_SESSION['firstname'] = $firstName; 
        header("Location: Dashboard.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
            $_SESSION['userid']=$row["UserID"];
            $_SESSION['username'] = $loginUsername; // Set the session variable
            $_SESSION['firstname'] = $row["FirstName"]; 
            header("Location: Dashboard.php");
            exit;
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
}

// Close the database connection
$conn->close();