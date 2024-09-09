<?php
//
session_start();
include('../../includes/dbcon.php');


//Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    function send_password_reset($name,$email,$token)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

        $mail->Host       = 'smtp.hostinger.com';                   //Set the SMTP server to send through
        $mail->Username   = 'administrator@xclusiveautospa.site';          //SMTP username
        $mail->Password   = 'Administrator#123';                      //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption 
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom("administrator@xclusiveautospa.site",$name);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset From Xclusive - Auto Spa";

        $email_template = "<h2> You requested a password change with Xclusive - Auto Spa </h2>
         <h5> If this is not you, We recommend to change your password </h5>
         <br></br>
         <a href='https://xclusiveautospa.site/webpages/password-reset.php?token=$token'>Click Me</a> 
        ";
        // You can add the $email next at the token if you want to include it on password change
        $mail->Body = $email_template;
        $mail->send();
        
        
    }

    if(isset($_POST['send_btn']))
    {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $token = md5(rand());

        $check_email = "SELECT name,email FROM users WHERE email = ? LIMIT 1";
        $stmt_check_email = mysqli_prepare($con, $check_email);
        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
        mysqli_stmt_execute($stmt_check_email);
        mysqli_stmt_store_result($stmt_check_email);

        if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
            mysqli_stmt_bind_result($stmt_check_email, $name, $email);
            mysqli_stmt_fetch($stmt_check_email);

            

            $update_token = "UPDATE users SET verify_token = ? WHERE email = ? LIMIT 1";
            $stmt_update_token = mysqli_prepare($con, $update_token);
            mysqli_stmt_bind_param($stmt_update_token, "ss", $token, $email);
            $update_token_run = mysqli_stmt_execute($stmt_update_token);

            if ($update_token_run) {
                send_password_reset($name, $email, $token);
                $_SESSION['message'] = "The password reset link has been sent to your email";
                header("Location: https://xclusiveautospa.site/webpages/forgot-password.php");
                exit(0);
            } else {
                $_SESSION['message'] = "Something went wrong #1";
                header("Location: https://xclusiveautospa.site/xclusive/webpages/forgot-password.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['message'] = "No Email Found";
            header("Location: https://xclusiveautospa.site/webpages/forgot-password.php");
            exit(0); 
        }
    }

    if (isset($_POST['update_btn'])) {
        $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($con, $_POST['con_password']);
        $token = mysqli_real_escape_string($con, $_POST['password_token']);
    
        if (!empty($token)) {
            if (!empty($new_password) && !empty($confirm_password)) {
                // Check if the token is valid or not
                $check_token = "SELECT verify_token FROM users WHERE verify_token = ? LIMIT 1";
                $stmt_check_token = mysqli_prepare($con, $check_token);
                mysqli_stmt_bind_param($stmt_check_token, "s", $token);
                mysqli_stmt_execute($stmt_check_token);
                mysqli_stmt_store_result($stmt_check_token);
    
                if (mysqli_stmt_num_rows($stmt_check_token) > 0) {
                    if ($new_password == $confirm_password) {
                        $update_password = "UPDATE users SET password = ? WHERE verify_token = ? LIMIT 1";
                        $stmt_update_password = mysqli_prepare($con, $update_password);
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the password
                        mysqli_stmt_bind_param($stmt_update_password, "ss", $hashed_password, $token);
                        $update_password_run = mysqli_stmt_execute($stmt_update_password);
    
                        if ($update_password_run) {
                            $_SESSION['message'] = "New password Successfully Updated";
                            header("Location: https://xclusiveautospa.site/webpages/login.php");
                            exit(0);
                        } else {
                            $_SESSION['message'] = "Something Went Wrong";
                            header("Location: https://xclusiveautospa.site/webpages/password-reset.php?token=$token");
                            exit(0);
                        }
                    } else {
                        $_SESSION['message'] = "Password Does Not Match";
                        header("Location: https://xclusiveautospa.site/webpages/password-reset.php?token=$token");
                        exit(0);
                    }
                } else {
                    $_SESSION['message'] = "Invalid Token";
                    header("Location: https://xclusiveautospa.site/webpages/password-reset.php?token=$token");
                    exit(0);
                }
            } else {
                $_SESSION['message'] = "Entry Field must not be empty";
                header("Location: https://xclusiveautospa.site/password-reset.php?token=$token");
                exit(0);
            }
        } else {
            $_SESSION['message'] = "No token Available";
            header("Location: https://xclusiveautospa.site/password-reset.php?token=$token");
            exit(0);
        }
    }
?>