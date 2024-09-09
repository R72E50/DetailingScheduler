<?php
session_start();
require_once 'config.php';

// Once the transaction has been approved, we need to complete it.
if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {
    $transaction = $gateway->completePurchase(array(
        'payer_id'             => $_GET['PayerID'],
        'transactionReference' => $_GET['paymentId'],
    ));
    $response = $transaction->send();

    if ($response->isSuccessful()) {
        // The customer has successfully paid.
        $arr_body = $response->getData();

        $payment_id = $arr_body['id'];
        $payer_id = $arr_body['payer']['payer_info']['payer_id'];
        $payer_email = $arr_body['payer']['payer_info']['email'];
        $amount = $arr_body['transactions'][0]['amount']['total'];
        $currency = PAYPAL_CURRENCY;
        $payment_status = $arr_body['state'];

        // Access the hidden input values
        $bookingData = isset($_SESSION['booking_data']) ? $_SESSION['booking_data'] : array();
        var_dump($bookingData);
        $id = $bookingData['user_id'];
        $name = $bookingData['name'];
        $email = $bookingData['email'];
        $car_type = $bookingData['car_type'];
        $services = $bookingData['service'];
        $rdate =$bookingData['rdate'];
        $rtime = $bookingData['rtime'];
        $service_type = $bookingData['service_type'];
        $duration = $bookingData['duration'];
        $pmethod = 'Paypal';

        // Insert all values into the table
        // Insert all values into the table using prepared statements
        $stmt1 = $db->prepare("INSERT INTO bookings (user_id, name, email, car_type, service, reserve_date, reserve_time, price, payment_method,service_type,duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
        $stmt1->bind_param("isssssdsssi", $id, $name, $email, $car_type, $services, $rdate, $rtime, $amount, $pmethod,$service_type,$duration);
        $stmt1->execute();
        $booking_id = $stmt1->insert_id; // Capture the generated booking_id
       


        $stmt2 = $db->prepare("INSERT INTO payments (payment_id, payer_id, payer_email, amount, currency, payment_status,booking_id) VALUES (?, ?, ?, ?, ?, ?,?)");
        $stmt2->bind_param("ssdsssi", $payment_id, $payer_id, $payer_email, $amount, $currency, $payment_status,$booking_id);
        $stmt2->execute();

        $stmt1->close();
        $stmt2->close();

        //echo "Payment is successful. Your transaction id is: ". $payment_id;
        header("Location: ../../webpages/view-reservations.php");
        exit(0);
    } else {
        echo $response->getMessage();
    }
} else {
    echo 'Transaction is declined';
}
?>
