<?php
session_start();
include('google-config.php');

if(isset($_POST['logout_btn']))
{
    //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_role']);
    unset($_SESSION['auth_user']);
    $google_client->revokeToken();
    session_destroy();
    $_SESSION['message'] = "Logged Out Successfully";
    header("Location: ../../index.php");
    exit(0);


}
if(isset($_POST['admin_logout_btn']))
{
    //session_destroy();
    unset($_SESSION['admin_auth']);
    unset($_SESSION['admin_auth_role']);
    unset($_SESSION['admin_auth_user']);

    $_SESSION['message'] = "Logged Out Successfully";
    header("Location: https://xclusiveautospa.site/webpages/admin-login.php");
    exit(0);


}
?>