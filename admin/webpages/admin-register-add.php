<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">User Account Creation</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Users</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Add User Account</h4>
            </div>
            <div class="card-body">

            <form action="https://xclusiveautospa.site/assets/php/admin-code.php" method="POST">
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">First Name</label>
                        <input type="text" name="fname" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Last Name</label>
                        <input type="text" name="lname"  class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Email Address</label>
                        <input type="text" name="email"  class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Password</label>
                        <input type="password" name="password"  class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Role as</label>
                        <select name="role_as" class="form-control">
                            <option value="">--Select Role--</option>
                            <option value="Admin">Admin</option>
                            <option value="User" >User</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
            </div>
           </div>
        </div>
    </div>


<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>