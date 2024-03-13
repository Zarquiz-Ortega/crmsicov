<?php
/*
Script: Plantilla para el CRM de SICOV  
autor: Zarquiz ortega 
fecha: 22-09-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();
$resultados = array();

if (isset($_SESSION['user']['user_name'])) {

    $sql = $con->prepare("SELECT `usuarios`.`id`, `usuarios`.`nombres`, `usuarios`.`apellidos`, `usuarios`.`usuario`, `usuarios`.`nom_userFoto` FROM `usuarios`");
    $sql->execute();
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SICOV | Plantilla</title>
    <!--Favicon-->
    <link rel="shortcut icon" href="img/sicovLogo.ico">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .logo {
            width: 40px !important;
            height: auto;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!--Header-->
    <?php include 'header.php'; ?>

    <div id="layoutSidenav">
        <!--sidebard-->
        <?php include 'sidebard.php'; ?>

        <div id="layoutSidenav_content" <?php echo ESTILO_CONTENT ?>>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Usuarios</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            Lista de usuarios activos en el sistema
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-sm-5 g-3">
                        <?php foreach ($row as $datos) {
                            $id_user = $datos['id'];
                            $nombre = $datos['nombres'];
                            $apellido = $datos['apellidos'];
                            $user_name = $datos['usuario'];
                            $nom_userFoto = $datos['nom_userFoto'];
                        ?>
                            <div class="col">
                                <div class="card text-center shadow-sm ">
                                    <div class="card-header text-center text-bg-dark">
                                        <b><?php echo $user_name ?></b>
                                    </div>
                                    <img src="img/usuarios/<?php echo $nom_userFoto ?>" class="img-thumbnail">
                                    <div class="card-body">
                                        <p><?php echo $nombre ?> <?php echo $apellido ?></p>
                                        <hr>
                                        <a href="detUsuario.php?id=<?php echo $id_user ?>" class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Detalles</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>