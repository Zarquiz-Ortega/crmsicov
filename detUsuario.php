<?php
/*
Script: Configuraciones de usuario
autor: Zarquiz ortega 
fecha: 06-10-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();


if (isset($_SESSION['user']['user_name'])) {
    $id_user = $_GET['id'];
    //consulta usuario 
    $sql = $con->prepare("SELECT nombres, apellidos, usuario, nom_userFoto, meta FROM usuarios WHERE id = ? LIMIT 1");
    $sql->execute([$id_user]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    $meta = $row['meta'];
    //consulta usuario-estados
    $sqlEstados = $con->prepare("SELECT M.`id_estado`, COUNT(S.`id_mensaje`) AS cantidad_seguimientos FROM `mensajes` AS M LEFT JOIN (SELECT `id_mensaje` FROM `seguimiento` WHERE `id_user` = ?) AS S ON S.`id_mensaje` = M.`id` GROUP BY M.`id_estado`");
    $sqlEstados->execute([$id_user]);
    $rowEstados = $sqlEstados->fetchall(PDO::FETCH_ASSOC);
    $nombreEstados = [
        1 => 'Clientes nuevos',
        2 => 'En seguimiento',
        3 => 'Ventas realizadas',
        4 => 'Cancelaciones'
    ];
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
    <title>SICOV | configuraciones</title>
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
                    <h1 class="mt-4">Configuraciones</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="usuarios.php">Usuarios</a></li>
                        <li class="breadcrumb-item active"><?php echo $row['nombres'] ?> <?php echo $row['apellidos'] ?></li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <h6>Datos del susario</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card border-dark mb-3">
                        <div class="card-header text-bg-dark">
                            <i class="fa-solid fa-database"></i>
                            Datos del usuario
                        </div>
                        <div class="row g-0">
                            <div class="col-md-4 align-items-end">
                                <img src="img/usuarios/<?php echo $row['nom_userFoto'] ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="card-title">
                                        <div class="input-group input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing">Nombre:</span>
                                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing" value="<?php echo $row['nombres'] ?> <?php echo $row['apellidos'] ?>" disabled>
                                        </div>
                                        <div class="input-group input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing">Usuario:</span>
                                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing" value="<?php echo $row['usuario'] ?>" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <?php
                                                    $ventas_realizada = array();
                                                    foreach ($rowEstados as $row) {
                                                        $idEstado = $row['id_estado'];
                                                        $cantidadSeguimientos = $row['cantidad_seguimientos'];
                                                        $nombreEstado = isset($nombreEstados[$idEstado]) ? $nombreEstados[$idEstado] : 'Estado Desconocido';
                                                    ?>
                                                        <?php if ($idEstado == 1) { ?>
                                                            <div class="col-6">
                                                                <div class="card bg-primary text-white text-center mb-2">
                                                                    <div class="card-header"><?php echo $nombreEstado ?></div>
                                                                    <div class="card-body">
                                                                        <h5><?php echo $cantidadSeguimientos ?></h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        if ($idEstado == 2) { ?>
                                                            <div class="col-6">
                                                                <div class="card bg-warning text-white text-center mb-2">
                                                                    <div class="card-header"><?php echo $nombreEstado ?></div>
                                                                    <div class="card-body">
                                                                        <h5><?php echo $cantidadSeguimientos ?></h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        if ($idEstado == 3) { ?>
                                                            <div class="col-3 col-6">
                                                                <div class="card bg-success text-white text-center">
                                                                    <div class="card-header"><?php echo $nombreEstado ?></div>
                                                                    <div class="card-body">
                                                                        <h5><?php echo $cantidadSeguimientos ?></h5>
                                                                        <?php $ventas_realizada[] = $cantidadSeguimientos; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        if ($idEstado == 4) { ?>
                                                            <div class="col-3 col-md-6">
                                                                <div class="card bg-danger text-white text-center">
                                                                    <div class="card-header"><?php echo $nombreEstado ?></div>
                                                                    <div class="card-body">
                                                                        <h5><?php echo $cantidadSeguimientos ?></h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <figure class="highcharts-figure">
                                                            <div id="container"></div>
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('container', {

            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                height: '60%'
            },

            title: {
                text: 'Meta de ventas'
            },

            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '140%'
            },

            yAxis: {
                min: 0,
                max: <?= $meta ?>,
                tickPixelInterval: 72,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 2,
                minorTickInterval: null,
                labels: {
                    distance: 20,
                    style: {
                        fontSize: '14px'
                    }
                },
                lineWidth: 0,
                plotBands: [{
                    from: 0,
                    to: <?= $meta * 0.5 ?>,
                    color: '#DF5353',
                    thickness: 20
                }, {
                    from: <?= $meta * 0.5 ?>,
                    to: <?= $meta * 0.75 ?>,
                    color: '#DDDF0D',
                    thickness: 20
                }, {
                    from: <?= $meta * 0.75 ?>,
                    to: <?= $meta ?>,
                    color: '#55BF3B',
                    thickness: 20
                }]
            },

            series: [{
                name: 'Ventas realizadas',
                data: <?= json_encode($ventas_realizada) ?>,
                dataLabels: {
                    format: '{y} Ventas',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '16px'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'gray',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'gray',
                    radius: 6
                }
            }]
        });
    </script>

</body>

</html>