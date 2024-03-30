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
  <title>Devices</title>
  <link rel="stylesheet" type="text/css" href="device_style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" href="home.png" type="image/x-icon">
</head>

<body>
  <div id="nav_shrink">
    <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;">&#9776;</span>
    <img id="profile_s" src="profile.png" alt="Profile Picture">
    <a href="Dashboard.php">
      <img class="icon" src="dashboard_icon.png">
    </a>
    <img class="icon" src="energy.png">
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
    <img id="pic" src="devices.png">
    <div id="set">
      <button onclick="closeDeviceSettings()"
        style="position: absolute; top: 10px; right: 10px; cursor: pointer;color:white;background-color: rgb(194, 60, 60);border-radius: 8px;">X</button>
      <br>
      <div class="device">
        <h2 id="t1">Living Room Light</h2>
        <label class="switch">
          <input type="checkbox">
          <span class="slider round"></span>
        </label>
      </div>
      <h3 id="roomName">Room Name : </h3>
      <img id="type1" src="lights.png" alt="Device" class="type" />
      <img id="type2" src="fans.png" alt="Device" class="type"/>
      <img id="type3" src="thermostats.png" alt="Device" class="type"/>
      <img id="type4" src="acs.png" alt="Device" class="type"/>
      <img id="type5" src="geysers.png" alt="Device" class="type"/>
      <img id="type6" src="doors.png" alt="Device" class="type"/>
      <div id="slidecontainer">
      <h3 id="brightness">Brightness : <span id="demo"></span></h3>
        <input type="range" min="1" max="100" value="50" class="slider_set" id="myRange">
      </div>
      <h3 id="mode">Mode : <span id="demo"><p class="mode">Morning</p> <p class="mode">Day</p> <p class="mode">Night</p>  </span> </h3>
      <button id="save" class="button">Save Changes</button>
    </div>
    <h1>My Devices</h1>
    <div class="nav">
      <div id="buttons">
        <button class="button" onclick="openPopup()">Add Device</button>
        <button class="button" id="delete" onclick="removeSelectedDevices()">Remove Room</button>
      </div>
      <p id="totalDevices" style="color:#1D084B;">Total No. of Devices: <span id="deviceCount">0</span></p>
    </div>
    <div class="search">
      <select id="devices">
        <option>All Devices</option>
        <option>Lights</option>
        <option>Doors</option>
        <option>Fans</option>
        <option>Thermostat</option>
        <option>Ac</option>
        <option>Geyser</option>
      </select>
      <div class="topnav">
        <div class="search-container">
          <input type="text" placeholder="Search.." name="search">
          <button type="submit"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>

    <input type="checkbox" id="select" style="position: relative; left: 750px; top: 100px;">
    <p style="display:inline; position: relative; left: 760px; top: 100px;">Click to select for delete</p>

    <input type="checkbox" id="selectall" style="position: relative; left: 810px; top: 100px; display: none;">
    <p id="all" style="display:inline; position: relative; left: 820px; top: 100px;">Select/Deselect
      all</p>

    <div id="tab"></div>


    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
      <button onclick="closePopup()"
        style="position: absolute; top: 10px; right: 10px; cursor: pointer;color:white;background-color: rgb(194, 60, 60);border-radius: 8px;">X</button>
      <h2>Add New Device</h2>
      <form action="device_add.php" method="post" id="deviceForm">
        <label for="deviceName">Device Name :</label><br>
        <input class="input" type="text" id="deviceName" name="deviceName"><br><br>

        <label for="deviceType">Device Type :</label><br>
        <select id="deviceType" name="deviceType">
          <option value="Lights">Lights</option>
          <option value="Doors">Doors</option>
          <option value="Fans">Fans</option>
          <option value="Thermostat">Thermostat</option>
          <option value="Ac">Ac</option>
          <option value="Geyser">Geyser</option>
        </select><br><br>

        <label for="existingRoom">Select Existing Room :</label><br>
        <select id="existingRoom" name="existingRoom">
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
        </select><br><br>

        <button class="button" type="submit">Submit</button>
      </form>
    </div>

  </div>
  <script src="device_script.js?v=<?php echo time(); ?>"></script>

</body>

</html>