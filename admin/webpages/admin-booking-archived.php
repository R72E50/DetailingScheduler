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
<head>
    <link rel="stylesheet" href="https://xclusiveautospa.site/assets/css/admin-modal.css">
    <!-- Tables Css -->
    <link rel="stylesheet" type="text/css" href="https://xclusiveautospa.site/assets/css/tables.css">
</head>
<?php include('../../includes/dbcon.php'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Archived Reservations </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Bookings</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Cancelled Reservations
                </h4>
            </div>
            <div class="card-body" style="overflow-x:auto">
                <table id="view-assigned-bookings" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Requested Date</th>
                            <th>Reason</th>
                            <th>Timestamp</th>
                            <th></th>
                            <th></th>
                           
                        </tr>   
                    </thead>
                    <tbody>
                        <?php
                        $cancelled = 'Cancelled';
                        $query="SELECT bookings.booking_id AS ID, users.Name AS Name, bookings.service AS Service, bookings.reserve_date AS Requested_Date, cancelled_bookings.reason AS Reason, cancelled_bookings.timestamp AS Timestamp FROM bookings JOIN users ON bookings.user_id = users.id LEFT JOIN cancelled_bookings ON bookings.booking_id = cancelled_bookings.booking_id WHERE bookings.status = ?";
                        if ($stmt = mysqli_prepare($con, $query)) {
                            mysqli_stmt_bind_param($stmt, "s", $cancelled);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

            
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $row['ID']; ?> </td>
                                    <td><?= $row['Name']; ?> </td>
                                    <td><?= $row['Service']; ?> </td>
                                    <td><?= $row['Requested_Date']; ?><br>
                                    <td><?= $row['Reason']; ?><br>
                                    <td><?= $row['Timestamp']; ?> </td>
                                    
                                    
                                    <td><button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#receiptModal<?= $row['ID']; ?>"><i class="fas fa-eye"></i></button></td>
                                  
                            
                                    <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $row['ID']; ?>"><i class="fas fa-trash-alt"></i></button></td>
                                    <!-- MODALS -->
                                    <!-- Confirmation Modal -->
                                    <div class="modal " id="confirmModal<?= $row['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
                                                        <button type="submit" name="booking_delete" value="<?=$row['ID']; ?>" id="delete_btn" class="btn btn-danger">Delete</button> 
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" id="close-btn" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- View Modal --> 
                                    <div class="modal " id="receiptModal<?= $row['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body" >
                                            <div class="receipt-content">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6" style="padding-bottom: 40px">
                                                            <div class="invoice-wrapper" style="padding-bottom: 500px">
                                                                <div class="intro">
                                                                    Hi <strong><?= $row['Name']?></strong>, 
                                                                    <br>
                                                                    This is the receipt for a payment of <strong><?= $row['price'] ?></strong> (PHP) for your works
                                                                </div>

                                                                <div class="payment-info" style="border-top: 2px solid #EBECEE;">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <span>Payment No.</span>
                                                                            <strong><?= $row['ID'] ?></strong>
                                                                        </div>
                                                                        <div class="col-sm-6 text-right">
                                                                            <span>Payment Date</span>
                                                                            <strong><?php echo date("D d, M y"); ?></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="line-items">
                                                                    <div class="headers clearfix">
                                                                        <div style="display: flex; gap: 25px">
                                                                            <div class="col-xs-4">Car Type</div>
                                                                            <div class="col-xs-3">Car Sevice</div>
                                                                            <div class="col-xs-3">Payment Method</div>
                                                                            <div style="text-align: right">Amount</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="items">
                                                                        <div style="display: flex; gap: 15px; font-size: 13px; letter-spacing: 2px;">
                                                                            <div class="col-xs-4 desc">
                                                                                <?= $row['car_type'] ?>
                                                                            </div>
                                                                            <div class="col-xs-3 qty">
                                                                                <?= $row['service'] ?>
                                                                            </div>
                                                                            <div class="col-xs-3 qty">
                                                                                <?= $row['payment_method'] ?>
                                                                            </div>
                                                                            <div style="text-align: left;">
                                                                                <?= $row['price'] ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="total text-left" style="float: right; margin-right: -30px">                                                        
                                                                            <div class="field">
                                                                                Subtotal: <span><?= $row['price'] ?>
                                                                            </div>
                                                                            <div class="field grand-total">
                                                                                Total: <span><?= $row['price'] ?>
                                                                            </div> 
                                                                    </div>

                                                                    <div class="print"  style="margin: 150px 0 0 20px">
                                                                        <a href="https://xclusiveautospa.site/webpages/print.php?id=<?= $row['booking_id'] ?>">
                                                                            <i class="fa fa-print"></i>
                                                                            Print this receipt
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
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
                                    <td></td>
                                  
                                </tr>   
                          <?php
                        }
                            mysqli_stmt_close($stmt);
                        } else {
                            echo "Error preparing statement: " . mysqli_error($con);
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
            $('#view-assigned-bookings').DataTable({
                lengthChange: false, // Disable the "Show entries" dropdown
                info: false,    
                paging: true,      // Enable pagination
                pageLength: 3,    // Set the number of items per page
                searching: true,    // Enable search functionality
                responsive: true,
                columnDefs: [
                    { targets: [5,6,7], orderable: false }
                ]
            });
        });
        // Notify Button
        function notifyBooking(bookingId) {
            // Use AJAX or fetch to send a request to your PHP script
            // Example using fetch:
            fetch('admin-notify.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notify: true, id: bookingId }), // Include the booking_id in the request
            })
            .then(response => response.json())
            .then(data => {
                console.log('Notification sent:', data);
                alert('Notification sent successfully!');
            })
            .catch(error => {
                console.error('Error sending notification:', error);
                alert('Error sending notification. Please try again later.');
            });
        }
    </script>
<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>