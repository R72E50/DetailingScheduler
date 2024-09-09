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

<div class="container-fluid px-4">
    <h1 class="mt-4">Reservations</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Booking</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Pending Service Reservations
                    <a href="https://xclusiveautospa.site/admin/webpages/admin-booking-add.php" class="btn btn-primary float-end">Book Manually</a>
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table  id="view-booking" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Price</th>
                            <th>Reserve Date</th>
                            <th>Reserve Time</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM bookings WHERE status = 'Pending'";
                        $query_run = mysqli_query($con,$query);
                        
                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $row) 
                            {
                            ?>
                                <tr>
                                    <td><?= $row['booking_id']; ?> </td>
                                    <td><?= $row['name']; ?> </td>
                                    <td><?= $row['service']; ?> </td>
                                    <td>₱<?= $row['price']; ?></td>
                                    <td><?= $row['reserve_date']; ?> </td>
                                    <td><?= formatTime($row['reserve_time']); ?></td>
                                    <td><a href="https://xclusiveautospa.site/admin/webpages/admin-booking-edit.php?id=<?= $row['booking_id']; ?>" class="btn btn-success"> <i class="fas fa-check"></i></a></td>
                                    <td><button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#WaitlistModal<?= $row['booking_id']; ?>"><i class="fas fa-clock"></i></button></td>
                                    <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $row['booking_id']; ?>"><i class="fas fa-times"></i></button></td>
                                    
                                    <!-- Confirmation Modal -->
                                    <div class="modal" id="confirmModal<?= $row['booking_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
                                                   <p class="modal-text"> Are you sure you want to decline this booking?</p>
                                                </div>
                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="button" id="delete_btn" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reasonModal<?= $row['booking_id']; ?>">Decline</button> 
                                                    
                                                    <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Follow up Confirmation Modal -->
                                    <div class="modal" id="reasonModal<?= $row['booking_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reasonModalLabel">Reason for Decline</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <form id="reasonForm" action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                                        <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                                        <div class="form-group">
                                                            <label for="reasonDropdown">Select a reason:</label>
                                                            <select class="form-control" id="reasonDropdown" name="decline_reason">
                                                                <option value="Overbooking or Capacity Limitations">Overbooking/Capacity Limitations</option>
                                                                <option value="Unavailable Services or Staff">Unavailable Services or Staff</option>
                                                                <option value="Insufficient Booking Notice">Insufficient Booking Notice</option>
                                                                <option value="Service Policy Violations">Service Policy Violations</option>
                                                            </select>
                                                        </div>
                                                </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="decline_booking" class="btn btn-danger">Submit</button>
                                                            <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Waitlist Modal -->
                                    <div class="modal" id="WaitlistModal<?= $row['booking_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="WaitlistModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="WaitlistModalLabel">Waitlist Confirmation</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <form id="reasonForm" action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                                        <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                                        <p class="modal-text">This booking will be waitlisted, Continue?</p>
                                                </div>
                                                <div class="modal-footer">
                                                            <button type="submit" name="waitlist_booking" class="btn btn-success">Confirm</button>
                                                            <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                                    </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
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
            $('#view-booking').DataTable({
                paging: true,      // Enable pagination
                pageLength: 3,    // Set the number of items per page
                searching: true,    // Enable search functionality
                responsive: true,
                columnDefs: [
                    { targets: [5,6,7], orderable: false }
                ]
            });
        });
    </script>
<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>