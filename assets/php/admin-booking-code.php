<?php
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
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location:https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
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
    $pmethod = 'Cash';

    
    $query = "INSERT INTO bookings (user_id, name, email, car_type, service, reserve_date, reserve_time, price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "isssssdss", $user_id, $name, $email, $car_type, $services, $reserve_date, $reserve_time, $price, $pmethod);
    mysqli_stmt_execute($stmt);

    
    if(mysqli_stmt_affected_rows($stmt))
    {
        $_SESSION['message'] = "Booking Added Successfully";
        header("Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-booking.php');
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
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
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
        header('Location: https://xclusiveautospa.site/admin/admin.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-reservations.php');
        exit(0);
    }
}



// Confirms Pending Booking, Will Send an Email Notification
function confirm_notification($name,$email,$service,$date,$time)
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
        
        $email_template = "<h2>Xclusive Auto Spa Booking Confirmation</h2> <p>Dear " . $name . ",</p><p>Your Booking Reservation has been confirmed. Thank you for choosing our carwash & auto detailing service. We are excited to confirm your reservation details:</p> <ul> <li><strong>Name:</strong>" . $name . "</li> <li><strong>Service:</strong> " . $service . "</li> <li><strong>Date:</strong> " . $date . "</li> <li><strong>Time:</strong> " . $time . "</li> </ul> <p>Your vehicle is scheduled for a thorough detailing, and we look forward to providing you with excellent service on the day of reservation.</p> <p>If you have any questions or need to make changes to your reservation, please feel free to contact us at our website.</p> <p>Thank you again for choosing our auto detailing service. We can't wait to make your vehicle shine!</p> <p>Best regards,<br>Xclusive Auto Spa</p>";


        $mail->Body = $email_template;
        $mail->send();
        $_SESSION['message'] = "Booking Details Updated Successfully";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
        
    }
    
    // Declines Pending Booking, Will Send an Email Notification
    function waitlist_notification($name,$email,$service,$date,$time)
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
            $mail->Subject ="You've Been Added to the Waitlist for Xclusive - Auto Spa";
            $email_template = 
            "<p>Dear ".$name. ",</p>
            
            <p>We hope this message finds you well. Due to high demand for Xclusive - Auto Spa services, our available slots are currently fully booked.</p><p>However, we understand how important it is for you to secure a spot, and we're thrilled to inform you that you've been added to our waitlist.</p>
            
            <p>Here are the details of your waitlist request:</p>
            <ul>
            <li>Name: ".$name."</li>
            <li>Service: ".$service." </li>
            <li>Preferred Date: ".$date."</li>
            <li>Preferred Time: ".$time."</li>
            </ul>
            
            <p>As soon as a slot becomes available, we will notify you immediately. Please be assured that we are actively managing our schedule and doing our best to accommodate your request.</p>
            
            <p>If you have any questions or need further assistance, feel free to reach out to our customer support at our website.
            
            Thank you for your patience and understanding. We look forward to serving you soon.</p>
            
            <p>Best regards,
            Xclusive - Auto Spa</p>";

            $mail->Body = $email_template;
            $mail->send();
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-booking-waitlist.php');
            exit(0);
            
        }
    if (isset($_POST['waitlist_booking'])) {
        $booking_id = $_POST['booking_id'];
        $Waitlisted = 'Waitlisted';
        

        $query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $stmt = mysqli_prepare($con, $query);
        
        mysqli_stmt_bind_param($stmt, "si", $Waitlisted, $booking_id);
        
        $query_run = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($query_run) {
            // Insert into the declined_bookings table
            $insert_query = "INSERT INTO bookings_waitlist (booking_id) VALUES ( ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "i", $booking_id);
            $insert_query_run = mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            if($insert_query_run){
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

                waitlist_notification($name,$email,$format_service,$date,$format_time);
                
                
                
                
            }
        } else {
            $_SESSION['message'] = "Something went wrong";
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
            exit(0);
        }
    }

    // Reschedule Booking From Waitlist, Will Send an Email Notification
    function reschedule_notification($name,$email,$service,$date,$time)
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
            $mail->Subject ="You're Booking has been rescheduled- Xclusive - Auto Spa";

            $email_template="<p>Dear ".$name.",</p>

            <p>We hope this message finds you well. Thank you for your patience as you waited on our Xclusive - Auto Spa waitlist.</p>
            
            <p>We're delighted to inform you that a spot has become available, and we have successfully rescheduled your appointment. The details of your new appointment are as follows:</p>
            <ul>
                <li>New Date: ".$date."</li>
                <li>New Time: ".$time."</li>
                <li>Service: ".$service."</li>
            </ul>
            
            <p>We understand that unexpected changes can be challenging, and we sincerely appreciate your understanding and flexibility in this matter.</p>
            
            <p>If you have any questions or concerns, please feel free to reach out to our customer support at our website.</p>
            
            <p>Thank you once again for choosing Xclusive - Auto Spa. We look forward to providing you with an exceptional auto detailing experience.</p>
            
            <p>Best regards,<br>Xclusive - Auto Spa</p>
            ";

            $mail->Body = $email_template;
            $mail->send();
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-booking-waitlist.php');
            exit(0);
            
        }
    if (isset($_POST['reschedule_booking'])) {
        $booking_id = $_POST['booking_id'];
        
        $confirmed= 'Confirmed';
        

        $query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $stmt = mysqli_prepare($con, $query);
        
        mysqli_stmt_bind_param($stmt, "si", $confirmed, $booking_id);
        
        $query_run = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($query_run) {
            $delete_query = "DELETE FROM bookings_waitlist WHERE booking_id = ?";
            $delete_stmt = mysqli_prepare($con, $delete_query);

            if ($delete_stmt) {
            mysqli_stmt_bind_param($delete_stmt, "i", $booking_id);
            $delete_query_run = mysqli_stmt_execute($delete_stmt);

                if($delete_query_run){
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

                    reschedule_notification($name,$email,$format_service,$date,$format_time);
                }
            }
        } else {
            $_SESSION['message'] = "Something went wrong";
            header('Location:https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
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

            $mail->Host       = 'smtp.office365.com';                   //Set the SMTP server to send through
            $mail->Username   = 'Sample_Server00@outlook.com';          //SMTP username
            $mail->Password   = 'sampleserver000';                      //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption 
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->setFrom("Sample_Server00@outlook.com",$name);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject ="Booking Cancellation - Xclusive - Auto Spa";

            $email_template="<p>Dear ".$name.",</p>


            <p>We hope this message finds you well. We regret to inform you that, due to unforeseen circumstances, we must cancel your scheduled booking with us.</p>

            <p>Booking Details:</p>
            <ul>
                <li>Service:".$service." </li>
                <li>Date: ".$date."</li>
                <li>Time: ".$time."</li>
            </ul>

            <p>Reason for Cancellation: ".$reason."</p>

            <p>We understand the inconvenience this may cause and sincerely apologize for any disruption to your plans. Our team is committed to providing you with the best service, and we appreciate your understanding in this matter.</p>

            <p>If you have any concerns or would like to reschedule, please contact our customer support at our website. We'll be happy to assist you in finding a suitable alternative.</p>

            <p>Once again, we apologize for any inconvenience, and we look forward to the opportunity to serve you in the future.</p>

            <p>Best regards,<br>Xclusive - Auto Spa</p>
            ";

            $mail->Body = $email_template;
            $mail->send();
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-booking-archived.php');
            exit(0);
            
        }
    if (isset($_POST['cancelled_booking'])) {
        $booking_id = $_POST['booking_id'];
        $decline_reason = htmlspecialchars($_POST['cancel_reason'], ENT_QUOTES, 'UTF-8');
        $cancelled= 'Cancelled';
        

        $query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $stmt = mysqli_prepare($con, $query);
        
        mysqli_stmt_bind_param($stmt, "si", $cancelled, $booking_id);
        
        $query_run = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($query_run) {
           
            $insert_query = "INSERT INTO cancelled_bookings (booking_id, reason) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "is", $booking_id, $decline_reason);
            $insert_query_run = mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            if($insert_query_run){
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

                cancel_notification($name,$email,$format_service,$date,$format_time,$decline_reason);
            }
        } else {
            $_SESSION['message'] = "Something went wrong";
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
            exit(0);
        }
    }


if (isset($_POST['confirm_booking'])) {
    $booking_id = $_POST['booking_id'];
    $slot = $_POST['slot'];
    $employee = $_POST['employee'];
    $status = 'Confirmed';

    $query = "UPDATE bookings SET slot=?, employee_assigned=?, status=? WHERE booking_id=?";
    $stmt = mysqli_prepare($con, $query);
    
    mysqli_stmt_bind_param($stmt, "sssi", $slot,$employee,$status,$booking_id);
    
    $query_run = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($query_run) {
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
                    confirm_notification($name,$email,$service,$date,$time);
            }

        
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
    }
}

if (isset($_POST['payment_booking'])) {
    $booking_id = $_POST['booking_id'];
    $payment = $_POST['payment'];
    $status = 'Completed';

    $query = "UPDATE bookings SET payment_status = IF('$payment' = 'Paid', 1, payment_status), status = ? WHERE booking_id = ?";
    
    $stmt = mysqli_prepare($con, $query);
    
    mysqli_stmt_bind_param($stmt, "si", $status, $booking_id);
    
    $query_run = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Booking Details Updated Successfully";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
        exit(0);
    }
}




    // Declines Pending Booking, Will Send an Email Notification
    function decline_notification($name,$email,$service,$date,$time,$reason)
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
            $mail->Subject ="Booking Notification From Xclusive Auto Spa";
            $email_template = "<h2>Xclusive Auto Spa Booking Declined</h2><p>Dear $name,</p><p>I hope this message finds you well. We appreciate your interest in our services and the time you've taken to submit your booking request. After careful consideration, we regret to inform you that, unfortunately, we are unable to fulfill your following booking request.</p><ul><li><strong>Name:</strong>$name</li><li><strong>Service:</strong>$service</li><li><strong>Date:</strong>$date</li><li><strong>Time:</strong>$time</li></ul><p>We understand that this may be disappointing, and we sincerely apologize for any inconvenience this may cause. Our decision is based on $reason.Please be assured that we value your patronage. If you have any concerns or would like further clarification, please do not hesitate to reach out to our customer support team at our website.</p><p>Your vehicle is scheduled for a thorough detailing, and we look forward to providing you with excellent service on the day of reservation.</p><p>Once again, we appreciate your understanding and apologize for any inconvenience. We look forward to the opportunity to serve you in the future.</p><p>Thank you for considering Xclusive - Auto Spa.</p><p>Best regards,<br>Xclusive Auto Spa";
            $mail->Body = $email_template;
            $mail->send();
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
            exit(0);
            
        }
    if (isset($_POST['decline_booking'])) {
        $booking_id = $_POST['booking_id'];
        $decline_reason = htmlspecialchars($_POST['decline_reason'], ENT_QUOTES, 'UTF-8');
        $declined = 'Declined';
        

        $query = "UPDATE bookings SET status = ? WHERE booking_id=?";
        $stmt = mysqli_prepare($con, $query);
        
        mysqli_stmt_bind_param($stmt, "si", $declined, $booking_id);
        
        $query_run = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        if ($query_run) {
            // Insert into the declined_bookings table
            $insert_query = "INSERT INTO declined_bookings (booking_id, reason_for_decline) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "is", $booking_id, $decline_reason);
            $insert_query_run = mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            if($insert_query_run){
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

                decline_notification($name,$email,$format_service,$date,$format_time,$decline_reason);
                
                
                
                
            }
        } else {
            $_SESSION['message'] = "Something went wrong";
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-booking.php');
            exit(0);
        }
    }







?>