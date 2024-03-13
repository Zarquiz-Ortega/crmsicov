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
    //consulta customers 
    /*$sql = $con->prepare("SELECT id, nombres, apellidos, telefono, nom_empresa, email, mensaje, modulos, asunto, estado, fecha  FROM customers");
    $sql->execute();
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);*/
} else {
    header("location: login.php");
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

        <div id="layoutSidenav_content">
            <main style="background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%)">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Nombre</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tables</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            descrpcion del script
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            name card
                        </div>
                        <div class="card-body">
                            cantent card
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