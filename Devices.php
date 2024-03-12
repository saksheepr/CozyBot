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
  <title>Devices</title>
  <link rel="stylesheet" type="text/css" href="device_style.css">
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
    <a href="Rooms.html">
      <img class="icon" src="rooms.png">
    </a>
    <a href="Devices.html">
      <img class="icon" src="device.png">
    </a>
    <img class="icon" src="members.png">
    <img class="icon" src="logout.png">
  </div>

  <div id="content">
    <img id="pic" src="devices.png">
    <h1>My Devices</h1>
    <div class="nav">
      <div id="buttons">
        <button class="button" onclick="openPopup()">Add Device</button>
        <button class="button">Remove Device</button>
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
        <option>Plugs</option>
      </select>
      <div class="topnav">
        <div class="search-container">
          <input type="text" placeholder="Search.." name="search">
          <button type="submit"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>

    <div id="tab"></div>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
      <button onclick="closePopup()" style="position: absolute; top: 10px; right: 10px; cursor: pointer;color:white;background-color: rgb(194, 60, 60);border-radius: 8px;">X</button>
      <h2>Add New Device</h2>
      <form action="add_device.php" method="post" id="deviceForm">
        <label for="deviceName">Device Name :</label><br>
        <input class="input" type="text" id="deviceName" name="deviceName"><br><br>

        <label for="deviceType">Device Type :</label><br>
        <select id="deviceType" name="deviceType">
          <option value="Lights">Lights</option>
          <option value="Doors">Doors</option>
          <option value="Fans">Fans</option>
          <option value="Thermostat">Thermostat</option>
          <option value="Ac">Ac</option>
          <option value="Plugs">Plugs</option>
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


  <script src="device_script.js"></script>
</body>

</html>