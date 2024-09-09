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
    <h1 class="mt-4">Payment Gateways</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Refund</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Refund Requests
                    <a href="https://xclusiveautospa.site/admin-booking-add.php" class="btn btn-primary float-end">Book Manually</a>
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table id="view-paypal" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Price</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM bookings WHERE status = 'Refund' AND (payment_method ='Paypal' OR payment_method ='Nextpay')";
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
                                    <td>₱<?= $row['price']; ?></td>
                                    <td><?= $row['payment_method']; ?> </td>
                                    <td><?= $row['status']; ?></td>
                                    <td>
                                        <form action="https://xclusiveautospa.site/assets/php/paypal-refund.php" method="POST">
                                            <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                            <button class="btn btn-warning" type="submit" name="submit"><i class="fas fa-undo"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="https://xclusiveautospa.site/assets/php/booking-code.php" method="POST">
                                        <button type="submit" name="booking_delete" value="<?=$row['booking_id']; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button> 
                                        </form>
                                    </td>
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
            $('#view-paypal').DataTable({
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