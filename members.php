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

$current_userid = $_SESSION['userid'];
$sql = "SELECT MemberID, MemberName, Role, Status FROM member WHERE UserID = '$current_userid'";
$result = $conn->query($sql);

$userFirstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Unknown";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $memberName = $_POST['name'] ?? '';
    $status = $_POST['status'] ?? '';
    $role = $_POST['role'] ?? ''; // Add the role variable

    // Prepare SQL statement to insert new member   
    $sql = "INSERT INTO member (UserID, MemberName, Role, Status) 
            VALUES ('$current_userid', '$memberName', '$role', '$status')"; // Include role in the SQL query
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to avoid form resubmission
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
        <img id="profile_s" src="profile.png" alt="Profile Picture">
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
        <img class="icon" src="logout.png" >
    </div>

    <div class="userTable">
        <?php if ($result->num_rows > 0): ?>
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
                        <a href="location.php"><img src="locationpin.png" width="30px" alt="Delete"></a>
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
                
                <label for="status">Status:</label>
                <input type="text" id="status" name="status" required><br><br>
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
            
            // AJAX call to delete user from database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // If deletion was successful, remove the row from the table
                    if (xhr.responseText.trim() === "success") {
                        row.remove();
                    }
                }
            };
            xhr.send("memberid=" + memberID);
        }
    </script>
</body>
</html>
