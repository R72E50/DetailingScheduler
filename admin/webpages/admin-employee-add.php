<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Carwash Employee Creation</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Employees</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Add Employee</h4>
            </div>
            <div class="card-body">

            <form action="https://xclusiveautospa.site/assets/php/admin-code.php" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Email Address</label>
                        <input type="text" name="email"  class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Contact</label>
                        <input type="tel" name="contact"  class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Specialty</label>
                        <input type="text" name="specialty" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Upload Picture</label>
                        <input type="file" name="employee_picture" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
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
