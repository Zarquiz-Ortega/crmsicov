<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$id_user = $_SESSION['user']['user_id'];

if (isset($_SESSION['user']['user_name'])) {
    //consulta Nuevos cleientes
    $sqlNew = $con->prepare("SELECT `mensajes`.`id`, `customers`.`nom_empresa`, `mensajes`.`asunto` , `mensajes`.`fecha` FROM `customers` LEFT JOIN `mensajes` ON `mensajes`.`id_customers` = `customers`.`id` WHERE mensajes.id_estado = 1 and mensajes.enseguimiento = 0");
    $sqlNew->execute();
    $new = $sqlNew->fetchAll(PDO::FETCH_ASSOC);

    //consulta Mis Seguimientos
    $sqlSeguimientos = $con->prepare("SELECT `seguimiento`.`id_seguimiento`, `seguimiento`.`id_mensaje`, `customers`.`nom_empresa`, `mensajes`.`id_estado`, `estados`.`estado` FROM `estados` LEFT JOIN `mensajes` ON `mensajes`.`id_estado` = `estados`.`id` LEFT JOIN `seguimiento` ON `seguimiento`.`id_mensaje` = `mensajes`.`id` LEFT JOIN `customers` ON `mensajes`.`id_customers` = `customers`.`id` WHERE seguimiento.id_user = ? AND mensajes.id_estado <= 2 ");
    $sqlSeguimientos->execute([$id_user]);
    $seguimientos = $sqlSeguimientos->fetchAll(PDO::FETCH_ASSOC);

    //consulta Mis ventas
    $sqlVentas = $con->prepare("SELECT `venta`.`id`, `venta`.`fecha`, `venta`.`id_user`, `mensajes`.`id` AS 'id_mensaje' ,`mensajes`.`id_estado`, `customers`.`nom_empresa` FROM `venta` LEFT JOIN `customers` ON `venta`.`id_customer` = `customers`.`id` LEFT JOIN `mensajes` ON `venta`.`id_mensaje` = `mensajes`.`id` WHERE (`venta`.`id_user` = ?)");
    $sqlVentas->execute([$id_user]);
    $venta = $sqlVentas->fetchAll(PDO::FETCH_ASSOC);

    //consulta cancelaciones
    $sqlCancelacion = $con->prepare("SELECT `cancelacion`.`id_cancelacion`, `cancelacion`.`id_user`, `cancelacion`.`id_mensaje`, `customers`.`nom_empresa` FROM `cancelacion` LEFT JOIN `mensajes` ON `cancelacion`.`id_mensaje` = `mensajes`.`id` LEFT JOIN `customers` ON `mensajes`.`id_customers` = `customers`.`id` WHERE (`cancelacion`.`id_user` = ?) ");
    $sqlCancelacion->execute([$id_user]);
    $cancelacion = $sqlCancelacion->fetchAll(PDO::FETCH_ASSOC);
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
    <title>SICOV | Clientes </title>
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
                    <h1 class="mt-4">Seguimientos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Seguimiento</li>
                    </ol>
                    <div class="row">
                        <!--nuevos contactos-->
                        <div class="col-xl-6">
                            <div class="card border-primary mb-4">
                                <div class="card-header text-bg-primary">
                                    <i class="fa-solid fa-user-plus"></i>
                                    Nuevos clientes
                                </div>
                                <div class="card-body">

                                    <table id="simple_table">
                                        <thead>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Asunto</th>
                                                <th>Fecha</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Asunto</th>
                                                <th>Fecha</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($new as $datosnew) {
                                                $id = $datosnew['id'];
                                                $nombre = $datosnew['nom_empresa'];
                                                $asunto = $datosnew['asunto'];
                                                $fecha = $datosnew['fecha'];


                                            ?>
                                                <tr>
                                                    <td><?php echo $nombre; ?></td>
                                                    <td><?php echo $asunto; ?></td>
                                                    <td><?php echo $fecha; ?></td>
                                                    <td>
                                                        <a id="añadir" class="btn btn-success btn-sm" data-bs-id="<?php echo $id; ?>" data-bs-toggle="modal" data-bs-target="#añadirModal"><i class="fa-solid fa-folder-plus fa-lg"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="añadirModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Añadir seguimiento</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Desea añadir el cliente a sus seguimientos
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">cancelar</button>
                                        <button type="button" id="btn-añade" class="btn btn-success" onclick="addCliente()">Añadir</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--mis seguimientos-->
                        <div class="col-xl-6">
                            <div class="card border-warning mb-4">
                                <div class="card-header text-bg-warning">
                                    <i class="fa-solid fa-list-check"></i>
                                    Mis Seguimientos
                                </div>
                                <div class="card-body">
                                    <table id="simple_seguimientos">
                                        <thead>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Estatus</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Asunto</th>
                                                <th>Fecha</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($seguimientos as $datosseg) {
                                                $id = $datosseg['id_mensaje'];
                                                $nombre = $datosseg['nom_empresa'];
                                                $id_estado = $datosseg['id_estado'];
                                                $estado = $datosseg['estado'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $nombre; ?></td>
                                                    <?php if ($id_estado == 1) { ?>
                                                        <td>
                                                            <div class="bg-primary text-center text-white rounded-3"><?php echo $estado; ?></div>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm" href="seguimiento.php?id=<?php echo $id; ?>&estado=<?php echo $id_estado; ?>"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                                        </td>
                                                    <?php } elseif ($id_estado == 2) { ?>
                                                        <td>
                                                            <div class="bg-warning text-center text-dark rounded-3">En seguimiento</div>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm" href="seguimiento.php?id=<?php echo $id ?>&estado=<?php echo $id_estado; ?>"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--ventas-->
                        <div class="col-xl-6">
                            <div class="card border-success mb-4">
                                <div class="card-header text-bg-success">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Mis ventas
                                </div>
                                <div class="card-body">
                                    <table id="simple_Ventas">
                                        <thead>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Fecha venta</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th>Fecha venta</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($venta as $datosvent) {
                                                $id = $datosvent['id_mensaje'];
                                                $nombre = $datosvent['nom_empresa'];
                                                $fecha_venta = $datosvent['fecha'];
                                                $id_estado = $datosvent['id_estado'];


                                            ?>
                                                <tr>
                                                    <td><?php echo $nombre; ?></td>
                                                    <td><?php echo $fecha_venta; ?></td>
                                                    <td><a class="btn btn-success btn-sm" href="seguimiento.php?id=<?php echo $id ?>"><i class="fa-regular fa-eye fa-lg"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--Cancelaciones-->
                        <div class="col-xl-6">
                            <div class="card border-danger mb-4">
                                <div class="card-header  text-bg-danger">
                                    <i class="fa-solid fa-ban"></i>
                                    Canselaciones
                                </div>
                                <div class="card-body">
                                    <table id="simple_Cancelacion">
                                        <thead>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Nom empresa</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($cancelacion as $datoscan) {
                                                $id = $datoscan['id_mensaje'];
                                                $nombre = $datoscan['nom_empresa'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $nombre; ?></td>
                                                    <td><a class="btn btn-success btn-sm" href="seguimiento.php?id=<?php echo $id ?>"><i class="fa-regular fa-eye fa-lg"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
    <script>
        let añadirModal = document.getElementById('añadirModal')
        añadirModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonAñade = añadirModal.querySelector('.modal-footer #btn-añade')
            buttonAñade.value = id
        })

        function addCliente() {

            let buttonAñade = document.getElementById('btn-añade')
            let id = buttonAñade.value

            let url = 'clases/addCliente.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('action', 'añade')

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload()
                    }
                })
        }
    </script>
</body>

</html>