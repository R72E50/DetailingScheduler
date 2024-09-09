<?php
session_start();
include('../../includes/dbcon.php');
//https://app-sandbox.nextpay.world/#/pl/Pn_K9BSOv;

//ck_sandbox_xxkei0gmgr66pomsdwi6rvag - Client

//ithhvjr4hp51zcaw863gauju - Secret Key

if (isset($_POST['submit'])) {
    // Store values in sessions
    $_SESSION['user_id'] = $_POST['user_id'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['service'] = $_POST['service'];
    $_SESSION['rdate'] = $_POST['rdate'];
    $_SESSION['rtime'] = $_POST['rtime'];
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['car_type'] = $_POST['car_type'];
    $_SESSION['service_type'] = $_POST['service_type'];
    $_SESSION['duration'] = $_POST['duration'];
    $price = $_POST['price'];
    
}


$description = "Payment for the service booked on Xclusive - Auto Spa.";


$nonce = round(microtime(true) * 1000);

echo $nonce;



$req_body = [
    'title' => "Xclusive - Auto Spa", //
    'amount' => $price,//$var
    'currency' => "PHP",
    'description' => $description,//
    'private_notes' => "Payment will be processed Accordingly",//
    'limit' => 1,
    'redirect_url' => 'https://xclusiveautospa.site/webpages/payment-success.php',
    'nonce' => $nonce,
];

$client_id = "ck_sandbox_xxkei0gmgr66pomsdwi6rvag";
$client_secret = "ithhvjr4hp51zcaw863gauju";

$url = 'https://api-sandbox.nextpay.world/v2/paymentlinks'; // issue 1 - added paymentlinks

$request_body_json = json_encode($req_body, JSON_UNESCAPED_SLASHES);
$signature = hash_hmac('sha256', $request_body_json, $client_secret); // issue 2 - signature computation

$headers = [
    'Content-Type: application/json',
    'client-id: ' . $client_id,
    'signature: ' . $signature,
];

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $request_body_json,
    CURLOPT_HTTPHEADER => $headers,
]);

$response = curl_exec($curl);


$err = curl_error($curl);


curl_close($curl);






if ($err) {
    echo "cURL Error #:" . $err;
} else {
    // Decode JSON response
    $response_data = json_decode($response, true);

    // Check if decoding was successful
    if ($response_data !== null) {
        // Assign values to variables
        $id = $response_data['id'];
        $title = $response_data['title'];
        $amount = $response_data['amount'];
        $currency = $response_data['currency'];
        $description = $response_data['description'];
        $limit = $response_data['limit'];
        $url = $response_data['url'];
    } 

    // Check if any required key is missing
    if ($id === null || $title === null || $amount === null || $currency === null || $description === null || $limit === null || $url === null) {
        echo "One or more required keys are missing in the response.";
    } else {
       
    }

    header("Location: " . $url);
}

?>