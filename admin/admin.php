<?php
include('../includes/dbcon.php');
include('includes/admin-header.php');
?>
<head>

</head>


<style>
    .button {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    color: blue; /* Set your preferred link color */
    text-decoration: underline;
    cursor: pointer;
}
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4">Xclusive Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Admin Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Total Users
                    <?php
                    $total_users_query = "SELECT * FROM users";
                    $total_users_stmt = mysqli_prepare($con, $total_users_query);

                    if ($total_users_stmt) {
                        mysqli_stmt_execute($total_users_stmt);
                        mysqli_stmt_store_result($total_users_stmt);
                        $users_total = mysqli_stmt_num_rows($total_users_stmt);

                        if ($users_total > 0) {
                            echo '<h4 class="ml-0">' . $users_total . '</h4>';
                        } else {
                            echo '<h4 class="ml-0"> No Data Found </h4>';
                        }
                    } else {
                        echo '<h4 class="ml-0">Error in preparing the statement</h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Schedules
                    <?php
                    $total_booking_query = "SELECT * FROM bookings WHERE status = 'Confirmed'";
                    $total_booking_stmt = mysqli_prepare($con, $total_booking_query);

                    if ($total_booking_stmt) {
                        mysqli_stmt_execute($total_booking_stmt);
                        mysqli_stmt_store_result($total_booking_stmt);
                        $booking_total = mysqli_stmt_num_rows($total_booking_stmt);

                        if ($booking_total > 0) {
                            echo '<h4 class="ml-0">' . $booking_total . '</h4>';
                        } else {
                            echo '<h4 class="ml-0"> No Data Found </h4>';
                        }
                    } else {
                        echo '<h4 class="ml-0">Error in preparing the statement</h4>';
                    }
                    ?>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Available Slots
                    <?php
                    $total_payment_query = "SELECT sum(price) FROM bookings";
                    $payment_stmt = mysqli_prepare($con, $total_payment_query);

                    if ($payment_stmt) {
                        mysqli_stmt_execute($payment_stmt);
                        mysqli_stmt_store_result($payment_stmt);
                        $payment_total = mysqli_stmt_num_rows($payment_stmt);

                        if ($payment_total > 0) {
                            echo '<h4 class="ml-0">' . $payment_total . '</h4>';
                        } else {
                            echo '<h4 class="ml-0"> No Data Found </h4>';
                        }
                    } else {
                        echo '<h4 class="ml-0">Error in preparing the statement</h4>';
                    }
                    ?>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Total Payments
                    <?php
                    $total_payment_query = "SELECT SUM(price) AS total_payment FROM bookings WHERE payment_status = 1";
                    $payment_stmt = mysqli_prepare($con, $total_payment_query);

                    if ($payment_stmt) {
                        mysqli_stmt_execute($payment_stmt);
                        mysqli_stmt_store_result($payment_stmt);
                        mysqli_stmt_bind_result($payment_stmt, $total_payment);
                        mysqli_stmt_fetch($payment_stmt);

                        if ($total_payment !== null) {
                            echo '<h4 class="ml-0">' . $total_payment . '</h4>';
                        } else {
                            echo '<h4 class="ml-0">No Data Found</h4>';
                        }
                    } else {
                        echo '<h4 class="ml-0">Error in preparing the statement</h4>';
                    }
                    ?>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                   Payments
                </div>
                <div class="card-body"><canvas id="payment-method" width="500" height="300" style="width: 500px !important; height: 300px !important;"></canvas></div>
                <div class="card-footer small text-muted">
                    <form action="https://xclusiveautospa.site/assets/php/reports.php" method="POST">
                        <button class="button" type="submit" name="dailyPayment">Daily</button>
                        <button class="button" type="submit" name="weeklyPayment">Weekly</button>
                        <button class="button" type="submit" name="monthlyPayment">Monthly</button>
                    </form>
                 </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                   Bookings
                </div>
                <div class="card-body"> <canvas id="booking-chart"></canvas></div>
                <div class="card-footer small text-muted">
                    <form action="https://xclusiveautospa.site/assets/php/reports.php" method="POST">
                        <button class="button" type="submit" name="dailyBooking">Daily</button>
                        <button class="button" type="submit" name="weeklyBooking">Weekly</button>
                        <button class="button" type="submit" name="monthlyBooking">Monthly</button>
                    </form>
                 </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Sentiment Analysis
                </div>
                <div class="card-body">
                    <canvas id="sentiment-chart"></canvas>
                </div>
                <div class="card-footer small text-muted">
                    <form action="https://xclusiveautospa.site/assets/php/reports.php" method="POST">
                        <button class="button" type="submit" name="dailyAnalysis">Daily</button>
                        <button class="button" type="submit" name="weeklyAnalysis">Weekly</button>
                        <button class="button" type="submit" name="monthlyPayment">Monthly</button>
                    </form>
                 </div>
            </div>
        </div>
    </div>

</div>
<script src="https://xclusiveautospa.site/assets/js/admin-scripts.js"></script>
<script src="https://xclusiveautospa.site/assets/js/payment-method-chart.js"></script>
<script src="https://xclusiveautospa.site/assets/js/sentiment-analysis-chart.js"></script>
<script src="https://xclusiveautospa.site/assets/js/bookings-chart.js"></script>
<script src="https://xclusiveautospa.site/assets/js/charts.js"></script>

<?php
include('includes/admin-footer.php');
?>
