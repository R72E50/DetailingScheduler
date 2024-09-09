<style>
    .navbar-brand{
        color:#FFDE59;
    }
    .logo{
        width:50%;
    }
    .btn-link{
        margin-left:55px;
    }
    
</style>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand logo" href="https://xclusiveautospa.site/admin/admin.php"> <img class="logo" src="https://xclusiveautospa.site/img/images/logo2.png"></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-me order-1 order-lg-0 me-0 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto  me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                  <form action="https://xclusiveautospa.site/assets/php/logoutcode.php" method="POST">
                    <button type="submit" name="admin_logout_btn" class="dropdown-item">Logout</button>
                  </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

   
