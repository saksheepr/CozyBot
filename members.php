<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cozybot";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['userid'])) {
    die("User ID not set in session.");
}

$current_userid = $_SESSION['userid'];
$sql = "SELECT MemberID, MemberName, Role, Status FROM members WHERE UserID = '$current_userid'";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$userFirstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Unknown";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberName = $_POST['name'] ?? '';
    $status = $_POST['status'] ?? '';
    $role = $_POST['role'] ?? '';
    $sql = "INSERT INTO members (UserID, MemberName, Role, Status) 
            VALUES ('$current_userid', '$memberName', '$role', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management</title>
    <link rel="stylesheet" href="memberstyle.css">
</head>

<body>
    <div id="heading">
        <h1>Your Members</h1>
    </div>
    <div id="welcome">
        <h1>Welcome, <?php echo $userFirstName; ?></h1>
    </div>

    <div id="nav_shrink">
        <span class="icon" id="shrink" style="font-size:30px;cursor:pointer;color: white;">&#9776;</span>
        <a href="User_Profile.html">
            <?php
            $sql = "SELECT UserImage FROM User WHERE UserID = $current_userid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $userImage = $row["UserImage"];
                    echo '<img id="profile_s" src="' . $userImage . '" alt="Profile Picture" title="User_Profile">';
                }
            } else {
                echo "0 results";
            }
            ?>
        </a>
        <a href="Dashboard.php"><img class="icon" src="dashboard_icon.png"></a>
        <img class="icon" src="schedule.png">
        <a href="Rooms.php"><img class="icon" src="rooms.png"></a>
        <a href="Devices.php"><img class="icon" src="device.png"></a>
        <a href="members.php"><img class="icon" src="members.png"></a>
        <img class="icon" src="logout.png">
    </div>

    <div class="userTable">
        <?php
        $sql = "SELECT MemberID, MemberName, Role, Status FROM members WHERE UserID = '$current_userid'";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Error executing query: " . $conn->error);
        }

        if ($result->num_rows > 0):
        ?>
        
          <table id="userTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="user-<?php echo $row['MemberID']; ?>">
                  <td><?php echo $row['MemberName']; ?></td>
                  <td><?php echo $row['Role']; ?></td>
                  <td><?php echo $row['Status']; ?></td>
                  <td>
                    <img src="delete.png" width="30px" alt="Delete" onclick="confirmDelete(<?php echo $row['MemberID']; ?>)">
                    <a href="#" onclick="updateLocation(<?php echo $row['MemberID']; ?>)"> <!-- Modify this line -->
    <img src="locationpin.png" width="30px" alt="Update Location">
</a>

                  </td>
                </tr>   
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
            <p>No members added.</p>
        <?php endif; ?>

        <div class="container">
            <button id="addMemberBtn" onclick="togglePopup()">Add Member</button>
        </div>

        <div id="popupForm" style="display: none;">
            <img src="close.png" width=20px height=20px id="closeButton" onclick="togglePopup()">
            <h2>Enter Member Details</h2>
            <form id="userForm" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="role">Role:</label>
                <input type="text" id="role" name="role" required><br><br>

                <label for="status">Status:</label><br>
                <select id="status" name="status" required>
                    <option value="home">Home</option>
                    <option value="away">Away</option>
                </select><br><br>

                <div class="container">
                    <button type="submit">Add New User</button>
                </div>
            </form>
        </div>
    </div>

    <div id="message" style="display: none; color: green; margin-top: 10px;"></div>

    <script>
        function togglePopup() {
            var popup = document.getElementById("popupForm");
            if (popup.style.display === "none") {
                popup.style.display = "block";
            } else {
                popup.style.display = "none";
            }
        }

        function confirmDelete(memberID) {
            var confirmDelete = confirm("Are you sure you want to delete this member?");
            if (confirmDelete) {
                deleteUser(memberID);
            }
        }

        function deleteUser(memberID) {
            var row = document.getElementById("user-" + memberID);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText.trim() === "success") {
                        row.remove();
                    }
                }
            };
            xhr.send("memberid=" + memberID);
        }
        var elements = document.querySelectorAll('.icon');
        elements.forEach(function (element) {
            element.addEventListener('mouseover', function () {
                element.style.filter = 'drop-shadow(0 0 0.75rem yellow)';
                element.style.height = '25px';
            });
            element.addEventListener('mouseout', function () {
                element.style.filter = '';
                element.style.height = '20px';
            });
        });

        function updateLocation(memberID) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                sendLocation(memberID, latitude, longitude);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
//updates location in database
    function sendLocation(memberID, latitude, longitude) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_location.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === "success") {
                    alert("Location updated successfully.");
                    // Optionally, you can update the UI or perform any other action upon successful update
                } else {
                    alert("Error updating location: " + xhr.responseText);
                }
            }
        };
        xhr.send("memberid=" + memberID + "&latitude=" + latitude + "&longitude=" + longitude);
    }

    </script>
</body>

</html>
