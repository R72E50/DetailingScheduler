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
    require '../../vendor/autoload.php';

    function resend_email_verify($name,$email,$verify_token)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

        $mail->Host       = 'smtp.office365.com';                   //Set the SMTP server to send through
        $mail->Username   = 'Sample_Server00@outlook.com';          //SMTP username
        $mail->Password   = 'sampleserver000';                      //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption 
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom("Sample_Server00@outlook.com",$name);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Email Verification From Xclusive - Auto Spa";

        $email_template = "<h2> You have registered with Xclusive - Auto Spa </h2>
         <h5> Verify your Email Address to login below with the given link </h5>
         <br></br>
         <a href='https://xclusiveautospa.site/verify-email.php?token=$verify_token'>Click Me</a>
        ";

        $mail->Body = $email_template;
        $mail->send();
        
        
    }


if(isset($_POST['verify-button']))
{
    if(!empty(trim($_POST['verify_email'])))
    {
        $email = mysqli_real_escape_string($con,$_POST['verify_email']);
        
        $checkemail_query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $checkemail_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $checkemail_query_run = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($checkemail_query_run) > 0) {
            $row = mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status'] == "0")
            {
                $id = $_GET['id'];
                $fname = $row['fname'];
                $fname = $row['lname'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];

                resend_email_verify($fname,$lname,$email,$verify_token);
                $_SESSION['message'] = "Verification link has been sent to email account";
                header("Location: ../../profile.php?id=$id");
                exit(0); 
            }
            else
            {
                $_SESSION['message'] = "Email Already Verified";
                header("Location:../../profile.php?id=$id");
                exit(0); 
            }
        }
        else
        {
            $_SESSION['message'] = "Email not registered";
            header("Location:../../profile.php");
            exit(0); 
        }
    }
    else
    {
        $_SESSION['message'] = "Email Field must not be empty";
        header("Location: ../../profile.php");
        exit(0); 
    }
}


