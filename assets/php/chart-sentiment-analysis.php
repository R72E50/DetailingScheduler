<?php
include('../../includes/dbcon.php');

// Fetch sentiment analysis data from the database
$sql = "SELECT sentiment, COUNT(*) as count FROM sentiment_analysis GROUP BY sentiment";
$result = $con->query($sql);

// Prepare data in JSON format
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'sentiment' => $row['sentiment'],
        'count' => $row['count']
    );
}

// Close the database connection
$con->close();

// Send the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
