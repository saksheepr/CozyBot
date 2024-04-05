<?php
session_start(); // Start session to access session variables

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cozybot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $user_id is retrieved from the session
if(isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
} else {
    echo "User ID not found in session.";
    exit; // Exit script if user ID is not found in session
}

// Fetch user details based on user ID
$stmt = $conn->prepare("SELECT username, firstname, lastname, phoneno, email, UserImage FROM user WHERE userid = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user details
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $phoneno = $row['phoneno'];
    $email = $row['email'];
    $userImage=$row['UserImage'];
} else {
    echo "User not found.";
    exit; // Exit script if user is not found
}


// Update user details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile picture upload
    $target_dir = "C:/_xampp/htdocs/";;
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["profile_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["profile_picture"]["name"])). " has been uploaded.";
            // Update database with the file path
            $profile_picture_path = "/profile_pictures/" . basename($_FILES["profile_picture"]["name"]);
            $stmt = $conn->prepare("UPDATE user SET profile_picture = ? WHERE userid = ?");
            $stmt->bind_param("si", $profile_picture_path, $user_id);
            if ($stmt->execute()) {
                echo "Profile picture updated successfully";
            } else {
                echo "Error updating profile picture: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
$current_userid = $_SESSION['userid'];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="UserProfile.css">
    <link rel="icon" href="home.png" type="image/x-icon">
</head>
<body>
    <main>
        <div id="nav_shrink">
            <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;">&#9776;</span>
            <a href="fetch_userdata.php">
            <?php
            // SQL query to select the UserImage from the User table
            $sql = "SELECT UserImage FROM User WHERE UserID = $current_userid";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $userImage = $row["UserImage"];
                    // Now you have the UserImage, you can use it as needed
                    // For example, if you want to display it in an img tag:
                    echo '<img id="profile_s" src="' . $userImage . '" alt="Profile Picture" title="User_Profile">';
                }
            } else {
                echo "0 results";
            }

            ?>
        </a>
            <a href="Dashboard.php">
            <img class="icon" src="dashboard_icon.png" title="Dashboard">
        </a>
        <a href="Scheduling.php">
            <img class="icon" src="schedule.png" title="Scheduling">
        </a>
        <a href="Rooms.php">
            <img class="icon" src="rooms.png" title="Rooms">
        </a>
        <a href="Devices.php">
            <img class="icon" src="device.png" title="Devices">
        </a>
        <a href="members.php">
            <img class="icon" src="members.png" title="Members">
        </a>
        <img class="icon" src="bell.png" onclick="openPopup()" title="Notifications">
        <a href="logout.php">
            <img class="icon" src="logout.png">
        </a>

        </div>
        <script src="room_script.js"></script>
        <div class="profile-container">
            <div class="avatar-container">
                <img src="<?php echo $userImage;?>" alt="Profile Picture" class="avatar" id="avatar">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    <button type="submit" name="submit">Upload Profile Picture</button>
                </form>
            </div>

            <form action="update_table.php" method="POST" enctype="multipart/form-data">
                <form id="profile-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php echo $username; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Enter First Name" value="<?php echo $firstname; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Enter Last Name" value="<?php echo $lastname; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter Phone" value="<?php echo $phoneno; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email" value="<?php echo $email; ?>" readonly>
                </div>
                    <button type="button" id="edit-btn">Edit</button>
                    <button type="button" id="change-password-btn">Change Password</button>
                    <button type="submit">Save Changes</button>
                </form>
                <form id="change-password-form" style="display: none;">
                    <div class="form-group">
                        <label for="current-password">Current Password:</label>
                        <input type="password" name="current-password" id="current-password" placeholder="Enter Current Password">
                    </div>
                    <div class="form-group" id="new-password-group" style="display: none;">
                        <label for="new-password">New Password:</label>
                        <input type="password" name="new-password" id="new-password" placeholder="Enter New Password">
                    </div>
                    <div class="form-group" id="confirm-password-group" style="display: none;">
                        <label for="confirm-password">Confirm New Password:</label>
                        <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm New Password">
                    </div>
                    <button type="button" id="cancel-password-btn">Cancel</button>
                    <button type="submit" id="save-password-btn" style="display: none;">Save Password</button>
                </form>
            </form>
            </form>
        </div>
    </main>
    <script src="User_Prof.js"></script>
    <script>    //JS Function For Edit Button
        document.getElementById('edit-btn').addEventListener('click', function() {
            var inputs = document.querySelectorAll('input[readonly]');
            var editBtn = document.getElementById('edit-btn');
            
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].readOnly = !inputs[i].readOnly;
            }
            
            editBtn.textContent = editBtn.textContent === 'Edit' ? 'Edit' : 'Edit';
        });

        document.getElementById('change-password-btn').addEventListener('click', function() {
            document.getElementById('change-password-form').style.display = 'block';
            document.getElementById('profile-form').style.display = 'none';
        });

        document.getElementById('cancel-password-btn').addEventListener('click', function() {
            document.getElementById('change-password-form').style.display = 'none';
            document.getElementById('profile-form').style.display = 'block';
        });
    </script>
</body>
</html>
