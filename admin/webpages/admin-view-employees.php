<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>
<!-- Tables Css -->
<link rel="stylesheet" type="text/css" href="https://xclusiveautospa.site/assets/css/tables.css">
<link rel="stylesheet" type="text/css" href="https://xclusiveautospa.site/assets/css/confirm-modal.css">
<style>
   .small-spacing {
        display: flex; /* Use flexbox to align items horizontally */
        justify-content: space-between; /* Add space between buttons */
        align-items: center;
    }
    .btn-right{
        margin:auto;
    }
    #profile-picture{
        width: 140px;
        height: 140px;
        border: 0px;
    }
    
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4">View Employees</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Employees</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Employee List
                    <a href="https://xclusiveautospa.site/admin-employee-add.php" class="btn btn-primary float-end">Add Employee</a>
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table id="view-employees"class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Specialty</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM employees";
                        $query_run = mysqli_query($con, $query);
                        
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $row) {
                                ?>
                                <tr>
                                    <td><?= $row['id']; ?> </td>
                                    <td><?= $row['name']; ?> </td>
                                    <td><?= $row['email']; ?> </td>
                                    <td><?= $row['contact']; ?> </td>
                                    <td><?= $row['Specialty']; ?> </td>
                                    <td><button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#profile<?= $row['id']; ?>"><i class="fas fa-eye"></i></button></td>
                                    <td class="small-spacing"><a href="https://xclusiveautospa.site/webpages/admin-employee-edit.php?id=<?= $row['id']; ?>" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a></td>
                                    <td><button type="button" class="btn  btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $row['id']; ?>"><i class="fas fa-trash-alt"></i></button></td>
                                    <!-- MODALS -->
                                    <!-- Confirmation Modal -->
                                    <div class="modal " id="confirmModal<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    Are you sure you want to delete this booking?
                                                </div>
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <form action="https://xclusiveautospa.site/assets/php/admin-code.php" method="POST">
                                                        <button type="submit" name="employee_delete" value="<?=$row['id']; ?>" id="delete_btn" class="btn btn-danger">Delete</button> 
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Profile Modal -->
                                    <div class="modal " id="profile<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="profileLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="profileLabel">Profile</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <!-- Modal Body -->
                                                <div class="modal-body">

                                                <img class="mb-3 rounded-pill shadow-sm mt-1" id="profile-picture" src="https://xclusiveautospa.site/img/uploads/<?=$row['picture_path']?>" alt="<?= $row['picture_path'] ?>">
                                                
                                                    <h6 class="mb-2"><?= $row['name'] ?></h6>
                                                    <p class="mb-1">+91 85680-79956</p>
                                                    <p><?= $row['email'] ?></p>
                                                
                                                    
                                                </div>
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <form action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                                        <button type="submit" name="employee_delete" value="<?=$row['id']; ?>" id="delete_btn" class="btn btn-danger">Delete</button> 
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="8">No Record Found</td>
                            
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
           </div>
        </div>
    </div>
    <script>
        //Data tables script
        $(document).ready(function () {
            $('#view-employees').DataTable({
                lengthChange:false,
                paging: true,      // Enable pagination
                info:false,
                pageLength: 3,    // Set the number of items per page
                searching: true,    // Enable search functionality
                responsive: true,
                columnDefs: [
                    { targets: [4,5], orderable: false }
                ],
                dom: 'lfrtip'
            });
        });
    </script>
<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>