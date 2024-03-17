<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    die("User is not logged in.");
}

$current_userid = $_SESSION['userid'];

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Check if roomId and roomImage are set in the POST data
if (isset($_POST['roomId']) && isset($_FILES['roomImage'])) {
    $roomId = $_POST['roomId'];

    // Process the uploaded file
    $targetDir = "uploads/"; // Directory where images will be stored
    $targetFile = $targetDir . basename($_FILES["roomImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["roomImage"]["tmp_name"]);
    if ($check !== false) {
        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Move uploaded file to destination directory
            if (move_uploaded_file($_FILES["roomImage"]["tmp_name"], $targetFile)) {
                // Update the room's image in the database
                $conn = new mysqli($host, $user, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "UPDATE Room SET RoomImage = ? WHERE RoomID = ? AND UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $targetFile, $roomId, $current_userid);
                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "Failed to execute SQL statement: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
} else {
    echo "Room ID and/or room image not provided.";
}
