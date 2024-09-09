<?php
session_start();
require_once 'config.php';

if (isset($_POST['submit'])) {

    try {
        $response = $gateway->purchase(array(
            'amount' => $_POST['amount'],
            'currency' => PAYPAL_CURRENCY,
            'returnUrl' => PAYPAL_RETURN_URL,
            'cancelUrl' => PAYPAL_CANCEL_URL,
        ))->send();

        if ($response->isRedirect()) {
            $_SESSION['booking_data'] = array(
                'name' => $_POST['name'],
                'user_id' => $_POST['user_id'],
                'email' => $_POST['email'],
                'car_type' => $_POST['car_type'],
                'service' => $_POST['service'],
                'rdate' => $_POST['rdate'],
                'rtime' => $_POST['rtime'],
                'service_type' => $_POST['service_type'],
                'duration' => $_POST['duration'],
            );
            $response->redirect(); // this will automatically forward the customer
        } else {
            // not successful
            echo $response->getMessage();
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

?>
            