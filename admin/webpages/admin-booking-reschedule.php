<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
function formatTime($timeStr) {
    $timeObj = DateTime::createFromFormat('H:i:s.u', $timeStr);
    return $timeObj->format('h:i A');
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Reschedule Booking</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Bookings</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Choose New Date & Time</h4>
            </div>
            <div class="card-body">
                 <?php
                    if(isset($_GET['id'])) {
                        $booking_id = $_GET['id'];
                        $users_query = "SELECT * FROM bookings WHERE booking_id=?";
                        $stmt_users = mysqli_prepare($con, $users_query);
                        mysqli_stmt_bind_param($stmt_users, "i", $booking_id);
                        mysqli_stmt_execute($stmt_users);
                        $users_result = mysqli_stmt_get_result($stmt_users);

                        if(mysqli_num_rows($users_result) > 0) {
                            $user = mysqli_fetch_assoc($users_result);

                            // Fetch employee names from the employee database
                            $employees_query = "SELECT id, name FROM employees";
                            $stmt_employees = mysqli_prepare($con, $employees_query);
                            mysqli_stmt_execute($stmt_employees);
                            $employees_result = mysqli_stmt_get_result($stmt_employees);
                            ?>

                            
                            <form action="https://xclusiveautospa.site/assets/php/admin-booking-code.php" method="POST">
                                <input type="hidden" name="booking_id" value="<?=$user['booking_id'];?>">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">First Name</label>
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
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Service</label>
                                        <select name="service" class="form-control" readonly>
                                            <option value="<?= $user['service']; ?>" selected><?= $user['service']; ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Slot</label>
                                        <select name="slot" class="form-control" required>
                                            <option value="<?= $user['slot']; ?>" selected ="0"></option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                            <option>6</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Employee</label>
                                        <select name="employee" class="form-control" required>
                                            <option value="" selected ="0"></option>

                                            <?php
                                            while($employee = mysqli_fetch_assoc($employees_result)) {
                                                echo "<option value='{$employee['name']}'>{$employee['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Reserve Date</label>
                                        <input type="date" name="rdate" value="<?= $user['reserve_date']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Reserve Time</label>
                                        <input type="text" name="rtime" value="<?= formatTime($user['reserve_time']); ?>" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Price</label>
                                        <input type="text" name="price" value="<?= $user['price']; ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Payment Method</label>
                                        <input type="text" name="pmethod" value="<?= $user['payment_method']; ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="reschedule_booking" class="btn btn-primary">Reschedule Booking</button>
                                </div>
                            </form>
                            <?php
                            
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
<script src="/xclusive/assets/js/admin-scripts.js"></script>