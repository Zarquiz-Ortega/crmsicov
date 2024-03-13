        <!--inicio sidebar-->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav" id="sidenavAccordion" style="background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Inicio</div>
                        <a class="nav-link nav-item-active" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-microchip"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Aciones</div>
                        <!--Clientes-->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseClientes" aria-expanded="false" aria-controls="collapseClientes">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-person-circle-plus"></i></div>
                            Clientes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseClientes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="customers.php">Seguimientos</a>
                                <a class="nav-link" href="agregaCliente.php">Nuevo cliente</a>
                            </nav>
                        </div>
                        <!--Usuarios-->
                        <?php if($_SESSION['user']['user_rol'] == 2){?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false" aria-controls="collapseUsuarios">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                            Usuarios
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        
                        <div class="collapse" id="collapseUsuarios" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link" href="usuarios.php" aria-expanded="false" aria-controls="pagesCollapseAuth">Usuarios</a>
                                <a class="nav-link" href="agregaUsuario.php" aria-expanded="false" aria-controls="pagesCollapseAuth">Nuevo Usuario</a>
                        </div>
                        <?php } ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAgenda" aria-expanded="false" aria-controls="collapseAgenda">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-days"></i></div>
                            Agenda
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAgenda" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link" href="calendario.php" aria-expanded="false" aria-controls="pagesCollapseAuth">Calendario</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!--fin sidebar-->