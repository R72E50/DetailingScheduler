<?php
session_start();
include_once('../../includes/dbcon.php');
include_once('../tcpdf/tcpdf.php');

$booking_id = $_GET['id'];

// Use prepared statement to prevent SQL injection
$sql = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Start TCPDF
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Loop through data and add to PDF
    while ($row = $result->fetch_assoc()) {

        // Set up HTML
        $html = '<h1 style="text-align: center; font-weight: bold; font-family: courier;">Park N Wash</h1>';
        $html .= '<p style="text-align: center; font-family: courier;"><i>28 Pasig Blvd C5 Road</i></p>';
        $html .= '<div style="text-align: center; border-top: 1px dashed #000; margin: 10px;"></div>';
        $html .= '<p style="font-family: courier;">Transaction ID: ' . $row['id'] . '</p>';
        $html .= '<p style="font-family: courier;">Date: ' . date("D d, M y") . '</p>';
        $html .= '<p style="font-family: courier;">Name: ' . $row['fname'] . ' ' . $row['lname'] . '</p>';
        $html .= '<div style="text-align: center; border-top: 1px dashed #000; margin: 10px;"></div>';
        $html .= '<p style="font-family: courier;">Service: ' . $row['cartype'] . ' ' . $row['service'] . '</p>';
        $html .= '<p style="font-family: courier;">Date Booked: ' . $row['rdate'] . ' ' . $row['rtime'] . '</p>';
        $html .= '<p style="font-family: courier;">Payment: ' . $row['pmethod'] . ' ' . $row['price'] . '</p>';
        $html .= '<div style="text-align: center; border-top: 1px dashed #000; margin: 10px;"></div>';
        $html .= '<p style="text-align: right; font-weight: bold; font-family: courier;">TOTAL: ' . $row['pmethod'] . ' ' . $row['price'] . '</p>';
        $html .= '<div style="text-align: center; border-top: 1px dashed #000; margin: 10px;"></div>';
        $html .= '<h2 style="text-align: center; font-weight: bold; font-family: courier;">Thank you for your business!</h2>';

        // Write HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    // Output the PDF
    ob_clean();
    $pdf->Output('report.pdf', 'I');
    ob_end_clean();
}

$stmt->close();
$con->close();
?>
