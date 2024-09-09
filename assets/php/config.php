<?php
require_once "../vendor/autoload.php";
 
use Omnipay\Omnipay;
 
define('CLIENT_ID', 'AY4zJDOpq8X8GM1HpZndLQ02iKQI-P_goo5ePCGJvHb8BXt0mP6GIEFfKTynheUmVYdpcMRsMYtDdhWY');
define('CLIENT_SECRET', 'ECD-gSbgo8tBJGoqPSE8WPGoCaudxDAHr-Q20ITWRpxTGvPeTmcbCDTno7SLn50PpVt8V5ID4Kto098j');
 
define('PAYPAL_RETURN_URL', 'https://xclusiveautospa.site/assets/php/success.php');
define('PAYPAL_CANCEL_URL', 'https://xclusiveautospa.site/assets/php/cancel.php');
define('PAYPAL_CURRENCY', 'PHP'); // set your currency here
 
// Connect with the database
$db = new mysqli('localhost', 'u847975301_xclusive', 'Xclusive#123', 'u847975301_xclusive'); 
 
if ($db->connect_errno) {
    die("Connect failed: ". $db->connect_error);
}
 
$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); //set it to 'false' when go live