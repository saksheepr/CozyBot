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

// Fetch data from the DeviceConsumption table
$sql = "SELECT DeviceID, DATE(StartTime) AS Date, SUM(ConsumedUnits) AS TotalConsumption 
        FROM DeviceConsumption 
        WHERE UserID = $current_userid
        GROUP BY DeviceID, DATE(StartTime)";
$result = $conn->query($sql);

// Initialize arrays to store labels and datasets
$labels = [];
$datasets = [];

// Process the fetched data
while ($row = $result->fetch_assoc()) {
    $deviceID = $row['DeviceID'];
    $date = $row['Date'];
    $totalConsumption = $row['TotalConsumption'];

    // Add label if not already added
    if (!in_array($date, $labels)) {
        $labels[] = $date;
    }

    // Find index of dataset for this device, if exists
    $datasetIndex = array_search($deviceID, array_column($datasets, 'label'));

    // If dataset for this device does not exist, create it
    if ($datasetIndex === false) {
        $datasets[] = [
            'label' => "Device $deviceID Consumption",
            'data' => [],
            'borderColor' => 'blue',
            'borderWidth' => 1
        ];
        $datasetIndex = count($datasets) - 1;
    }

    // Add consumption data for this device and date
    $datasets[$datasetIndex]['data'][] = $totalConsumption;
}

// Convert arrays to JSON for JavaScript
$jsonLabels = json_encode($labels);
$jsonDatasets = json_encode($datasets);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Graphs</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Placeholder for the graph -->
    <canvas id="deviceConsumptionChart" width="400" height="200"></canvas>

    <script>
        // JavaScript code to render the chart
        // Initialize Chart.js
        const ctx = document.getElementById('deviceConsumptionChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $jsonLabels; ?>,
                datasets: <?php echo $jsonDatasets; ?>
            },
            options: {
                responsive: false, // Prevent the chart from resizing to fit its container
                maintainAspectRatio: false, // Prevent the aspect ratio from being maintained
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
