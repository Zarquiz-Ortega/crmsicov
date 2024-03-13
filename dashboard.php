<?php

require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();
$resultados = array();

if (isset($_SESSION['user']['user_name'])) {
    $id_user = $_SESSION['user']['user_id'];
    //consulta  de conteo de estdos 
    $sqlEstados = $con->prepare("SELECT e.id, e.estado, COUNT(m.id_estado) AS cantidad FROM estados e LEFT JOIN mensajes m ON e.id = m.id_estado GROUP BY e.estado");
    $sqlEstados->execute();
    $resultados = $sqlEstados->fetchAll(PDO::FETCH_ASSOC);

    //consulta  de conteo de estdos por mes
    $sqlEstadosMes = $con->prepare("SELECT DATE_FORMAT(m.fecha, '%b') AS mes, e.estado, COUNT(m.id_estado) AS cantidad FROM estados e LEFT JOIN mensajes m ON e.id = m.id_estado GROUP BY mes, e.estado ORDER BY FIELD(mes, 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'), e.estado; ");
    $sqlEstadosMes->execute();
    $datos_grafica_barras = $sqlEstadosMes->fetchAll(PDO::FETCH_ASSOC);

    //consulta  leads vs ventas por mes 
    $sqlEstadosMes = $con->prepare("SELECT DATE_FORMAT(m.fecha, '%b') AS mes, SUM(CASE WHEN e.estado = 'Venta realizada' THEN 1 ELSE 0 END) AS ventas_realizadas, COUNT(*) AS total_filas FROM estados e LEFT JOIN mensajes m ON e.id = m.id_estado GROUP BY mes ORDER BY FIELD(mes, 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'); ");
    $sqlEstadosMes->execute();
    $datos_grafica_leads = $sqlEstadosMes->fetchAll(PDO::FETCH_ASSOC);

    //consuta to ventas por ususario
    $sqlVentasUsuario = $con->prepare("SELECT usuarios.id, usuarios.nombres, usuarios.apellidos, COUNT(venta.id) AS cantidad_ventas
    FROM usuarios
    LEFT JOIN venta ON venta.id_user = usuarios.id
    GROUP BY usuarios.id, usuarios.nombres, usuarios.apellidos
    HAVING COUNT(venta.id) > 0;");
    $sqlVentasUsuario->execute();
    $topventas = $sqlVentasUsuario->fetchAll(PDO::FETCH_ASSOC);
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
    <title>SICOV | Dashboard</title>
    <!--Favicon-->
    <link rel="shortcut icon" href="img/sicovLogo.ico">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        .logo {
            width: 40px !important;
            height: auto;
        }
    </style>
</head>

<body class="sb-nav-fixed" style="<?php echo ESTOLO_BODY ?>">

    <?php include 'header.php'; ?>

    <div id="layoutSidenav">

        <?php include 'sidebard.php'; ?>


        <!--inicio continer-->
        <div id="layoutSidenav_content" <?php echo ESTILO_CONTENT ?>>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <!--Cards-->
                    <div class="row">
                        <?php
                        foreach ($resultados as $fila) {
                            $id = $fila['id'];
                            $estado = $fila['estado'];
                            $cantidad = $fila['cantidad'];
                        ?>
                            <?php if ($id == 1) { ?>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-primary text-white text-center mb-4 ">
                                        <div class="card-header">
                                            <?php echo $estado; ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="h4"><span class=""><i class="fa-solid fa-person"></i> <?php echo $cantidad; ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($id == 2) { ?>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-warning text-white text-center mb-4">
                                        <div class="card-header">
                                            <?php echo $estado; ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="h4"><span class=""><i class="fa-solid fa-headset"></i> <?php echo $cantidad;  ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($id == 3) { ?>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-success text-white text-center mb-4">
                                        <div class="card-header">
                                            <?php echo $estado; ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="h4"><span class=""><i class="fa-solid fa-money-bill-trend-up "></i> <?php echo $cantidad;  ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($id == 4) { ?>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-danger text-white text-center mb-4">
                                        <div class="card-header">
                                            <?php echo $estado; ?>
                                        </div>
                                        <div class="card-body">
                                            <p class="h4"><span class=""><i class="fa-solid fa-ban"></i> <?php echo $cantidad;  ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <!--graficas-->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card border-dark mb-4">
                                <div class="card-header text-bg-dark">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Grafica de barras
                                </div>
                                <div class="card-body">
                                    <?php
                                    $leads_data = array();
                                    $ventas_data = array();

                                    $meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec');

                                    foreach ($meses as $mes) {
                                        $leads_data[] = 0;
                                        $ventas_data[] = 0;
                                    }

                                    foreach ($datos_grafica_leads as $dato) {
                                        $mes = $dato['mes'];
                                        $leads_data[array_search($mes, $meses)] = (int)$dato['total_filas'] - (int)$dato['ventas_realizadas'];
                                        $ventas_data[array_search($mes, $meses)] = (int)$dato['ventas_realizadas'];
                                    }

                                    ?>
                                    <figure class="highcharts-figure">
                                        <div id="graficaLeads"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- Grafica de estados -->
                        <div class="col-xl-6">
                            <div class="card border-dark mb-4">
                                <div class="card-header text-bg-dark">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Grafica de lineas
                                </div>
                                <div class="card-body">
                                    <?php
                                    $series = array();
                                    $meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec');

                                    foreach ($datos_grafica_barras as $dato) {
                                        $estado = $dato['estado'];
                                        $mes = $dato['mes'];
                                        $cantidad = (int)$dato['cantidad'];

                                        if (!isset($series[$estado])) {
                                            $series[$estado] = array_fill(0, 12, 0);
                                        }

                                        $index = array_search($mes, $meses);
                                        $series[$estado][$index] = $cantidad;
                                    }
                                    ?>
                                    <figure class="highcharts-figure">
                                        <div id="grafica_estados"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- Grafica de pastel Top vendedores -->
                        <div class="col-xl-6">
                            <div class="card border-dark mb-4">
                                <div class="card-header text-bg-dark">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Grafica de pastel
                                </div>
                                <div class="card-body">
                                    <?php
                                    $datos_grafica_pastel = array();

                                    foreach ($topventas as $fila) {
                                        $datos_grafica_pastel[] = array(
                                            'name' => htmlspecialchars($fila['nombres'] . ' ' . $fila['apellidos']),
                                            'y' => (int)$fila['cantidad_ventas']
                                        );
                                    }

                                    $datos_grafica_pastel = json_encode($datos_grafica_pastel);
                                    ?>

                                    <figure class="highcharts-figure">
                                        <div id="grafico_pastel"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card border-primary mb-4">
                                <div class="card-header text-bg-primary ">
                                    <i class="fa-solid fa-crown"></i>
                                    Top vendedore
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php
                                        usort($topventas, function ($a, $b) {
                                            return $b['cantidad_ventas'] - $a['cantidad_ventas'];
                                        });
                                        $posicion = 1;
                                        foreach ($topventas as $fila) {
                                            if ($fila['cantidad_ventas'] > 0) {
                                                $clase_css = ($posicion <= 3) ? 'list-group-item destacado' : 'list-group-item'; ?>
                                                <li class="<?php echo $clase_css ?> '">
                                                    <h5> <i class="fa-solid fa-crown fa-lg " <?php if ($posicion == 1) {
                                                                                                    echo 'style="color: #f0fc0c;"';
                                                                                                } elseif ($posicion == 2) {
                                                                                                    echo 'style="color: #c0c0c0;"';
                                                                                                } elseif ($posicion == 3) {
                                                                                                    echo 'style="color: #ff8000;"';
                                                                                                } ?>></i> <?php echo $posicion ?> - <?php echo htmlspecialchars($fila['nombres'] . ' ' . $fila['apellidos']) . ' (' . $fila['cantidad_ventas'] . ' ventas) '; ?> </h5>
                                                </li>
                                        <?php $posicion++;
                                                if ($posicion > 5) {
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
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
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- Grafica de leads vs ventas -->
    <script>
        var chart = Highcharts.chart('graficaLeads', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Leads vs Ventas'
            },
            legend: {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical'
            },
            xAxis: {
                categories: <?= json_encode($meses) ?>,
                labels: {
                    x: -10
                }
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Amount'
                }
            },
            series: [{
                name: 'Leads',
                data: <?= json_encode($leads_data) ?>
            }, {
                name: 'Venta realizada',
                data: <?= json_encode($ventas_data) ?>
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal'
                        },
                        yAxis: {
                            labels: {
                                align: 'left',
                                x: 0,
                                y: -5
                            },
                            title: {
                                text: null
                            },
                        },
                        subtitle: {
                            text: null
                        },
                        credits: {
                            enabled: false
                        }
                    }
                }]
            }
        });
    </script>
    <!-- Grafica de estados  -->
    <script>
        Highcharts.chart('grafica_estados', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Cuentas activas'
            },
            xAxis: {
                categories: <?= json_encode($meses) ?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '# de cuentas'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                <?php foreach ($series as $estado => $data) { ?> {
                        name: '<?= $estado ?>',
                        data: <?= json_encode($data) ?>
                    },
                <?php } ?>
            ]
        });
    </script>
    <!-- Grafica de pastel -->
    <script>
        Highcharts.chart('grafico_pastel', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Top de ventas',
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Usuarios',
                colorByPoint: true,
                data: <?php echo $datos_grafica_pastel; ?>
            }]
        });
    </script>


</html>


</body>

</html>