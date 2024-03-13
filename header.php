<!--inicio Header-->
<nav class="sb-topnav navbar navbar-expand" style="background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="dashboard.php"><img class="logo" src="img/logoSicov.png"> <span style=" font-family: 'nasalization', sans-serif;">SICOV</span> </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li class="text-center"><h5><?php echo $_SESSION['user']['user_name']?></h5></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="configuraciones.php">Configuraciones</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
<!--fin Header-->
