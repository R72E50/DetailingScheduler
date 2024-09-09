<?php
Session_start();
include('google-config.php');

// Check if the user is trying to log in with Google
if (isset($_GET["code"])) {
    try {
        // Handle Google authentication
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

        if (!isset($token['error'])) {
            // Set Google access token in the session
            $google_client->setAccessToken($token['access_token']);
            $_SESSION['access_token'] = $token['access_token'];

            // Get user data from Google
            $google_service = new Google_Service_Oauth2($google_client);
            $google_user_data = $google_service->userinfo->get();

            // Check if the Google email is already in the database
            $email = $google_user_data->getEmail();
            $google_id = $google_user_data->getId();
            $name = $google_user_data->getName();
            $picture = $google_user_data->getPicture();
            // Use prepared statements to secure SQL queries
            $query = "SELECT * FROM users WHERE  email = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "s",$email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            //SELECT LOCALLY CREATED USER 
            $email_query = "SELECT * FROM users WHERE  email = ? AND  authentication_source = 'local'";
            $stmt1 = mysqli_prepare($con, $email_query);
            mysqli_stmt_bind_param($stmt1, "s",$email);
            mysqli_stmt_execute($stmt1);
            $result1 = mysqli_stmt_get_result($stmt1);

            function generateUniqueID($con) {
                do {
                    $newID = floor(rand());
            
                    $stmtUsers = $con->prepare("SELECT id FROM users WHERE id = ?");
                    $stmtUsers->bind_param("i", $newID);
                    $stmtUsers->execute();
                    $stmtUsers->store_result();
                   
                   
                    
                } while ($stmtUsers->num_rows > 0);
                $stmtUsers->close();
                return $newID;
            }
            //CHECK IF THE USER HAS RECORD ON DB IF NOT THEN INSERT
            if (mysqli_num_rows($result) == 0) {
                $uniqueID = generateUniqueID($con);
                $insert_query = "INSERT INTO users (id,google_id, name, email,profile_picture,authentication_source) VALUES (?, ?, ?,?,?,'google')";
                $insert_stmt = mysqli_prepare($con, $insert_query);
                mysqli_stmt_bind_param($insert_stmt, "issss", $uniqueID,$google_id, $name, $email,$picture);
                mysqli_stmt_execute($insert_stmt);
                // Store Google user data in session variables
                $_SESSION['auth'] = true;
                $_SESSION['profile_picture'] = $picture;
                
                $_SESSION['user_id'] = $uniqueID;
                $_SESSION['auth_user'] = [
                        'user_id' => $uniqueID,
                        'user_name' => $name,
                        'user_email' => $email,
                        'profile_picture' => $picture,
                    ];
                // Redirect to another page after Google login
                header('Location: https://xclusiveautospa.site/index.php');
                exit;
            }else if(mysqli_num_rows($result1) == 1){
                $_SESSION['message'] = "Email is already registered to an account";
                header('Location: https://xclusiveautospa.site/webpages/login.php');
                exit;
            }else{
                //IF THE USER HAS RECORD, SELECT SESSION VALUES FROM DB
                $sql = "SELECT id, name, email, profile_picture FROM users WHERE email = ?";
                $stmt = $con->prepare($sql);

                // Bind parameters
                $stmt->bind_param("s", $email);

                // Execute the statement
                $stmt->execute();

                // Get result
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Fetch the data from the result set
                    $row = $result->fetch_assoc();

                    // Assign values to $_SESSION array
                    $_SESSION['auth'] = true;
                    $_SESSION['profile_picture'] = $row['profile_picture'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['auth_user'] = [
                        'user_id' => $row['id'],
                        'user_name' => $row['name'],
                        'user_email' => $row['email'],
                        'profile_picture' => $row['profile_picture'],
                    ];

                    // Close the statement
                    $stmt->close();
                }
                // Redirect to another page after Google login
                header('Location: https://xclusiveautospa.site/index.php');
                exit;
            }

            
        }
    } catch (Exception $e) {
        // Handle Guzzle error
        echo 'Caught exception: ', $e->getMessage(), "\n";
        exit;
    }
}

// If the user is not logged in with Google, display an error or redirect to an error page
echo 'Error: Google Not Logged In';
?>
