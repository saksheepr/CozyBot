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
    <title>Scheduling</title>

    <link rel="stylesheet" href="schedule_style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="home.png" type="image/x-icon">
</head>

<body>
    <div id="nav_shrink">
        <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;">&#9776;</span>
        <a href="User_Profile.html">
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
        <a href="Scheduling.php">
            <img class="icon" src="schedule.png" title="Scheduling">
        </a>
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
        <h1>Scheduling</h1>
        <div class="scform">
            <h2>Create a New Scene</h2>
            <form action="" method="post" id="sceneForm">
                <label for="sceneName">Scene Name :</label>
                <input class="input" type="text" id="sceneName" name="sceneName"><br>

                <label for="RoomName">Choose Device Type :</label>
                <select id="devices">
                    <option>All Devices</option>
                    <option>Lights</option>
                    <option>Doors</option>
                    <option>Fans</option>
                    <option>Thermostat</option>
                    <option>Ac</option>
                    <option>Geyser</option>
                </select><br>

                <label for="RoomName">Choose Room :</label>
                <select id="RoomName" name="RoomName">
                    <?php
                    $sql = "SELECT RoomName FROM Room WHERE userid = ?";
                    // Prepare the statement
                    $stmt = $conn->prepare($sql);

                    // Bind the parameter
                    $stmt->bind_param("i", $current_userid);

                    // Execute the statement
                    $stmt->execute();

                    // Get the result
                    $result = $stmt->get_result();

                    // Check if there are rows returned
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        echo '<option value= "">Select a Room</option>';
                        while ($row = $result->fetch_assoc()) {
                            // Output option element for each room
                            echo '<option value="' . $row['RoomName'] . '">' . $row['RoomName'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No rooms found</option>';
                    }

                    // Close the statement
                    $stmt->close();
                    ?>
                </select><br>

                <label for="DeviceName">Choose Device :</label>
                <select id="DeviceName" name="DeviceName"></select><br>

                <label for="SettingsName">Choose Settings :</label>
                <select id="SettingsName" name="SettingsName"></select><br>

                <label for="SettingsName">Settings Value :</label>
                <select id="SettingsValue" name="SettingsValue"></select><br>

                <label for="timeInput">Select Start Time :</label>
                <input class="input" type="time" id="stime" name="stime">
                <br>
                <label for="timeInput">Select End Time :</label>
                <input class="input" type="time" id="etime" name="etime"><br>

                <label for="ndays">Repeat :</label><br>
                <div id="days">
                    <div class="day" id="monday">M</div>
                    <div class="day" id="tuesday">T</div>
                    <div class="day" id="wednesday">W</div>
                    <div class="day" id="thursday">T</div>
                    <div class="day" id="friday">F</div>
                    <div class="day" id="saturday">S</div>
                    <div class="day" id="sunday">S</div>
                </div>


                <button class="button" type="submit">Submit</button>
            </form>
        </div>

    </div>


    <script src="schedule_script.js?v=<?php echo time(); ?>"></script>
</body>

</html>