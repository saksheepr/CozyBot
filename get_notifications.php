    <?php

    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CozyBot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is logged in
    if (!isset($_SESSION['userid'])) {
        die("User is not logged in.");
    }

    $current_userid = $_SESSION['userid'];

    $sql = "SELECT * FROM notifications WHERE UserID = $current_userid ORDER BY CreatedAt DESC";
    $result = $conn->query($sql);

    $notifications = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
    }

    // Output notifications as JSON
    echo json_encode($notifications);

    $conn->close();
    ?>
