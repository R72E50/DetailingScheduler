<?php

session_start();
include('../../includes/dbcon.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Use prepared statements to prevent SQL injection
    $verify_query = "SELECT verify_token, verify_status FROM users WHERE verify_token=? LIMIT 1";
    $stmt = $con->prepare($verify_query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['verify_status'] == "0") {
            $clicked_token = $row['verify_token'];

            // Use prepared statements to prevent SQL injection
            $update_query = "UPDATE users SET verify_status = '1' WHERE verify_token=? LIMIT 1";
            $stmt = $con->prepare($update_query);
            $stmt->bind_param("s", $clicked_token);
            $stmt->execute();
            $stmt->close();

            if ($con->affected_rows > 0) {
                $_SESSION['message'] = "Your account has been verified successfully.";
                header("Location: ../../webpages/login.php");
                exit(0);
            } else {
                $_SESSION['message'] = "Verification failed";
                header("Location: ../../webpages/register.php");
                exit(0);
            }
        } else {
            $_SESSION['message'] = "Email Already Verified, Proceed to Login";
            header("Location: ../../webpages/register.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Token does not exist";
        header("Location: ../../webpages/register.php");
        exit(0);
    }
} else {
    // Handle the case when the 'token' parameter is not set
}
?>
