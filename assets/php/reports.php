<?php
session_start();
include_once('../../includes/dbcon.php');
include_once('../tcpdf/tcpdf.php');
if (isset($_POST['weeklyAnalysis'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Weekly Sentiment Analysis Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this week)
    $startOfWeek = date('Y-m-d', strtotime('last Sunday'));
    $endOfWeek = date('Y-m-d', strtotime('this Saturday'));
    $pdf->Cell(0, 10, 'Date Range: ' . $startOfWeek . ' to ' . $endOfWeek, 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides a weekly sentiment analysis for the current week.', 0, 'L');

    // Calculate the start and end timestamps for this week
    $startTimestamp = strtotime($startOfWeek . ' 00:00:00');
    $endTimestamp = strtotime($endOfWeek . ' 23:59:59');

    // Query to select all values for this week
    $query = "SELECT id, neg, neu, pos, comp, sentiment FROM sentiment_analysis 
              WHERE STR_TO_DATE(created_at, '%Y-%m-%d %H:%i:%s') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Negativity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Neutrality', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Positivity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Compound', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Sentiment', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        $totalGood = $totalBad = $totalNeutral = 0;

        // Fetch the data and add data rows with color based on sentiment
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['neg'], 1, 0, 'C', getSentimentColor($row['neg']));
            $pdf->Cell(30, 10, $row['neu'], 1, 0, 'C', getSentimentColor($row['neu']));
            $pdf->Cell(30, 10, $row['pos'], 1, 0, 'C', getSentimentColor($row['pos']));
            $pdf->Cell(30, 10, $row['comp'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['sentiment'], 1, 1, 'C', getSentimentColor($row['sentiment']));

            // Update total sentiment counts
            switch ($row['sentiment']) {
                case 'positive':
                    $totalGood++;
                    break;
                case 'negative':
                    $totalBad++;
                    break;
                case 'neutral':
                    $totalNeutral++;
                    break;
            }
        }

        // Display total sentiment counts
        $pdf->Cell(20, 10, 'Total Good:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalGood, 1, 0, 'C', getSentimentColor('positive'));
        $pdf->Cell(30, 10, 'Total Bad:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalBad, 1, 0, 'C', getSentimentColor('negative'));
        $pdf->Cell(30, 10, 'Total Neutral:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalNeutral, 1, 1, 'C', getSentimentColor('neutral'));

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('weekly_sentiment_analysis_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['monthlyAnalysis'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Monthly Sentiment Analysis Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this month)
    $startOfMonth = date('Y-m-01');
    $endOfMonth = date('Y-m-t');
    $pdf->Cell(0, 10, 'Date Range: ' . $startOfMonth . ' to ' . $endOfMonth, 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides a monthly sentiment analysis for the current month.', 0, 'L');

    // Calculate the start and end timestamps for this month
    $startTimestamp = strtotime($startOfMonth . ' 00:00:00');
    $endTimestamp = strtotime($endOfMonth . ' 23:59:59');

    // Query to select all values for this month
    $query = "SELECT id, neg, neu, pos, comp, sentiment FROM sentiment_analysis 
              WHERE STR_TO_DATE(created_at, '%Y-%m-%d %H:%i:%s') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Negativity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Neutrality', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Positivity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Compound', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Sentiment', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        $totalGood = $totalBad = $totalNeutral = 0;

        // Fetch the data and add data rows with color based on sentiment
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['neg'], 1, 0, 'C', getSentimentColor($row['neg']));
            $pdf->Cell(30, 10, $row['neu'], 1, 0, 'C', getSentimentColor($row['neu']));
            $pdf->Cell(30, 10, $row['pos'], 1, 0, 'C', getSentimentColor($row['pos']));
            $pdf->Cell(30, 10, $row['comp'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['sentiment'], 1, 1, 'C', getSentimentColor($row['sentiment']));

            // Update total sentiment counts
            switch ($row['sentiment']) {
                case 'positive':
                    $totalGood++;
                    break;
                case 'negative':
                    $totalBad++;
                    break;
                case 'neutral':
                    $totalNeutral++;
                    break;
            }
        }

        // Display total sentiment counts
        $pdf->Cell(20, 10, 'Total Good:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalGood, 1, 0, 'C', getSentimentColor('positive'));
        $pdf->Cell(30, 10, 'Total Bad:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalBad, 1, 0, 'C', getSentimentColor('negative'));
        $pdf->Cell(30, 10, 'Total Neutral:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalNeutral, 1, 1, 'C', getSentimentColor('neutral'));

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('monthly_sentiment_analysis_report.pdf', 'I');
    ob_end_clean();
}


if (isset($_POST['dailyAnalysis'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Daily Sentiment Analysis Report', 1, 1, 'C', 1);
    
    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black
    
    // Add date (for today)
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides a daily sentiment analysis for the current month.', 0, 'L');

    // Calculate the start and end timestamps for today
    $startTimestamp = strtotime('today 00:00:00');
    $endTimestamp = strtotime('today 23:59:59');

    // Query to select all values for today
    $query = "SELECT id, neg, neu, pos, comp, sentiment FROM sentiment_analysis 
              WHERE STR_TO_DATE(created_at, '%Y-%m-%d %H:%i:%s') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Negativity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Neutrality', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Positivity', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Compound', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Sentiment', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        $totalGood = $totalBad = $totalNeutral = 0;

        // Fetch the data and add data rows with color based on sentiment
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['neg'], 1, 0, 'C', getSentimentColor($row['neg']));
            $pdf->Cell(30, 10, $row['neu'], 1, 0, 'C', getSentimentColor($row['neu']));
            $pdf->Cell(30, 10, $row['pos'], 1, 0, 'C', getSentimentColor($row['pos']));
            $pdf->Cell(30, 10, $row['comp'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['sentiment'], 1, 1, 'C', getSentimentColor($row['sentiment']));

            // Update total sentiment counts
            if ($row['sentiment'] == 'positive') {
                $totalGood++;
            } elseif ($row['sentiment'] == 'negative') {
                $totalBad++;
            } else {
                $totalNeutral++;
            }
        }

        // Display total sentiment counts
        $pdf->Cell(20, 10, 'Total Good:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalGood, 1, 0, 'C', getSentimentColor('good'));
        $pdf->Cell(30, 10, 'Total Bad:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalBad, 1, 0, 'C', getSentimentColor('bad'));
        $pdf->Cell(30, 10, 'Total Neutral:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalNeutral, 1, 1, 'C', getSentimentColor('neutral'));
        
        
       

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('daily_sentiment_analysis_report.pdf', 'I');
    ob_end_clean();
}

// Function to determine sentiment color
function getSentimentColor($value) {
    $color = '';

    switch ($value) {
        case 'positive':
            $color = array(144, 238, 144); // Light green
            break;
        case 'negative':
            $color = array(255, 99, 71); // Light red
            break;
        case 'neutral':
            $color = array(255, 255, 153); // Light yellow
            break;
        default:
            // Default color for unknown sentiment
            $color = array(255, 255, 255); // White
    }

    return $color;
}



if (isset($_POST['dailyPayment'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Payment Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides payment information for today.', 0, 'L');

    // Calculate the start and end timestamps for today
    $startTimestamp = strtotime(date('Y-m-d 00:00:00'));
    $endTimestamp = strtotime(date('Y-m-d 23:59:59'));

    // Query to select payment-related values for today
    // Modify the SQL query to use STR_TO_DATE for timestamp conversion
    $query = "SELECT booking_id, user_id, payment_method, price 
              FROM bookings 
              WHERE status = 'Completed' 
              AND payment_status = 1 
              AND STR_TO_DATE(reservation_updated, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";


    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);

        $pdf->Cell(40, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        // Initialize total variable
        $totalPayments = 0;

        while ($row = $result->fetch_assoc()) {
        $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['payment_method'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['price'], 1, 1, 'C');
        
    
        // Accumulate total payments
        $totalPayments += $row['price'];
    }
    $pdf->Cell(90, 10, 'Total Payments:', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, $totalPayments, 1, 1, 'C');
    
    // Free the result set
    $result->free();
    
    
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('payment_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['weeklyPayment'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Payment Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this week)
    $pdf->Cell(0, 10, 'Date Range: ' . date('Y-m-d', strtotime('this week Monday')) . ' to ' . date('Y-m-d', strtotime('this week Sunday')), 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides payment information for this week.', 0, 'L');

    // Calculate the start and end timestamps for this week
    $startTimestamp = strtotime('this week Monday 00:00:00');
    $endTimestamp = strtotime('this week Sunday 23:59:59');

    // Query to select payment-related values for this week
    // Modify the SQL query to use STR_TO_DATE for timestamp conversion
    $query = "SELECT booking_id, user_id, payment_method, price 
              FROM bookings 
              WHERE status = 'Completed' 
              AND payment_status = 1 
              AND STR_TO_DATE(reservation_updated, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);

        $pdf->Cell(40, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        // Initialize total variable
        $totalPayments = 0;

        while ($row = $result->fetch_assoc()) {
        $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['payment_method'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['price'], 1, 1, 'C');
        
    
        // Accumulate total payments
        $totalPayments += $row['price'];
    }
    $pdf->Cell(90, 10, 'Total Payments:', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, $totalPayments, 1, 1, 'C');
    
    // Free the result set
    $result->free();
    
    
    
    
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('weekly_payment_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['monthlyPayment'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Payment Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this month)
    $pdf->Cell(0, 10, 'Date Range: ' . date('Y-m-01') . ' to ' . date('Y-m-t'), 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This formal report provides payment information for this month.', 0, 'L');

    // Calculate the start and end timestamps for this month
    $startTimestamp = strtotime('first day of this month 00:00:00');
    $endTimestamp = strtotime('last day of this month 23:59:59');

    // Query to select payment-related values for this month
    // Modify the SQL query to use STR_TO_DATE for timestamp conversion
    $query = "SELECT booking_id, user_id, payment_method, price 
              FROM bookings 
              WHERE status = 'Completed' 
              AND payment_status = 1 
              AND STR_TO_DATE(reservation_updated, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);
        $pdf->Cell(40, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 1, 'C', 1);

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        // Initialize total variable
        $totalPayments = 0;

        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['payment_method'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['price'], 1, 1, 'C');

            // Accumulate total payments
            $totalPayments += $row['price'];
        }
        $pdf->Cell(90, 10, 'Total Payments:', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, $totalPayments, 1, 1, 'C');

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('monthly_payment_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['dailyBooking'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Daily Bookings Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date (for today)
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This report provides information about daily bookings.', 0, 'L');

    // Calculate the start and end timestamps for today
    $startTimestamp = strtotime('today 00:00:00');
    $endTimestamp = strtotime('today 23:59:59');

    // Query to select booking-related values for today
    $query = "SELECT booking_id, user_id, payment_method, price, service_type, car_type, status
              FROM bookings 
              WHERE STR_TO_DATE(reservation_created, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);
        $pdf->Cell(29, 10, 'Service Type', 1, 0, 'C', 1);
        $pdf->Cell(31, 10, 'Car Type', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 0, 'C', 1);
        $pdf->Cell(28, 10, 'Status', 1, 1, 'C', 1); // New header for status

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
            $pdf->Cell(29, 10, $row['service_type'], 1, 0, 'C');
            $pdf->Cell(31, 10, $row['car_type'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['payment_method'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['price'], 1, 0, 'C');
            $pdf->Cell(28, 10, $row['status'], 1, 1, 'C'); // Display status
        }

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('daily_bookings_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['weeklyBooking'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Weekly Bookings Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this week)
    $startOfWeek = date('Y-m-d', strtotime('this week Monday'));
    $endOfWeek = date('Y-m-d', strtotime('this week Sunday'));
    $pdf->Cell(0, 10, 'Date Range: ' . $startOfWeek . ' to ' . $endOfWeek, 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This report provides information about weekly bookings.', 0, 'L');

    // Calculate the start and end timestamps for this week
    $startTimestamp = strtotime('this week Monday 00:00:00');
    $endTimestamp = strtotime('this week Sunday 23:59:59');

    // Query to select booking-related values for this week
    $query = "SELECT booking_id, user_id, payment_method, price, service_type, car_type, status
              FROM bookings 
              WHERE STR_TO_DATE(reservation_created, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);
        $pdf->Cell(29, 10, 'Service Type', 1, 0, 'C', 1);
        $pdf->Cell(31, 10, 'Car Type', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 0, 'C', 1);
        $pdf->Cell(28, 10, 'Status', 1, 1, 'C', 1); // New header for status

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
            $pdf->Cell(29, 10, $row['service_type'], 1, 0, 'C');
            $pdf->Cell(31, 10, $row['car_type'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['payment_method'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['price'], 1, 0, 'C');
            $pdf->Cell(28, 10, $row['status'], 1, 1, 'C'); // Display status
        }

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('weekly_bookings_report.pdf', 'I');
    ob_end_clean();
}

if (isset($_POST['monthlyBooking'])) {
    // Create PDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'N', 12);

    // Add title with color
    $pdf->SetFillColor(41, 43, 44); // #292b2c
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->Cell(0, 10, 'Xclusive - Auto Spa', 1, 1, 'C', 1);
    $pdf->Cell(0, 10, 'Monthly Bookings Report', 1, 1, 'C', 1);

    // Reset color
    $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
    $pdf->SetTextColor(0, 0, 0); // Reset text color to black

    // Add date range (for this month)
    $startOfMonth = date('Y-m-01');
    $endOfMonth = date('Y-m-t');
    $pdf->Cell(0, 10, 'Date Range: ' . $startOfMonth . ' to ' . $endOfMonth, 0, 1, 'C');

    $pdf->MultiCell(0, 10, 'This report provides information about monthly bookings.', 0, 'L');

    // Calculate the start and end timestamps for this month
    $startTimestamp = strtotime('first day of this month 00:00:00');
    $endTimestamp = strtotime('last day of this month 23:59:59');

    // Query to select booking-related values for this month
    $query = "SELECT booking_id, user_id, payment_method, price, service_type, car_type, status
              FROM bookings 
              WHERE STR_TO_DATE(reservation_created, '%Y-%m-%d %H:%i:%s.%f') BETWEEN FROM_UNIXTIME($startTimestamp) AND FROM_UNIXTIME($endTimestamp)";

    // Execute the query
    $result = $con->query($query);

    if ($result) {
        // Add table headers with color
        $pdf->SetFillColor(41, 43, 44); // #292b2c
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(25, 10, 'Booking ID', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'User ID', 1, 0, 'C', 1);
        $pdf->Cell(29, 10, 'Service Type', 1, 0, 'C', 1);
        $pdf->Cell(31, 10, 'Car Type', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Payment Method', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Price', 1, 0, 'C', 1);
        $pdf->Cell(28, 10, 'Status', 1, 1, 'C', 1); // New header for status

        // Reset color
        $pdf->SetFillColor(255, 255, 255); // Reset fill color to white
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black

        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(25, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['user_id'], 1, 0, 'C');
            $pdf->Cell(29, 10, $row['service_type'], 1, 0, 'C');
            $pdf->Cell(31, 10, $row['car_type'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['payment_method'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['price'], 1, 0, 'C');
            $pdf->Cell(28, 10, $row['status'], 1, 1, 'C'); // Display status
        }

        // Free the result set
        $result->free();
    } else {
        echo "Error: " . $con->error;
    }

    // Output PDF to browser or save to a file
    ob_clean();
    $pdf->Output('monthly_bookings_report.pdf', 'I');
    ob_end_clean();
}




?>

