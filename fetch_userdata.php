<?php
session_start(); // Start session to access session variables
session_start(); // Start session to access session variables

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cozybot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $user_id is retrieved from the session
if(isset($_SESSION['userid'])) {
// Assuming $user_id is retrieved from the session
if(isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
} else {
    echo "User ID not found in session.";
    exit; // Exit script if user ID is not found in session
}
} else {
    echo "User ID not found in session.";
    exit; // Exit script if user ID is not found in session
}

// Fetch user details based on user ID
$stmt = $conn->prepare("SELECT username, firstname, lastname, phoneno, email, password FROM user WHERE userid = ?");
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
    $password = $row['password'];
} else {
    echo "User not found.";
    exit; // Exit script if user is not found
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
                <img class="icon" src="dashboard_icon.png">
            </a>
            <img class="icon" src="schedule.png">
            <a href="Rooms.php">
                <img class="icon" src="rooms.png">
            </a>
            <a href="Devices.php">
                <img class="icon" src="device.png">
            </a>
            <a href="members.php">
                <img class="icon" src="members.png">
            </a>
            <a href="logout.php">
                <img class="icon" src="logout.png">
            </a>

        </div>
        <script src="room_script.js"></script>
        <div class="profile-container">
            <div class="avatar-container">
                <img src="avatar.png" alt="Profile Picture" class="avatar" id="avatar">
                <input type="file" accept="image/*" id="avatar-input" style="display: none;">
                <button onclick="openAvatarDialog()">Change Profile</button>
            </div>

            <form action="update_table.php" method="POST" enctype="multipart/form-data">
                <form id="profile-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php echo $username; ?>">
                </div>
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Enter First Name" value="<?php echo $firstname; ?>">
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Enter Last Name" value="<?php echo $lastname; ?>">
                </div>
                    <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter Phone" value="<?php echo $phoneno; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" value="<?php echo $password; ?>">
                </div>

                    <button type="button" id="edit-btn">Edit</button>
                    <button type="submit">Save Changes</button>
                </form>
            </form>
        </div>
    </main>
    <script src="User_Prof.js"></script>
</body>
</html>