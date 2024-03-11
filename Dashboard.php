<?php
session_start();

$host="localhost";
$user="root";
$password="";
$dbname="cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$currentusername=$_SESSION['username'];
$currentname = $_SESSION['firstname'];
$current_userid = $_SESSION['userid'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashb.css">
    <link rel="icon" href="home.png" type="image/x-icon">
</head>
<body>
        <div id="nav_shrink" style="display:none">
            <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;" onclick="closeNav()" >&#9776;</span>
            <a href="User_Profile.html">
                <img id ="profile_s" src="profile.png" alt="Profile Picture" title="User_Profile">
            </a>  
            <a href="Dashboard.html">
                <img class="icon" src="dashboard_icon.png" title="Dashboard" >
            </a>
            <img class="icon" src="schedule.png" title="Scheduling">
            <a href="Rooms.html">
                <img class="icon" src="rooms.png" title="Rooms">
            </a>
            <a href="Devices.php">
                <img class="icon" src="device.png" title="Devices" >
            </a>
            <img class="icon" src="members.png" title="Members" >
            <img class="icon" src="logout.png" title="Logout" >
        </div>
        <div id="nav_expand" >
            <span class="icon" id="expand" style="font-size:30px;cursor:pointer;color: navy;display: inline;" onclick="openNav()">&#9776;</span>
            <a href="User_Profile.html">
                <div id="profile">
                    <img src="profile.png" alt="Profile Picture">
                </div>
            </a>
            <div id="nav">
                <div class="menuitems" id="dasboard">
                    <a href="Dashboard.html">
                        <img class="icon" src="dashboard_icon.png" >
                    </a>
                    <a href="Dashboard.html">
                        <p class="options">Dashboard</p>
                    </a>
                </div>
                <div class="menuitems" id="schedule">
                    <img class="icon" src="schedule.png" >
                    <p class="options">Scheduling</p>
                </div>
                <div class="menuitems" id="rooms">
                    <a href="Rooms.html">
                        <img class="icon" src="rooms.png" >
                    </a>
                    <a href="Rooms.html">
                        <p class="options">Rooms</p>
                    </a> 
                </div>
                <div class="menuitems" id="devices">
                    <a href="Devices.php">
                        <img class="icon" src="device.png" >
                    </a>
                    <a href="Devices.php">
                        <p class="options">Devices</p>
                    </a>
                </div>
                <div class="menuitems" id="members">
                    <img class="icon" src="members.png" >
                    <p class="options">Members</p>
                </div>
                <div class="menuitems" id="logout">
                    <img class="icon" src="logout.png" >
                    <p class="options">Logout</p>
                </div>
            </div>
        </div>
        <div id="content" >
            <h2>Welcome <?php echo $currentname; ; ?></h2>
        <div id="rooms_widget">
            <select id="room" name="Room">
                <option value="Bedroom">Bedroom</option>
                <option >Living Room</option>
                <option >Kitchen</option>
                <option >Kids Room</option>
            </select>
            <img id="displayedImage"class="rooms_image" src="Bedroom.jpg" alt="Displayed Image">
        </div>
        <div class="view">
            <h3>My Devices</h3>
            <a href="Devices.php">View All</a>
        </div>
        
        <div id="Mydevices">
            <div class="de">
                <img class="icon_device" src="bulb.png" alt="bulb">
                <p class="dev">7 Lights</p>
            </div>
            <div class="de">
                <img class="icon_device" src="door.png" alt="door">
                <p class="dev">3 Doors</p>
            </div>
            <div class="de">
                <img class="icon_device" src="fan.png" alt="fan">
                <p class="dev">6 Fans</p>
            </div>
            <div class="de">
                <img class="icon_device" src="thermostat.png" alt="thermostat">
                <p class="dev">1 Thermostat</p>
            </div>
            <div class="de">
                <img class="icon_device" src="ac.png" alt="ac">
                <p class="dev">3 ACs</p>
            </div>
            <div class="de">
                <img class="icon_device" src="plug.png" alt="plug">
                <p class="dev">10 Plugs</p>
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
                <path d="M50,350 C150,300 250,250 350,200 C450,150 550,100 550,100" fill="none" stroke="#ffffff" stroke-width="2" />
            </svg>
            
        </div>
        <div class="security">
            <div id="arm1">
                <h3 id="t1">Security</h3>
            <div id="disarm">Disarmed</div>
            </div>
            
            <div id="arm2">
                <button class="button">Arm Home</button>
            <button class="button">Arm Away</button>
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
        
        
    <script src="dash.js"></script>
</body>
</html>