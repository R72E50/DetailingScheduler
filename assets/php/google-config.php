<?php
//start session on web page
include('../../includes/dbcon.php');
//config.php


//Include Google Client Library for PHP autoload file
require_once '../vendor/vendor/autoload.php';


//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('https://xclusiveautospa.site/assets/php/google-login-code.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>