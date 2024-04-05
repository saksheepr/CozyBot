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
$currentusername = $_SESSION['username'];
$currentname = $_SESSION['firstname'];
$current_userid = $_SESSION['userid'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashb.css?v=<?php echo time(); ?>">
    <link rel="icon" href="home.png" type="image/x-icon">
</head>

<body>
    <div id="nav_shrink" style="display:none">
        <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;"
            onclick="closeNav()">&#9776;</span>
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
        <a href="logout.php">
            <img class="icon" src="logout.png">
        </a>
    </div>
    <div id="nav_expand">
        <span class="icon" id="expand" style="font-size:30px;cursor:pointer;color: navy;display: inline;"
            onclick="openNav()">&#9776;</span>
        <a href="fetch_userdata.php">
            <div id="profile">
                <?php
                // Fetch the user's profile image from the database
                $sql = "SELECT UserImage FROM User WHERE UserID = $current_userid"; // Assuming $current_userid holds the current user's ID
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $userImage = $row["UserImage"];
                    echo "<img src='$userImage' alt='Profile Picture'>";
                } else {
                    // If no profile image found, display a default image
                    echo "<img src='default_profile_image.png' alt='Profile Picture'>";
                }
                ?>
            </div>
        </a>
        <div id="nav">
            <p id="llogin">Last Login :
                <?php
                $sql = "SELECT lastLogin FROM User WHERE UserID = $current_userid";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo $row['lastLogin'];
                } else {
                    echo 'No record found';
                }
                ?>
            </p>
            <div class="menuitems" id="dasboard">
                <a href="Dashboard.php">
                    <img class="icon" src="dashboard_icon.png">
                </a>
                <a href="Dashboard.php">
                    <p class="options">Dashboard</p>
                </a>
            </div>
            <div class="menuitems" id="schedule">
                <a href="Scheduling.php">
                    <img class="icon" src="schedule.png" title="Scheduling">
                </a>
                <a href="Scheduling.php">
                    <p class="options">Scheduling</p>
                </a>
            </div>
            <div class="menuitems" id="rooms">
                <a href="Rooms.php">
                    <img class="icon" src="rooms.png">
                </a>
                <a href="Rooms.php">
                    <p class="options">Rooms</p>
                </a>
            </div>
            <div class="menuitems" id="devices">
                <a href="Devices.php">
                    <img class="icon" src="device.png">
                </a>
                <a href="Devices.php">
                    <p class="options">Devices</p>
                </a>
            </div>
            <div class="menuitems" id="members">
                <a href="members.php">
                    <img class="icon" src="members.png">
                </a>
                <a href="members.php">
                    <p class="options">Members</p>
                </a>
            </div>
            <div class="menuitems" id="logout">
                <a href="logout.php">
                    <img class="icon" src="logout.png">
                </a>
                <a href="logout.php">
                    <p class="options">Logout</p>
                </a>
            </div>
        </div>
    </div>
    <div id="content">
        <h2>Welcome
            <?php echo $currentname; ?>
        </h2>
        <div id="rooms_widget">
            <select id="room" name="Room">
                <?php
                $sql = "SELECT RoomName, RoomImage FROM Room WHERE userid = ?";

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
                    echo '<option>Choose a Room</option>';
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        // Output option element for each room
                        echo '<option value="' . $row['RoomName'] . '" data-image="' . $row['RoomImage'] . '">' . $row['RoomName'] . '</option>';
                    }
                } else {
                    echo '<option value="">No rooms found</option>';
                }

                // Close the statement
                $stmt->close();
                ?>
            </select>
            <img id="displayedImage" class="rooms_image" src="room.jpg" alt="Displayed Image">
        </div>

        <div class="view">
            <h3>My Devices</h3>
            <a href="Devices.php">View All</a>
        </div>

        <div id="Mydevices">
            <div class="de">
                <img class="icon_device" src="bulb.png" alt="bulb">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Lights'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Lights</p>';
                } else {
                    echo '<p class="dev">0 Lights</p>';
                }
                ?>
            </div>
            <div class="de">
                <img class="icon_device" src="door.png" alt="door">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Doors'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Doors</p>';
                } else {
                    echo '<p class="dev">0 Doors</p>';
                }
                ?>
            </div>
            <div class="de">
                <img class="icon_device" src="fan.png" alt="fan">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Fans'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Fans</p>';
                } else {
                    echo '<p class="dev">0 Fans</p>';
                }
                ?>
            </div>
            <div class="de">
                <img class="icon_device" src="thermostat.png" alt="thermostat">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Thermostat'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Thermostats</p>';
                } else {
                    echo '<p class="dev">0 Thermostats</p>';
                }
                ?>
            </div>
            <div class="de">
                <img class="icon_device" src="ac.png" alt="ac">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Ac'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Acs</p>';
                } else {
                    echo '<p class="dev">0 Acs</p>';
                }
                ?>
            </div>
            <div class="de">
                <img class="icon_device" src="geyser.png" alt="plug">
                <?php
                $sql = "SELECT count(*) as light_count FROM Device WHERE userid = $current_userid and DeviceType='Geyser'";
                $result = $conn->query($sql);
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<p class="dev">' . $row['light_count'] . ' Geyser</p>';
                } else {
                    echo '<p class="dev">0 Geysers</p>';
                }
                ?>
            </div>
        </div>
        <h3 id="t">Time Usage of Smart Devices</h3>
        <div class="bar-graph">
            <div class="bar" style="height: 80%; background-color: #007bff;" data-label="Smart Lights"></div>
            <div class="bar" style="height: 60%; background-color: #28a745;" data-label="Smart Thermostat"></div>
            <div class="bar" style="height: 40%; background-color: #ffc107;" data-label="Smart Lock"></div>
            <div class="bar" style="height: 70%; background-color: #dc3545;" data-label="Smart Speaker"></div>
            <div class="bar" style="height: 90%; background-color: #6c757d;" data-label="Smart TV"></div>
        </div>
        <p style="position: absolute;left:750px;top:370px;background: linear-gradient(to right,#1D084B, #212167, #23438C); -webkit-text-fill-color: transparent; 
        -webkit-background-clip: text; 
        background-clip: text;">Power Saving Mode</p>
        <div class="view_energy">
            <h3 id="t1">Energy Consumption</h3>
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="line-graph">
            <svg viewBox="0 0 650 400">
                <!-- X and Y axes -->
                <line x1="50" y1="350" x2="550" y2="350" stroke="#ccc" />
                <line x1="50" y1="50" x2="50" y2="350" stroke="#ccc" />

                <!-- Y-axis labels with percentages -->
                <text x="20" y="355" class="label">100%</text>
                <text x="20" y="305" class="label">75%</text>
                <text x="20" y="255" class="label">50%</text>
                <text x="20" y="205" class="label">25%</text>
                <text x="20" y="155" class="label">0%</text>

                <!-- Data points with labels (months) and white stroke -->
                <circle cx="50" cy="350" r="4" class="point" stroke="#fff" />
                <text x="45" y="380" class="label">January</text>

                <circle cx="150" cy="300" r="4" class="point" stroke="#fff" />
                <text x="140" y="380" class="label">February</text>

                <circle cx="250" cy="250" r="4" class="point" stroke="#fff" />
                <text x="240" y="380" class="label">March</text>

                <circle cx="350" cy="200" r="4" class="point" stroke="#fff" />
                <text x="340" y="380" class="label">April</text>

                <circle cx="450" cy="150" r="4" class="point" stroke="#fff" />
                <text x="440" y="380" class="label">May</text>

                <circle cx="550" cy="100" r="4" class="point" stroke="#fff" />
                <text x="540" y="380" class="label">June</text>

                <!-- Curved line touching the last point -->
                <path d="M50,350 C150,300 250,250 350,200 C450,150 550,100 550,100" fill="none" stroke="#ffffff"
                    stroke-width="2" />
            </svg>

        </div>
        <div class="security">
            <div id="arm1">
                <h3 id="t1">Security</h3>
                <p id="unsecure">Disarmed</p>
                <p id="secure">Armed</p>
            </div>
            <div id="gate" onclick="AddSecurity()">Add Security to Main Door </div>
            <div id="mod">
                <div id="stay" class="mo"><img class="modes" src="stay.png">
                    <p class="se">Arm Home</p>
                </div>
                <div id="away" class="mo"><img class="modes" src="away.png">
                    <p class="se">Arm Away</p>
                </div>
            </div>
        </div>
        <div class="weather">
            <h3 id="t2">Weather</h3>
            <p style="color: black;display: inline-block;position: relative;top:25px;left: 60px;">Today</p>
            <div class="weather-widget" id="weather-widget">
                <img src="sun.png" alt="Weather Icon" class="weather-icon">
                <span class="temperature">22°C</span>
            </div>
            <div class="weather-details">
                Sunny, <span class="min-temp">18&deg;C</span> / <span class="max-temp">31&deg;C</span>
            </div>
        </div>
        <div class="member">
            <div id="heading">
                <h3 id="t1">Members</h3>
                <div id="disarm"><a href="Members.html">View All</a></div>
            </div>

            <div id="circle">
                <div class="mem">
                    <img src="girl.png">
                </div>
                <div class="mem">
                    <img src="man.png">
                </div>
                <div class="mem">
                    <img src="boy.png">
                </div>
                <div class="mem">
                    <img src="woman.png">
                </div>
            </div>
        </div>

    </div>


    <script src="dash.js?v=<?php echo time(); ?>"></script>
</body>

</html>
