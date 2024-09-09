<?php
session_start();
include('../../includes/dbcon.php');//since no authentication yet

if (isset($_POST['user_delete'])) {
    $user_id = $_POST['user_delete'];

    $query = "DELETE FROM users WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Account Deleted Successfully";
        header('Location: ../../webpages/admin-view-users.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../../webpages/admin-view-users.php');
        exit(0);
    }
}

if (isset($_POST['employee_delete'])) {
    $user_id = $_POST['employee_delete'];

    $query = "DELETE FROM employees WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Account Deleted Successfully";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
        exit(0);
    }
}

if (isset($_POST['add_user'])) {
    $name = $_POST['fname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];

    $query = "INSERT INTO users (name, email, password, role_as) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $password, $role_as);
    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Account Added Successfully";
        header('Location: ../../webpages/view-users.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../../webpages/view-users.php');
        exit(0);
    }
}

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];

    $query = "UPDATE users SET name=?, email=?, password=?, role_as=? WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $password, $role_as, $user_id);
    $query_run = mysqli_stmt_execute($stmt);

    if ($query_run) {
        $_SESSION['message'] = "Updated Successfully";
        header('Location: ../../webpages/view-users.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: ../../webpages/view-users.php');
        exit(0);
    }
}


/// EMPLOYEES
if(isset($_POST['add_employee']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $specialty = $_POST['specialty'];

    // File Upload
    if (!empty($_FILES['employee_picture']['name'])) {
        $img_name = $_FILES['employee_picture']['name'];
        $tmp_name = $_FILES['employee_picture']['tmp_name'];
        $error = $_FILES['employee_picture']['error'];

        if ($error === 0) {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_to_lc = strtolower($img_ex);

            $allowed_exs = array('jpg', 'jpeg', 'png');

            if (in_array($img_ex_to_lc, $allowed_exs)) {
                // Generate a unique name for the image
                $new_img_name = uniqid($name, true) . '.' . $img_ex_to_lc;
                $img_upload_path = '../../img/uploads/' . $new_img_name;

                // Move the uploaded file to the destination
                move_uploaded_file($tmp_name, $img_upload_path);

                // Insert into Database with the image path using prepared statement
                $query = "INSERT INTO employees (name, email, contact, specialty, picture_path) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $query);

                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $contact, $specialty, $img_upload_path);

                $result = mysqli_stmt_execute($stmt);

                if($result) {
                    $_SESSION['message'] = "Employee Added Successfully";
                    header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
                    exit(0);
                } else {
                    $_SESSION['message'] = "Something Went Wrong";
                    header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
                    exit(0);
                }

                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['message'] = "Invalid file format. Only JPG, JPEG, and PNG are allowed.";
                header('Location: https://xclusiveautospa.site/xclusive/admin/webpages/admin-view-employees.php');
                exit(0);
            }
        } else {
            $_SESSION['message'] = "File upload error.";
            header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Please select a profile picture.";
        header('Location: https://xclusiveautospa.site/admin/webpages/admin-view-employees.php');
        exit(0);
    }
}

?>