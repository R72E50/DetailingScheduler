<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"><?php echo date("D d, M y"); ?></div>
                <a class="nav-link" href="https://xclusiveautospa.site/admin/admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>


                <div class="sb-sidenav-menu-heading">Users & Employees</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#users" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Users
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="users" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-users.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Registered Users
                         </a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#employees" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                        Employees
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="employees" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-employees.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                            Current Employees
                         </a>
                    </nav>
                </div>

             
                <div class="sb-sidenav-menu-heading">Bookings</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pending" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                        Pending Bookings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="pending" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-booking.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-hourglass-start"></i></div>
                            Pending Approvals
                         </a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-booking-waitlist.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-hourglass-start"></i></div>
                            Waitlisted
                         </a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-booking-declined.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-folder"></i></div>
                            Archived Requests
                         </a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#approved" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-check"></i></i></div>
                        Assigned Bookings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="approved" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-assigned-booking.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                            Confirmed
                         </a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-booking-upcoming.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                            Upcoming 
                         </a>
                    </nav>
                    
                </div>



                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#history" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                        Bookings History
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="history" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-booking-completed.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-hourglass-start"></i></div>
                           Settled Bookings
                         </a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-booking-archived.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-ban"></i></div>
                            Archived Booking
                         </a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Payments</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#payments" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                         Transactions
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="payments" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-cash-payments.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                            Cash Payments
                         </a>
                    </nav>
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="https://xclusiveautospa.site/admin/webpages/admin-view-paypal-payments.php">
                            <div class="sb-nav-link-icon"><i class="fab fa-paypal"></i></div>
                            Paypal Payments
                         </a>
                    </nav>
                </div>
                


        

                


            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>
