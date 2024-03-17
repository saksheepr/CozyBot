<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die ("Connection failed: " . $conn->connect_error);
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
            <img id ="profile_s" src="profile.png" alt="Profile Picture">
            <a href="Dashboard.php">
                <img class="icon" src="dashboard_icon.png" >
            </a>
            <img class="icon" src="energy.png" >
            <a href="Rooms.php">
                <img class="icon" src="rooms.png" >
            </a>
            <a href="Devices.php">
                <img class="icon" src="device.png" >
            </a>
            <img class="icon" src="members.png" >
            <a href="logout.php">
                <img class="icon" src="logout.png" >
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
            <center><div id="tab"></div></center>

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