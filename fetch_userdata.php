<?php
    // Assuming you have initialized session
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "cozybot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Assuming $user_id is retrieved from the session
    $user_id = $_SESSION['userid'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT username, firstname, lastname, email, password FROM user WHERE userid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<script>
            document.getElementById('username').value = '{$row['username']}';
            document.getElementById('firstname').value = '{$row['firstname']}';
            document.getElementById('lastname').value = '{$row['lastname']}';
            document.getElementById('email').value = '{$row['email']}';
            document.getElementById('password').value = '{$row['password']}';
        </script>";
    } else {
        echo "User data not found.";
    }

    $stmt->close();
    $conn->close();
?>
