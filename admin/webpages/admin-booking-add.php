<?php
session_start();
include('../includes/admin-header.php');
include('../../includes/dbcon.php');//since no authentication yet
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Reservation</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"> Dashboard</li>
        <li class="breadcrumb-item"> Reservation</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
            <div class="card-header">
                <h4>Add Booking</h4>
            </div>
            <div class="card-body">
                 <?php

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
                                        <input type="text" name="name" value="" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Contact</label>
                                        <input type="text" name="phone" value="" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Email Address</label>
                                        <input type="text" name="email" value="" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Type</label>
                                        <select name="cartype" class="form-control" >
                                            <?php
                                                      // Retrieve Car Sizes from the database
                                                      // Fetch car types using prepared statement
                                                      $stmt_car_types = $con->prepare("SELECT id, car_type FROM car_types");
                                                      $stmt_car_types->execute();
                                                      $result_car_types = $stmt_car_types->get_result();
                    
                                                      if ($result_car_types && $result_car_types->num_rows > 0) {
                                                          while ($row = $result_car_types->fetch_assoc()) {
                                                              echo '<option value="' . $row['id'] . '">' . $row['car_type'] . '</option>';
                                                          }
                                                     
                                                      $stmt_car_types->close(); // Close the prepared statement for car types
                                                  
                                              
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Service</label>
                                        <select name="service" class="form-control" >
                                            <?php
                                             $stmt_car_services = $con->prepare("SELECT id, service_type FROM car_services");
                                             $stmt_car_services->execute();
                                             $result_car_services = $stmt_car_services->get_result();
                                      
                                              if ($result_car_services && $result_car_services->num_rows > 0) {
                                                  while ($row = $result_car_services->fetch_assoc()) {
                                                      echo '<option value="' . $row['service_type'] . '">' . $row['service_type'] . '</option>';
                                                  }
                                              }
                                              $stmt_car_services->close(); // Close the prepared statement for car services
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Car Slot</label>
                                        <select name="slot" class="form-control">
                                            <option value="" selected ="0"></option>
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
                                        <select name="employee" class="form-control">
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
                                        <input type="date" name="rdate" value="" class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Reserve Time</label>
                                        <input type="time" name="rtime" value="" class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Price</label>
                                        <input type="text" name="price" value="" class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Payment Method</label>
                                        <input type="text" name="pmethod" value="" class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="add_booking" class="btn btn-primary">Confirm Booking</button>
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
                    
                ?>
                    
                
            </div>
           </div>
        </div>
    </div>
    
    

<?php
include('../includes/admin-footer.php');
?>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>