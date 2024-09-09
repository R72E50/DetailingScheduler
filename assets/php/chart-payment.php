<?php

include('../../includes/dbcon.php');

// Fetch payment method data
$query = "SELECT payment_method as method, COUNT(*) as count FROM bookings GROUP BY payment_method";
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