<?php
//
session_start();
include('../../includes/dbcon.php');//since no authentication yet
//Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    function formatTime($timeStr) {
        $timeObj = DateTime::createFromFormat('H:i:s.u', $timeStr);
        return $timeObj->format('h:i A');
    }

if(isset($_POST['booking_delete']))
{
    $user_id = $_POST['booking_delete'];

    $query = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);


    
    if(mysqli_stmt_affected_rows($stmt) > 0)
    {
        $_SESSION['message'] = "Booking Deleted Successfully";
        header('Location: ../../webpages/view-reservations.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../../webpages/view-reservations.php');
        exit(0);
    }
}



if(isset($_POST['add_booking']))
{   
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $car_type = $_POST['car_type'];
    $services = $_POST['service'];
    $reserve_date = $_POST['rdate'];
    $reserve_time = $_POST['rtime'];
    $price = $_POST['price'];
    $service_type = $_POST['service_type'];
    $duration = $_POST['duration'];
    $pmethod = 'Cash';

    
    $query = "INSERT INTO bookings (user_id, name, email, car_type, service, reserve_date, reserve_time, price, payment_method,service_type,duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "isssssssssi", $user_id, $name, $email, $car_type, $services, $reserve_date, $reserve_time, $price, $pmethod,$service_type,$duration);
    mysqli_stmt_execute($stmt);

    
    if(mysqli_stmt_affected_rows($stmt))
    {
        $_SESSION['message'] = "Booking Added Successfully";
        header("Location: ../../webpages/view-reservations.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../../webpages/confirmation.php');
        exit(0);
    }
    
}

if (isset($_POST['update_booking'])) {
    $user_id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $cartype = $_POST['cartype'];
    $service = $_POST['service'];
    $slot = $_POST['slot'];
    $rdate = $_POST['rdate'];
    $rtime = $_POST['rtime'];
    $price = $_POST['price'];
    $pmethod = $_POST['pmethod'];

    $query = "UPDATE bookings SET fname=?, lname=?, email=?, cartype=?, service=?, slot=?, rdate=?, rtime=?, price=?, pmethod=? WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    
    mysqli_stmt_bind_param($stmt, "ssssssssdsi", $fname, $lname, $email, $cartype, $service, $slot, $rdate, $rtime, $price, $pmethod, $user_id);
    
    $query_run = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Booking Details Updated Successfully";
        header('Location: ../../webpages/view-reservations.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: ../../webpages/view-reservations.php');
        exit(0);
    }
}


if (isset($_POST['update_profile'])) {
    $user_id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $pmethod = $_POST['pmethod'];
    $phone = $_POST['phone'];

    $query = "UPDATE users SET fname=?, lname=?, email=?, pmethod=?, phone=? WHERE id=?";
    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_bind_param($stmt, "sssssi", $fname, $lname, $email, $pmethod, $phone, $user_id);

    $query_run = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($query_run) {
        $_SESSION['message'] = "User Profile Updated Successfully";
        header('Location: ../../index.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: ../../webpages/view-reservations.php');
        exit(0);
    }
}


// Reschedule Booking From Waitlist, Will Send an Email Notification
function user_refund_notification($name,$email,$service,$date,$time,$reason)
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
    $mail->Subject ="Booking Cancellation - Xclusive - Auto Spa";

    $email_template="<p>Dear <?php echo $name; ?>,</p>

    <p>We hope this message finds you well. We regret to inform you that your scheduled booking with us has been canceled. It appears that you have initiated this cancellation.</p>
    
    <p><strong>Booking Details:</strong></p>
    <ul>
        <li>Service: <?php echo $service; ?></li>
        <li>Date: <?php echo $date; ?></li>
        <li>Time: <?php echo $time; ?></li>
    </ul>
    
    <p><strong>Reason for Cancellation:</strong> <?php echo $reason; ?></p>
    
    <p>We understand that circumstances may change, and we respect your decision to cancel the booking. If you have any concerns or would like to reschedule, please contact our customer support at our website. We'll be happy to assist you in finding a suitable alternative.</p>
    
    <p>Your payment for this booking will be refunded. The refund process may take some time, and we appreciate your patience in this regard.</p>
    
    <p>Once again, we appreciate your understanding, and we look forward to the opportunity to serve you in the future.</p>
    
    <p>Best regards,<br>Xclusive - Auto Spa</p>";

    $mail->Body = $email_template;
    $mail->send();
    header('Location: https://xclusiveautospa.site/webpages/view-reservations.php');
    exit(0);
    
}

if (isset($_POST['refund_booking'])) {
    $booking_id = $_POST['booking_id'];
    $cancel_reason = htmlspecialchars($_POST['cancel_reason'], ENT_QUOTES, 'UTF-8');
    $Refund= 'Refund';


    $query = "UPDATE bookings SET status = ? WHERE booking_id=?";
    $stmt = mysqli_prepare($con, $query);

    mysqli_stmt_bind_param($stmt, "si", $Refund, $booking_id);

    $query_run = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($query_run) {
    
            $booking_query = "SELECT users.name, users.email, bookings.service, bookings.reserve_date, bookings.reserve_time FROM users INNER JOIN bookings ON users.id = bookings.user_id WHERE bookings.booking_id = ?";
            $booking_stmt = mysqli_prepare($con, $booking_query);
            mysqli_stmt_bind_param($booking_stmt, "i", $booking_id);
            mysqli_stmt_execute($booking_stmt);
            mysqli_stmt_bind_result($booking_stmt, $user_name, $user_email, $service, $date, $time);
            mysqli_stmt_fetch($booking_stmt);
            mysqli_stmt_close($booking_stmt);
        
            $format_time = htmlspecialchars(formatTime($time), ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8');
            $format_service = htmlspecialchars($service, ENT_QUOTES, 'UTF-8');
            $date = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');

            user_refund_notification($name,$email,$format_service,$date,$format_time,$cancel_reason);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: https://xclusiveautospa.site/xclusive/webpages/view-reservations.php');
        exit(0);
    }
}


// Reschedule Booking From Waitlist, Will Send an Email Notification
function cancel_notification($name,$email,$service,$date,$time,$reason)
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
    $mail->Subject ="Booking Cancellation - Xclusive - Auto Spa";

    $email_template="<p>Dear <?php echo $name; ?>,</p>

    <p>We hope this message finds you well. We regret to inform you that your scheduled booking with us has been canceled. It appears that you have initiated this cancellation.</p>
    
    <p><strong>Booking Details:</strong></p>
    <ul>
        <li>Service: <?php echo $service; ?></li>
        <li>Date: <?php echo $date; ?></li>
        <li>Time: <?php echo $time; ?></li>
    </ul>
    
    <p><strong>Reason for Cancellation:</strong> <?php echo $reason; ?></p>
    
    <p>We understand that circumstances may change, and we respect your decision to cancel the booking. If you have any concerns or would like to reschedule, please contact our customer support at our website. We'll be happy to assist you in finding a suitable alternative.</p>
    
    <p>Once again, we appreciate your understanding, and we look forward to the opportunity to serve you in the future.</p>
    
    <p>Best regards,<br>Xclusive - Auto Spa</p>";

    $mail->Body = $email_template;
    $mail->send();
    header('Location: https://xclusiveautospa.site/webpages/view-reservations.php');
    exit(0);
    
}

if (isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];
    $cancel_reason = htmlspecialchars($_POST['cancel_reason'], ENT_QUOTES, 'UTF-8');
    $cancelled = 'Cancelled';

    // Check the status of the booking
    $status_query = "SELECT status FROM bookings WHERE booking_id=?";
    $status_stmt = mysqli_prepare($con, $status_query);
    mysqli_stmt_bind_param($status_stmt, "i", $booking_id);
    mysqli_stmt_execute($status_stmt);
    mysqli_stmt_bind_result($status_stmt, $booking_status);
    mysqli_stmt_fetch($status_stmt);
    mysqli_stmt_close($status_stmt);

    if ($booking_status == 'Confirmed') {
        // Update the booking status
        $update_query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $update_stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $cancelled, $booking_id);
        $update_query_run = mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        if ($update_query_run) {
            // Insert into cancelled_bookings
            $insert_query = "INSERT INTO cancelled_bookings (booking_id, reason) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "is", $booking_id, $cancel_reason);
            $insert_query_run = mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            if ($insert_query_run) {
                // Retrieve booking details for notification
                $booking_query = "SELECT users.name, users.email, bookings.service, bookings.reserve_date, bookings.reserve_time FROM users INNER JOIN bookings ON users.id = bookings.user_id WHERE bookings.booking_id = ?";
                $booking_stmt = mysqli_prepare($con, $booking_query);
                mysqli_stmt_bind_param($booking_stmt, "i", $booking_id);
                mysqli_stmt_execute($booking_stmt);
                mysqli_stmt_bind_result($booking_stmt, $user_name, $user_email, $service, $date, $time);
                mysqli_stmt_fetch($booking_stmt);
                mysqli_stmt_close($booking_stmt);

                $format_time = htmlspecialchars(formatTime($time), ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
                $email = htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8');
                $format_service = htmlspecialchars($service, ENT_QUOTES, 'UTF-8');
                $date = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');

                cancel_notification($name, $email, $format_service, $date, $format_time, $cancel_reason);
            }
        } else {
            $_SESSION['message'] = "Something went wrong";
            header('Location: https://xclusiveautospa.site/webpages/view-reservations.php');
            exit(0);
        }
    } else {
        $declined = 'Declined';
        $update_query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $update_stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $declined, $booking_id);
        $update_query_run = mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        if ($update_query_run) {
            $insert_query = "INSERT INTO declined_bookings (booking_id, reason) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "is", $booking_id, $cancel_reason);
            $insert_query_run = mysqli_stmt_execute($insert_stmt);

            header('Location: https://xclusiveautospa.site/webpages/view-reservations.php');
            exit(0);
        }

    }
}
?>