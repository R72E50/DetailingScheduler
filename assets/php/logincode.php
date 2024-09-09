<?php
//
session_start();

include('../../includes/dbcon.php');

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $userEnteredPassword = $_POST['password'];

    // Use a prepared statement to prevent SQL injection
    $login_query = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = mysqli_prepare($con, $login_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if ($result && $data = mysqli_fetch_assoc($result)) {
            $storedHashedPassword = $data['password'];

            $role_as = $data['role_as'];
            $_SESSION['auth_role'] = $role_as; // 1 = admin, 0 = user


            // Verify the user-entered password against the stored hashed password
            if (password_verify($userEnteredPassword, $storedHashedPassword)) {
                // Password is correct
                

                if ($_SESSION['auth_role'] == '1') {
                    $_SESSION['message'] = "Access Denied. You are an administrator.";
                    header("Location: ../../webpages/login.php");
                    exit(0);
                } elseif ($_SESSION['auth_role'] == '0') {
                    $user_id = $data['id'];
                    $user_name = $data['name'];
                    $user_email = $data['email'];
                    
                    $profile_picture = $data['profile_picture'];

                    $_SESSION['auth'] = true;
                    $_SESSION['profile_picture'] = $profile_picture;
                   
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['auth_user'] = [
                        'user_id' => $user_id,
                        'user_name' => $user_name,
                        'user_email' => $user_email,
                        'profile_picture' => $profile_picture,
                    ];
                    
                    $_SESSION['message'] = "You are Logged In";
                    header("Location: ../../index.php");
                    exit(0);
                }
            } else {
                // Password is incorrect
                $_SESSION['message'] = "Invalid Email or Password";
                header("Location: ../../webpages/login.php");
                exit(0);
            }
        } else {
            // User not found
            $_SESSION['message'] = "Invalid Email or Password";
            header("Location: ../../webpages/login.php");
            exit(0);
        }
    } else {
        // Handle the case where the prepared statement fails
        $_SESSION['message'] = "Something Went Wrong";
        header("Location: ../../webpages/login.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "You are not allowed to access this file";
    header("Location: ../../webpages/login.php");
    exit(0);
}
?>
