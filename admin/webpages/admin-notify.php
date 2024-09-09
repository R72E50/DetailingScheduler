<?php
session_start();
include("../../includes/dbcon.php");


function formatTime($timeStr) {
    $timeObj = DateTime::createFromFormat('H:i:s.u', $timeStr);
    return $timeObj->format('h:i A');
}

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../../assets/vendor/autoload.php';

    function send_notification($name,$email,$service,$date,$time)
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
        $mail->Subject = "Booking Notification From Xclusive Auto Spa";
        
        $email_template = "<h2>Xclusive Auto Spa Booking Reminder</h2> <p>Dear " . $name . ",</p><p>This is a friendly reminder for your upcoming reservation with Xclusive Auto Spa. We look forward to providing you with our carwash & auto detailing service. Here are the details:</p><ul> <li><strong>Name:</strong>" . $name . "</li> <li><strong>Service:</strong> " . $service . "</li> <li><strong>Date:</strong> " . $date . "</li> <li><strong>Time:</strong> " . $time . "</li> </ul> <p>Your vehicle is scheduled for a thorough detailing, and we look forward to providing you with excellent service.</p> <p>If you have any questions or need to make changes to your reservation, please feel free to contact us at our website.</p> <p>Thank you again for choosing our auto detailing service. We can't wait to make your vehicle shine!</p> <p>Best regards,<br>Xclusive Auto Spa</p>";


        $mail->Body = $email_template;
        $mail->send();
        header("Location: /admin/webpages/admin-view-assigned-booking.php");
        exit(0); 
        
    }

    if (isset($_POST['notify_btn'])) {
        
        
        if(!empty(trim($_POST['booking_id'])))
        {
            $booking_id = mysqli_real_escape_string($con, $_POST['booking_id']);
            
            $bookings_query = "SELECT * FROM bookings WHERE booking_id = ? LIMIT 1";
            $stmt = mysqli_prepare($con, $bookings_query);
            mysqli_stmt_bind_param($stmt, "i", $booking_id);
            mysqli_stmt_execute($stmt);

            $bookings_query_run = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($bookings_query_run) > 0) {
                $row = mysqli_fetch_array($bookings_query_run);
                
                    $name = $row['name'];
                   
                    $email = $row['email'];
                    $service = $row['service'];
                    $date = $row['reserve_date'];
                    $time = formatTime($row['reserve_time']);

                    send_notification($name,$email,$service,$date,$time);
            }
            else
            {
                $_SESSION['message'] = "Notification not sent";
                    header("Location: https://xclusiveautospa.site/admin/webpages/admin-view-assigned-booking.php");
                    exit(0); 

            }
        }
        else
        {
            $_SESSION['message'] = "Something Went Wrong";
            header("Location: https://xclusiveautospa.site/admin/webpages/view-assigned-booking.php");
            exit(0); 
        }
    }
?>