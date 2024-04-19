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

// Check if a device named "Main Gate" exists for the current user
$mainGateExists = false; // Initialize to false by default

$sqlCheckDevice = "SELECT COUNT(*) AS count FROM Device WHERE DeviceName = 'Main Gate' AND UserID = $current_userid";
$result = $conn->query($sqlCheckDevice);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $mainGateExists = ($row['count'] > 0);
    // Fetch device settings data for Main Gate device
    $sqlDeviceSettings = "SELECT SettingValue FROM DeviceSettings 
                     INNER JOIN Device ON DeviceSettings.DeviceID = Device.DeviceID 
                     WHERE Device.DeviceName = 'Main Gate' AND Device.UserID = $current_userid";
    $resultSettings = $conn->query($sqlDeviceSettings);

    // Initialize an array to store the setting values
    $settingsData = '';

    // Check if settings data is found
    if ($resultSettings && $resultSettings->num_rows > 0) {
        // Fetch each row of settings data
        while ($rowSettings = $resultSettings->fetch_assoc()) {
            // Store the setting value in the array
            $settingsData = $rowSettings['SettingValue'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashb.css?v=<?php echo time(); ?>">
    <link rel="icon" href="home.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <img class="icon" src="bell.png" onclick="openPopup()" title="Notifications">
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
            <div class="menuitems" id="bell">
                <img class="icon" src="bell.png" onclick="openPopup()">
                <p class="options" onclick="openPopup()">Notifications</p>
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
        <!-- Container for the graph -->
    <div id="graphContainer" onclick="redirectToPage()">
        <canvas id="myChart"></canvas>
    </div>


    <script>
        // Fetch data from the server using AJAX
        function fetchData() {
            // Make an AJAX request to your PHP script
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Parse the JSON response
                    var data = JSON.parse(this.responseText);
                    // Call function to draw chart
                    drawChart(data);
                }
            };
            xhttp.open("GET", "fetch_data.php", true);
            xhttp.send();
        }

        // Function to draw the chart using Chart.js
        function drawChart(data) {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Time Consumption',
                        data: data.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Time Consumption'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Devices'
                            }
                        }
                    }
                }
            });
        }

        // Fetch data when the page loads
        window.onload = function() {
            fetchData();
            fetchData2();
        };
    </script>
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
        <div id="graphContainer2">
        <canvas id="myChart2" onclick="redirectPage()"></canvas>
        </div>
        <script>
        function fetchData2() {
            // Make an AJAX request to your PHP script
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Parse the JSON response
                    var data2 = JSON.parse(this.responseText);
                    // Call function to draw chart
                    drawChart2(data2);
                }
            };
            xhttp.open("GET", "energy_fetch.php", true);
            xhttp.send();
        }

        // Function to draw the chart using Chart.js
        function drawChart2(data2) {
            var ctx = document.getElementById('myChart2').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data2.labels,
                    datasets: [{
                        label: 'Energy Consumption',
                        data: data2.values,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Energy Consumption'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Devices'
                            }
                        }
                    }
                }
            });
        }

        // Fetch data when the page loads
        window.onload = function() {
            fetchData();
            fetchData2();
        };
    </script>
        
        
        
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
                <div id="disarm"><a href="members.php">View All</a></div>
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

    <div class="popup" id="popup">

        <table>
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Action</th> <!-- Added Action column -->
                </tr>
            </thead>
            <tbody id="notification-list"></tbody>
        </table>
        <div class="button-container">
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script src="dash.js?v=<?php echo time(); ?>"></script>
    <script>
        function openPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
    <script>
        function showSecurityMode() {
            document.getElementById("mod").style.visibility = "visible";
            document.getElementById("gate").style.visibility = "hidden";
            document.getElementById("secure").style.visibility = "visible";
            document.getElementById("unsecure").style.visibility = "hidden";
        }
        <?php if ($mainGateExists): ?>
            showSecurityMode();
        <?php endif; ?>
        // Get the array of setting values from PHP
        const settingsData = <?php echo json_encode($settingsData); ?>;
        console.log("Data", settingsData);

        // Select the correct mode based on the setting value
        const mode = document.querySelectorAll('.mo');

        // Loop through each mode
        mode.forEach((mo) => {
            // Check if the setting data matches the ID of the mode
            if (settingsData.includes(mo.id)) {
                // Add 'selected-modes' class to the mode
                mo.classList.add('selected-modes');
            } else {
                // If the mode does not match the setting data, remove the 'selected-modes' class
                mo.classList.remove('selected-modes');
            }
        });

        // Add click event listener to each mode
        mode.forEach((mo) => {
            mo.addEventListener('click', () => {
                // Remove 'selected-modes' class from all modes
                mode.forEach(m => m.classList.remove('selected-modes'));
                // Add 'selected-modes' class to the clicked mode
                mo.classList.add('selected-modes');

                // Get the selected mode's ID
                const selectedModeId = mo.id;

                // Send an AJAX request to update the devicesettings table
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_devicesettings.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log('Device setting updated successfully');
                        } else {
                            console.error('Error updating device setting:', xhr.status);
                        }
                    }
                };
                // Prepare the data to be sent in the request
                const requestData = JSON.stringify({ mode: selectedModeId });

                // Send the request
                xhr.send(requestData);
            });
        });


    </script
</body>

</html>