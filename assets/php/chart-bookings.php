<?php

include('../../includes/dbcon.php');

// Fetch data from the database based on status
$query = "SELECT status, COUNT(*) as count FROM bookings GROUP BY status";
$result = $con->query($query);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Send the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "Error: " . $query . "<br>" . $con->error;
}

// Close the connection
$con->close();
?>
