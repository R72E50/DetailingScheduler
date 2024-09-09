<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Registered User Account</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Users</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Edit Users</h4>
            </div>
            <div class="card-body">
                <?php
                    if(isset($_GET['id']))
                    {
                        $user_id = $_GET['id'];
                        $users = "SELECT * FROM users WHERE id='$user_id' ";
                        $users_run = mysqli_query($con,$users);

                        if(mysqli_num_rows($users_run) > 0)
                        {
                            foreach($users_run as $user)
                            {
                            ?>
                            
                            <form action="https://xclusiveautospa.site/assets/php/admin-code.php" method="POST">
                                <input type="hidden" name="user_id" value="<?=$user['id'];?>">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $user['name']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Email Address</label>
                                        <input type="text" name="email" value="<?= $user['email']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Role as</label>
                                        <select name="role_as" class="form-control">
                                            <option value="">--Select Role--</option>
                                            <option value="admin" <?= $user['role_as'] == '1' ? 'selected': ''?> >Admin</option>
                                            <option value="user" <?= $user['role_as'] == '0' ? 'selected': ''?> >User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                                </div>
                            </form>
                            <?php
                            }
                        }
                        else
                        {
                            ?>
                            <h4>No Record Found</h4>
                            <?php
                        }
                    }
                ?>
                    
                
            </div>
           </div>
        </div>
    </div>


<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>