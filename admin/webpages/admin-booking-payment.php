<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Payment</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item">Payment</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Cash Payment</h4>
            </div>
            <div class="card-body">
                <?php
                    if(isset($_GET['id']))
                    {
                        $booking_id = $_GET['id'];
                        $users = "SELECT * FROM bookings WHERE booking_id='$booking_id' ";
                        $users_run = mysqli_query($con,$users);

                        if(mysqli_num_rows($users_run) > 0)
                        {
                            foreach($users_run as $user)
                            {
                            ?>
                            
                            <form action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                <input type="hidden" name="booking_id" value="<?=$user['booking_id'];?>">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $user['name']; ?>" class="form-control" readonly>
                                    </div>

                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="">Email Address</label>
                                        <input type="text" name="email" value="<?= $user['email']; ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Type</label>
                                        <select name="cartype" class="form-control" readonly>
                                            <option value="<?= $user['car_type']; ?>" selected><?= $user['car_type']; ?></option>
                                            <option value="Motorcycle">Motorcycle</option>
                                            <option value="Extra Small">Extra Small</option>
                                            <option value="Small">Small</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Large">Large</option>
                                            <option value="Extra Large">Extra Large</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Service</label>
                                        <select name="service" class="form-control" readonly>
                                            <option value="<?= $user['service']; ?>" selected><?= $user['service']; ?></option>
                                            <option>Carwash</option>
                                            <option>Carwash + Engine Wash</option>
                                            <option>Carwash + Acid Rain Removal</option>
                                            <option>Carwash + Asphalt Removal</option>
                                            <option>Carwash + Dashboard & Siding Polishing</option>
                                            <option>Carwash + Back To Zero/Sterilize</option>
                                            <option>Quickie Detail Wax</option>
                                            <option>HandJob Wax</option>
                                            <option>Machine Job Wax</option>
                                            <option>Glass Detailing</option>
                                            <option>Interior Detailing</option>
                                            <option>Exterior Detailing</option>
                                            <option>Headlight Restoration</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                      
                                        <select name="slot" class="form-control" hidden>
                                            <option value="0>" selected ="0"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Payment Status</label>
                                        <select name="payment" class="form-control">
                                            <option value="" selected></option>
                                            <option>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Reserve Date</label>
                                        <input type="date" name="rdate" value="<?= $user['reserve_date']; ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Reserve Time</label>
                                        <input type="text" name="rtime" value="<?= $user['reserve_time']; ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Price</label>
                                        <input type="text" name="price" value="<?= $user['price']; ?>" class="form-control" readonly>
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="payment_booking" class="btn btn-primary">Confirm Booking</button>
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