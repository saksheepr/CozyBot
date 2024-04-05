<?php
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
$current_userid = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>

    <link rel="stylesheet" href="rooms_style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="home.png" type="image/x-icon">
</head>

<body>
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
    <div id="content">
        <div class="container">
            <img class="room" src="room.jpg">
            <p class="text">Rooms</p>
        </div>
        <div id="buttons">
            <button class="button" onclick="openPopup()">Add Room</button>
            <button class="button" id="delete" onclick="removeSelectedRooms()">Remove Room</button>
        </div>
        <br>
        <input type="checkbox" id="select" style="position: relative; left: 200px;">
        <p style="display:inline; position: relative; left:210px;">Click to select for delete</p>

        <input type="checkbox" id="selectall" style="position: relative; left: 300px; display: none;">
        <p id="all" style="display:inline; position: relative; left: 310px;">Select/Deselect all</p>
        <br><br>
        <center>
            <div id="tab"></div>
        </center>

        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
            <button onclick="closePopup()"
                style="position: absolute; top: 10px; right: 10px; cursor: pointer;color:white;background-color: rgb(194, 60, 60);border-radius: 8px;">X</button>
            <h2>Add New Room</h2>
            <form action="room_add.php" method="post" id="roomForm">
                <label for="roomName">Room Name :</label><br>
                <input class="input" type="text" id="roomName" name="roomName"><br><br>
                <button class="button" type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script src="room_script.js?v=<?php echo time(); ?>"></script>
</body>

</html>