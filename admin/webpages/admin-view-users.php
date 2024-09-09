<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
// Function to format time
function formatTime($timeStr) {
    $timeObj = DateTime::createFromFormat('H:i:s.u', $timeStr);
    return $timeObj->format('h:i A');
}
?>
<!-- Tables Css -->
<link rel="stylesheet" type="text/css" href="https://xclusiveautospa.site/assets/css/tables.css">
<link rel="stylesheet" type="text/css" href="https://xclusiveautospa.site/assets/css/confirm-modal.css">
<style>
    #profile-picture{
        width: 140px;
        height: 140px;
        border: 0px;
    }
</style>
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
                <h4>Registered Users
                    <a href="https://xclusiveautospa.site/admin-register-add.php" class="btn btn-primary float-end">Add Another User</a>
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table id="view-users" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $query_run = mysqli_query($con,$query);
                        
                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $row) 
                            {
                            ?>
                                <tr>
                                    <td><?= $row['id']; ?> </td>
                                    <td><?= $row['name']; ?> </td>
                                    <td><?= $row['email']; ?> </td>
                                    <td>
                                        <?php
                                            if($row['role_as'] == '1'){
                                                echo 'Admin';
                                            }elseif($row['role_as'] == '0'){
                                                echo 'User';
                                            }
                                        ?>
                                    </td>
                                    <td><button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#profile<?= $row['id']; ?>"><i class="fas fa-eye"></i></button></td>
                                    <td><a href="https://xclusiveautospa.site/admin-register-edit.php?id=<?= $row['id']; ?>" class="btn btn-success"> <i class="fas fa-pencil-alt"></i> </a></td>
                                    <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $row['id']; ?>"><i class="fas fa-trash-alt"></i></button></td>
                                    <!-- MODALS -->
                                    <!-- Confirmation Modal -->
                                    <div class="modal" id="confirmModal<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                                                    <button type="button" class="close" id="dismiss" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    Are you sure you want to delete this booking?
                                                </div>
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <form action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                                        <button type="submit" name="booking_delete" value="<?=$row['id']; ?>" id="delete-btn" class="btn btn-danger">Delete</button> 
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

                                                      <?php      if ($row['authentication_source'] == 'google') {
                                                        // Display Google profile picture
                                                        echo '<img class="mb-3 rounded-pill shadow-sm mt-1" src="' . $row['profile_picture'] . '" alt="' . $row['profile_picture'] . '">';
                                                    } else {
                                                        // Display a default profile picture for other authentication sources
                                                        echo '<img class="mb-3 rounded-pill shadow-sm mt-1" src="https://xclusiveautospa.site/img/uploads'. $row['profile_picture'] .'" alt="Default Profile Picture">';
                                                    } ?>
                                                
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
                        }
                        else
                        {
                          ?>
                                <tr>
                                    <td>No Record Found</td>
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
            $('#view-users').DataTable({
                lengthChange: false, // Disable the "Show entries" dropdown
                info: false,    
                paging: true,      // Enable pagination
                pageLength: 3,    // Set the number of items per page
                searching: true,    // Enable search functionality
                responsive: true,
                columnDefs: [
                    { targets: [3,4,5,6], orderable: false }
                ]
            });
        });
    </script>
<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>