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
  <link rel="stylesheet" type="text/css" href="device_style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <a href="Scheduling.php">
      <img class="icon" src="schedule.png">
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
      <img id="type2" src="fans.png" alt="Device" class="type" />
      <img id="type3" src="thermostats.png" alt="Device" class="type" />
      <img id="type4" src="acs.png" alt="Device" class="type" />
      <img id="type5" src="geysers.png" alt="Device" class="type" />
      <img id="type6" src="doors.png" alt="Device" class="type" />
      <div id="slidecontainer">
        <h3 id="brightness">Brightness : <span id="demo"></span></h3>
        <input type="range" min="1" max="100" value="50" class="slider_set" id="myRange">
      </div>
      <div id="slidecontainer2">
        <h3 id="temp">Desired Temperature : <span id="demo2">°F</span></h3>
        <input type="range" min="40" max="90" value="45" class="slider_set" id="myRange2">
      </div>
      <h3 id="mode">Mode : <div class="demo">
          <p id="Morning" class="mode">Morning</p>
          <p id="Day" class="mode">Day</p>
          <p id="Night" class="mode">Night</p>
          <p id="power_saving" class="mode">Power Saving</p>
        </div>
      </h3>
      <div id="thermo-mode">
        <h3 id="modes">Modes :</h3>
        <div id="mod">
          <div id="Heating" class="mo"><img class="modes" src="heating.png">
            <p>Heating</p>
          </div>
          <div id="Cooling" class="mo"><img class="modes" src="cooling.png">
            <p>Cooling</p>
          </div>
          <div id="Auto" class="mo"><img class="modes" src="auto.png">
            <p>Auto</p>
          </div>
        </div>
      </div>
      <div id="fan-control">
        <h3 id="control">Fan Control : </h3>
        <label id="fan-con" class="switch">
          <input type="checkbox">
          <span class="slider round"></span>
        </label>
      </div>
      <div id="slidecontainer3">
        <h3 id="actemp">Desired Temperature : <span id="demo3">°C</span></h3>
        <input type="range" min="15" max="30" value="20" class="slider_set" id="myRange3">
      </div>
      <div id="slidecontainer4">
        <h3 id="geysertemp">Desired Temperature : <span id="demo4">°C</span></h3>
        <input type="range" min="30" max="60" value="40" class="slider_set" id="myRange4">
      </div>
      <h3 id="gmode">Mode : <div class="demo">
          <p id="normal" class="mode">Normal</p>
          <p id="hotspring" class="mode">Hot Spring Mode</p>
          <p id="comfort" class="mode">Comfort Mode</p>
          <p id="powersaving" class="mode">Power Saving</p>
        </div>
      </h3>
      <div id="ac-mode">
        <h3 id="modes">Modes :</h3>
        <div id="ac-mod">
          <div id="cool" class="mo"><img class="modes" src="cool.png">
            <p>Cool</p>
          </div>
          <div id="fan" class="mo"><img class="modes" src="fanmode.png">
            <p>Fan</p>
          </div>
          <div id="automode" class="mo"><img class="modes" src="automode.png">
            <p>Auto</p>
          </div>
          <div id="dry" class="mo"><img class="modes" src="dry.png">
            <p>Dry</p>
          </div>
          <div id="eco" class="mo"><img class="modes" src="eco.jpg">
            <p>Eco</p>
          </div>
          <div id="turbo" class="mo"><img class="modes" src="turbo.jpg">
            <p>Turbo</p>
          </div>
          <div id="heat" class="mo"><img class="modes" src="heat.png">
            <p>Heat</p>
          </div>
        </div>
      </div>

      <div id="lock">
        <h3 id="locks">Lock Status :</h3>
        <div id="loc">
          <div id="locked" class="lo"><img class="modes" src="locked.png">
            <p>Locked</p>
          </div>
          <div id="unlocked" class="lo"><img class="modes" src="unlocked.png">
            <p>Unlocked</p>
          </div>
        </div>
      </div>
      <div id="locking">
        <label id="pref" for="preference">Locking Preference :</label>
        <select name="preference" id="preference">
          <option value="automatic">Automatic Lock</option>
          <option value="manual">Manual Lock</option>
        </select>
      </div>

      <div id="lock-mode">
        <h3 id="modes">Modes :</h3>
        <div id="mod">
          <div id="stay" class="mo"><img class="modes" src="stay.png">
            <p>Home</p>
          </div>
          <div id="away" class="mo"><img class="modes" src="away.png">
            <p>Away</p>
          </div>
        </div>
      </div>

      <div id="bulb-shade">
        <h3 id="shade">Shade : </h3>
        <div id="bulb-container">
          <div class="bulb" id="white" title="White"></div>
          <div class="bulb" id="cream" title="Cream"></div>
          <div class="bulb" id="red" title="Red"></div>
          <div class="bulb" id="green" title="Green"></div>
          <div class="bulb" id="blue" title="Blue"></div>
          <div class="bulb" id="yellow" title="Yellow"></div>
          <div class="bulb" id="orange" title="Orange"></div>
          <div class="bulb" id="purple" title="Purple"></div>
          <div class="bulb" id="pink" title="Pink"></div>
          <div class="bulb" id="cyan" title="Cyan"></div>
          <div class="bulb" id="indigo" title="Indigo"></div>
          <div class="bulb" id="lime" title="Lime"></div>
        </div>
      </div>
      <div id="speed-fan">
        <h3 id="speed">Fan Speed : </h3>
        <div class="fan-speed">
          <div class="speed-circle" id="1" style="width: 15px; height: 15px;">
          </div>
          <div class="speed-circle" id="2" style="width: 20px; height: 20px;">
          </div>
          <div class="speed-circle" id="3" style="width: 25px; height: 25px;">
          </div>
          <div class="speed-circle" id="4" style="width: 35px; height: 35px;">
          </div>
          <div class="speed-circle" id="5" style="width: 40px; height: 40px;">
          </div>
        </div>
      </div>
      <div id="setting">
        <label id="direct" for="direction">Direction:</label>
        <select name="direction" id="direction">
          <option value="clockwise">Clockwise</option>
          <option value="counterclockwise">Counterclockwise</option>
        </select>
      </div>

      <button id="save" class="button">Save Changes</button>
    </div>
    <h1>My Devices</h1>
    <div class="nav">
      <div id="buttons">
        <button class="button" onclick="openPopup()">Add Device</button>
        <button class="button" id="delete" onclick="removeSelectedDevices()">Remove Device</button>
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
      <form action="device_add.php" method="post" id="deviceForm" onsubmit="return validateForm()">
        <label for="deviceName">Device Name :</label><br>
        <input class="input" type="text" id="deviceName" name="deviceName"><br><br>

        <label for="deviceType">Device Type :</label><br>
        <select id="deviceType" name="deviceType">
          <option>Select a Device Type</option>
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
            echo '<option>Select a Room</option>';
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