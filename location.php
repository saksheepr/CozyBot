<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CozyBot";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the UserID from the session
session_start();
$userId = $_SESSION['userid'];

// Fetch the username from the user table
$sql = "SELECT Username FROM user WHERE UserID = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the username
    $row = $result->fetch_assoc();
    $username = $row['Username'];
} else {
    // Handle case where no user found
    $username = "Unknown";
}

// Fetch the user image from the user table
$sql_user_image = "SELECT UserImage FROM user WHERE UserID = '$userId'";
$result_user_image = $conn->query($sql_user_image);
if ($result_user_image->num_rows > 0) {
    $row_user_image = $result_user_image->fetch_assoc();
    $userImage = $row_user_image['UserImage'];
} else {
    // Handle case where no user image found
    $userImage = "default_image.png";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Location</title>
    <link rel="stylesheet" href="location.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 400px;
        }
    </style>
</head>
<body>
    <div id="heading">
        <h1>Family Location</h1>
    </div>


    <div id="nav_shrink">
        <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;">&#9776;</span>
        <a href="User_Profile.html">
            <?php echo '<img id="profile_s" src="' . $userImage . '" alt="Profile Picture" title="User_Profile">'; ?>
        </a>
        <a href="Dashboard.php"><img class="icon" src="dashboard_icon.png"></a>
        <img class="icon" src="schedule.png">
        <a href="Rooms.php"><img class="icon" src="rooms.png"></a>
        <a href="Devices.php"><img class="icon" src="device.png"></a>
        <a href="members.php"><img class="icon" src="members.png"></a>
        <img class="icon" src="logout.png">
    </div>
<body>
    <button id="displayAllMembersBtn">Display All Members</button>
    <div id="locationResult"></div>
 
    <div id="map-container">
  <div id="map"></div>
</div>

    <!-- Button for setting home location -->
    <button id="setHomeLocationBtn">Set Home Location</button>
    <div id="popupForm" style="display: none;">
    <div id="formContent">
        <img src="close.png" alt="Back" id="backButton" width="20" height="20">
        <form id="homeLocationForm">
            <label for="homeLocation">Enter Home Location:</label>
            <input type="text" id="homeLocation" name="homeLocation" required><br><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>





    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="location_script.js"></script>
    <script>

       // Add event listener for the back button click
document.getElementById('backButton').addEventListener('click', function() {
    // Hide the popup form
    document.getElementById("popupForm").style.display = "none";
});


        // Add event listener for the button click
        document.getElementById('displayAllMembersBtn').addEventListener('click', function() {
            displayAllMembers(); // Call a function to display all member coordinates
        });

        // Add event listener for the "Set Home Location" button click
        document.getElementById('setHomeLocationBtn').addEventListener('click', function() {
            // Display the popup form
            var popup = document.getElementById("popupForm");
            popup.style.display = "block";
        });

        // Add event listener for the form submission
        document.getElementById('homeLocationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var homeLocation = document.getElementById('homeLocation').value;
            fetchCoordinates(homeLocation); // Call a function to fetch coordinates using OpenCage Geolocation API
        });

        function fetchCoordinates(location) {
            // Use OpenCage Geolocation API to fetch coordinates for the provided location
            var apiKey = 'a492a1c113ea4287a6d92c62358fb566';
            var apiUrl = 'https://api.opencagedata.com/geocode/v1/json?q=' + encodeURIComponent(location) + '&key=' + apiKey;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // Extract latitude and longitude from the response
                    var latitude = data.results[0].geometry.lat;
                    var longitude = data.results[0].geometry.lng;
                    // Now you can store these coordinates in the user table in your database
                    // Send coordinates to server-side script using AJAX
                    saveCoordinatesToDatabase(latitude, longitude);
                    // Hide the popup form
                    document.getElementById("popupForm").style.display = "none";
                })
                .catch(error => {
                    console.error('Error fetching coordinates:', error);
                });
        }

       // Get the UserID from the session
var userId = '<?php echo $_SESSION["userid"]; ?>';
//coordinates saving to database in table user

function saveCoordinatesToDatabase(latitude, longitude) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_coordinates.php", true); // Change "save_coordinates.php" to your server-side script
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText); // Log the response from the server
            // Optionally, you can handle the response here (e.g., display a success message)
        }
    };
    // Send latitude, longitude, and UserID to the server-side script
    xhr.send("latitude=" + latitude + "&longitude=" + longitude + "&userid=" + userId);
}

    </script>
</body>
</html>
