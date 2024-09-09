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


<div class="container-fluid px-4">
    <h1 class="mt-4">Income</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Cash</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Cash Payments
                    <a href="https://xclusiveautospa.site/admin/admin-booking-add.php" class="btn btn-primary float-end">Book Manually</a>
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table id="view-cash-payments" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Price</th>
                            <th>Payment Method</th>
                            <th></th>
                            <th></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM bookings WHERE status = 'Confirmed' AND payment_method ='Cash' AND payment_status = 1";
                        $query_run = mysqli_query($con,$query);
                        
                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $row) 
                            {
                            ?>
                                <tr>
                                    <td><?= $row['booking_id']; ?> </td>
                                    <td><?= $row['name']; ?> </td>
                                    <td><?= $row['email']; ?> </td>
                                    <td><?= $row['price']; ?></td>
                                    <td><?= $row['payment_method']; ?> </td>
                                    
                                    <td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#receiptModal<?= $row['booking_id']; ?>"><i class="fas fa-eye"></i></button></td>
                                    <div class="modal fade" id="receiptModal<?= $row['booking_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="receipt.php">
                                            <!-- add the code for displaying the receipt here -->
                                            <div class="receipt">
                                        <h1>Receipt ID #<?= $row['booking_id'] ?></h1>
                                        
                                        <p><b>Date:</b> <?php echo date("D d, M y"); ?></p>
                                        <hr>
                                        <div class="item">
                                            <p><b>Name: </b><?= $row['name'] ?> ?></p>
                                        </div>
                                        <div class="item">
                                            <p><b>Car Service:</b> <?= $row['car_type'] ?> &nbsp-&nbsp <?= $row['service'] ?></p>
                                        </div>
                                        <div class="item">
                                            <p><b>Reservation Date: </b> <?= $row['reserve_date'] ?> &nbsp:&nbsp <?= $row['reserve_time'] ?></p>
                                        </div>
                                        <hr>
                                        <div class="item">
                                            <p>Payment:</p>
                                            <p><?= $row['payment_method'] ?> &nbsp-&nbsp <?= $row['price'] ?></p>
                                        </div>
                                        <hr>
                                        <div class="item">
                                            <p>Total:</p>
                                            <p><?= $row['payment_method'] ?> &nbsp-&nbsp <?= $row['price'] ?></p>
                                        </div>
                                        </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="https://xclusiveautospa.site/assets/php/print.php?id=<?= $row['booking_id'] ?>" class="btn btn-primary">Print</a>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $row['booking_id']; ?>"><i class="fas fa-trash-alt"></i></button></td>
                                    <!-- MODALS -->
                                    <!-- Confirmation Modal -->
                                    <div class="modal " id="confirmModal<?= $row['booking_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
                                                    <form action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                                        <button type="submit" name="booking_delete" value="<?=$row['booking_id']; ?>" id="delete_btn" class="btn btn-danger">Delete</button> 
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
            $('#view-cash-payments').DataTable({
                paging: true,      // Enable pagination
                pageLength: 3,    // Set the number of items per page
                searching: true,    // Enable search functionality
                responsive: true,
                columnDefs: [
                    { targets: [4,5,6], orderable: false }
                ]
            });
        });
    </script>
<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>