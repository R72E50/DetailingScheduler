<?php
session_start();
include("../../includes/dbcon.php");



    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    function sendemail_verify($name,$email,$verify_token)
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
        $mail->Subject = "Email Verification From Xclusive Auto Spa";

        $email_template = "<h2> You have registered with Xclusive Auto Spa </h2>
         <h5> Verify your Email Address to login below with the given link </h5>
         <br></br>

        Dear $name,

        <p>We hope this message finds you well. Thank you for choosing to be a part of our community!</p>

        <p>To complete your registration and unlock the full benefits of our platform, please click on the following link:</p>

         <a href='https://xclusiveautospa.site/assets/php/verify-email.php?token=$verify_token'>Verify Account</a>

        <p> By confirming your registration, you ensure that your account is fully activated and ready for use. </p>

        <p>Thank you for choosing us, and we look forward to having you as an active member of our community!</p>

        <p>Best regards,
        Xclusive - Auto Spa</p>";

        $mail->Body = $email_template;
        $mail->send();
        
        
    }

if (isset($_POST['register_btn'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $name = $fname . ' ' . $lname;
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $conpassword = $_POST['conpassword'];
    $verify_token = md5(rand());

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (strlen($password) < 8) {
            $_SESSION['message'] = "Password must be at least 8 characters long!";
            header("Location: ../../webpages/register.php");
            exit(0);
        }

    if ($password == $conpassword) {
            // hashing the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if (!empty($_FILES['profile']['name'])) {
                $img_name = $_FILES['profile']['name'];
                $tmp_name = $_FILES['profile']['tmp_name'];
                $error = $_FILES['profile']['error'];

                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_to_lc = strtolower($img_ex);

                    $allowed_exs = array('jpg', 'jpeg', 'png');

                    if (in_array($img_ex_to_lc, $allowed_exs)) {
                        $new_img_name = uniqid($name, true) . '.' . $img_ex_to_lc;
                        $img_upload_path = '../../img/uploads/' . $new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);

                        // Insert into Database
                        $sql = "INSERT INTO users(name, email,phone, password, verify_token, profile_picture) 
                            VALUES(?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "sssss", $name, $email,$contact, $hashedPassword, $verify_token, $new_img_name);

                            try {
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);

                                sendemail_verify($name, $email, $verify_token);

                                $_SESSION['message'] = "Verification link has been sent to your email";
                                header("Location: ../../webpages/login.php");
                                exit;
                            } catch (Throwable $e) {
                                 // Check if the error is a duplicate entry error
                                if ($e instanceof mysqli_sql_exception && $e->getCode() == 1062) {
                                    $_SESSION['message'] = "Email Already Exists";
                                    header("Location: ../../webpages/register.php");
                                    exit(0);
                                } else {
                                    // Handle other errors
                                    $_SESSION['message'] = "Something Went Wrong";
                                    header("Location: ../../webpages/register.php");
                                    exit(0);
                                }
                            }
                        } else {
                            $_SESSION['message'] = "Something Went Wrong";
                            header("Location: ../../webpages/register.php");
                            exit(0);
                        }
                    } else {
                        $_SESSION['message'] = "You can't Upload this type of File";
                        header("Location: ../../webpages/register.php");
                        exit(0);
                    }
                } else {
                    $_SESSION['message'] = "Something Went Wrong";
                    header("Location: ../../webpages/register.php");
                    exit(0);
                }
            } else {
                // Insert into Database without profile picture
                $profile_picture = 'default-pp.png';
                $sql = "INSERT INTO users(name, email,phone, password, verify_token,profile_picture) VALUES (?, ?, ?, ?,?,?)";
                $stmt = mysqli_prepare($con, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email,$contact, $hashedPassword, $verify_token, $profile_picture);

                    try {
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        sendemail_verify($name, $email, $verify_token);

                        $_SESSION['message'] = "Verification link has been sent to your email ";
                        header("Location: ../../webpages/login.php");
                        exit;
                    } catch (Throwable $e) {
                        // Check if the error is a duplicate entry error
                        if ($e instanceof mysqli_sql_exception && $e->getCode() == 1062) {
                            $_SESSION['message'] = "Email Already Exists";
                            header("Location: ../../webpages/register.php");
                            exit(0);
                        } else {
                            // Handle other errors
                            $_SESSION['message'] = "Something Went Wrong";
                            header("Location: ../../webpages/register.php");
                            exit(0);
                        }
                    }
                } else {
                    $_SESSION['message'] = "Something Went Wrong";
                    header("Location: ../../webpages/register.php");
                    exit(0);
                }
            }
        }
    } else {
        $_SESSION['message'] = "Invalid Email address";
        header("Location: ../../webpages/register.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "Error Occurred!";
    header("Location: ../../webpages/register.php");
    exit;
}
?>
