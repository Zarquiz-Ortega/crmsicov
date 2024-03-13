<?php
/*
Script: Plantilla para el CRM de SICOV  
autor: Zarquiz ortega 
fecha: 22-09-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';
require 'clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

//areglos para los mensajes
$errors = [];

if (isset($_SESSION['user']['user_name'])) {


    $id = $_GET['id'];
    $id_user = $_SESSION['user']['user_id'];

    //consulta customers 
    $sql = $con->prepare("SELECT `customers`.`id` AS 'id_customer', `customers`.`nombres`, `customers`.`apellidos`, `customers`.`telefono`, `customers`.`nom_empresa`, `customers`.`email`,`estados`.`estado`, `mensajes`.`mensaje`, `mensajes`.`modulos`, `mensajes`.`asunto`, `mensajes`.`fecha`, `mensajes`.`id_estado` FROM `estados` LEFT JOIN `mensajes` ON `mensajes`.`id_estado` = `estados`.`id` LEFT JOIN `customers` ON `mensajes`.`id_customers` = `customers`.`id` WHERE mensajes.id = ? LIMIT 1");
    $sql->execute([$id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    $id_customer = $row['id_customer'];
    $nombre = $row['nombres'] . ' ' . $row['apellidos'];
    $telefono = $row['telefono'];
    $codigo_pais = substr($telefono, 0, 2);
    $codigo_area = substr($telefono, 2, 4);
    $numero = substr($telefono, 6);
    $telefono_formateado = "($codigo_pais) $codigo_area $numero";
    $nom_empresa = $row['nom_empresa'];
    $email = $row['email'];
    $mensaje = $row['mensaje'];
    $modulo = $row['modulos'];
    $modulos = explode(",", trim($modulo, ","));
    $asunto = $row['asunto'];
    $estado = $row['estado'];
    $fecha = $row['fecha'];
    $idestado = $row['id_estado'];

    //consulta comentarios
    $sqlcomentarios = $con->prepare("SELECT `seguimiento`.`com_seg_1`, `seguimiento`.`com_seg_2`, `seguimiento`.`com_seg_3`, `seguimiento`.`com_seg_4`, `mensajes`.`id` AS 'id_mensaje', `usuarios`.`id` AS 'id_user' FROM `mensajes` LEFT JOIN `seguimiento` ON `seguimiento`.`id_mensaje` = `mensajes`.`id` LEFT JOIN `usuarios` ON `seguimiento`.`id_user` = `usuarios`.`id` WHERE mensajes.id = ? AND usuarios.id = ?    ");
    $sqlcomentarios->execute([$id, $id_user]);
    $comentarios = $sqlcomentarios->fetch(PDO::FETCH_ASSOC);

    //consulta comentarios
    $sqleventa = $con->prepare("SELECT `venta`.`monto`, `venta`.`descuento`, `venta`.`id_mensaje`, `venta`.`id_user` FROM `venta` WHERE ((`venta`.`id_mensaje` = ?) AND (`venta`.`id_user` = ?)) ");
    $sqleventa->execute([$id, $id_user]);
    $venta = $sqleventa->fetch(PDO::FETCH_ASSOC);
    
    if (!empty($_POST)) {
        $acction = $_POST['acction'];
        $id_mensaje = $_POST['id_mensaje'];
        $comentario = $_POST['comentario'];

        if ($acction == 'NC') {
            if (!reguistarSeguimiento($id_mensaje, $comentario, $acction, $con)) {
                $errors[] = "A ocurrido un error inesperado. Intenta mas tarde";
            }
            header("location: customers.php");
        } elseif ($acction == 'SS') {
            if (!reguistarSeguimiento($id_mensaje, $comentario, $acction, $con)) {
                $errors[] = "A ocurrido un error inesperado. Intenta mas tarde";
            } else {
                $monto = $_POST['monto'];
                $descuento = $_POST['descuento'];
                $id_customer = $_POST['id_customer'];
                $fecha_venta = obtenerFecha();
                if (reguistraVenta([$monto, $descuento, $fecha_venta, $id_customer, $id_user, $id_mensaje], $con)) {
                    header("location: customers.php");
                }
            }
        }
    }
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
    <title>SICOV | Seguimiento</title>
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
    <?php include 'header.php';
    ?>

    <div id="layoutSidenav">
        <!--sidebard-->
        <?php include 'sidebard.php';
        ?>

        <div id="layoutSidenav_content" <?php echo ESTILO_CONTENT ?>>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Seguimiento del cliente</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="customers.php">Seguimiento</a></li>
                        <li class="breadcrumb-item active">Customer</li>
                    </ol>
                    <?php mostrarErrors($errors); ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <h5>Seguimiento del cliente </h5>
                                </div>
                                <?php if ($idestado <= 2) { ?>
                                    <div class="col-2">
                                        <div class="d-flex align-items-end flex-column">
                                            <a id="añadir" class="btn btn-danger btn-mb" data-bs-id="<?php echo $id; ?>" data-bs-toggle="modal" data-bs-target="#cancelarModal"><i class="fa-solid fa-ban"></i> Cancelar venta</a>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="cancelarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Cancelar venta</h1>
                                                    <button type="button" class="btn-close" data-bs-id="<?php echo $id; ?>" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="fs-5">Desea cancelar la venta</p>
                                                    <div class="form-floating">
                                                        <textarea class="form-control" id="motCancela"></textarea>
                                                        <label for="floatingTextarea">Motivo de canselacion</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" id="btn-cancela" class="btn btn-success" onclick="addCliente()">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card border-primary mb-4">
                                <div class="card-header text-bg-primary">
                                    <i class="fa-solid fa-user-plus fa-sm"></i> Datos del cliente
                                </div>
                                <div class="card-body">

                                    <h5><b>Nombre:</b> <?php echo $nombre; ?></h5>
                                    <h5><b>Teléfono:</b> <?php echo $telefono_formateado; ?></h5>
                                    <h5><b>Email:</b> <?php echo $email; ?></h5>
                                    <h5><b>Nombre de la empresa:</b> <?php echo $nom_empresa; ?></h5>
                                    <hr>
                                    <h5><b>Estatus:&nbsp;</b><?php if ($idestado == 1) { ?>
                                            <td>
                                                <span class="badge text-bg-primary"><?php echo $estado ?></span>
                                            </td>
                                        <?php } elseif ($idestado == 2) { ?>
                                            <td>
                                                <span class="badge text-bg-warning"><?php echo $estado ?></span>
                                            </td>
                                        <?php } elseif ($idestado == 3) { ?>
                                            <td>
                                                <span class="badge text-bg-success"><?php echo $estado ?></span>
                                            </td>
                                        <?php } elseif ($idestado == 4) { ?>
                                            <td>
                                                <span class="badge text-bg-danger"><?php echo $estado ?></span>
                                            </td>
                                        <?php } ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card border-warning mb-4">
                                <div class="card-header  text-bg-warning">
                                    <i class="fa-solid fa-list-check"></i>
                                    <?php echo $asunto; ?>
                                </div>
                                <div class="card-body">
                                    <?php if ($asunto == 'Cotización') { ?>
                                        <h6 class="fw-bold"><i class="fa-solid fa-circle-info"></i> Modulos de interés</h6>
                                        <hr>
                                        <ul class="list-group">
                                            <?php $count = count($modulos);
                                            for ($i = 0; $i < $count; $i++) { ?>
                                                <li class="list-group-item"><i class="fa-solid fa-chevron-right fa-sm"></i> <?php echo $modulos[$i]; ?></li>
                                            <?php } ?>
                                        </ul>
                                        <br>
                                        <h6 class="fw-bold"><i class="fa-solid fa-circle-question"></i> Dudas o comentarios</h6>
                                        <hr>
                                        <h6><?php echo $mensaje; ?></h6>
                                    <?php } else { ?>
                                        <h6 class="fw-bold"><i class="fa-solid fa-circle-question"></i> Dudas o Comentarios</h6>
                                        <hr>
                                        <h6><?php echo $mensaje; ?></h6>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($idestado == 1) {
                        $acction = $idestado; ?>
                        <div class="card border-dark mb-4">
                            <div class="card-header text-bg-dark">
                                <i class="fa-solid fa-comments"></i> Ingresa lo que se ha realizado en el primer contacto con el cliente.
                            </div>
                            <div class="card-body">
                                <form action="seguimiento.php?id=<?php echo $id ?>" method="post">
                                    <div class="input-group ">
                                        <input type="hidden" name="acction" id="acction" value="NC">
                                        <input type="hidden" name="id_mensaje" id="id_mensaje" value="<?php echo $id ?>">
                                        <span class="input-group-text"><i class="fa-solid fa-notes-medical fa-lg"></i></span>
                                        <textarea class="form-control" placeholder="Comentarios" name="comentario" id="comentario"></textarea>
                                    </div><br>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">Avanzar al siguiente paso</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div <?php if ($idestado == 4) { ?>class="col-xl" <?php } else { ?> class="col-xl-6" <?php } ?>>
                                <div class="card border-dark mb-4">
                                    <div class="card-header text-bg-dark">
                                        <i class="fa-solid fa-comments"></i>
                                        Comentarios del seguimiento
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            $com_seg_1 = $comentarios['com_seg_1'];
                                            $com_seg_2 = $comentarios['com_seg_2'];
                                            $com_seg_3 = $comentarios['com_seg_3'];
                                            $com_seg_4 = $comentarios['com_seg_4'];
                                            ?>
                                            <?php if ($com_seg_1 != '') { ?>
                                                <div class="col-6">
                                                    <div class="card border-primary mb-2">
                                                        <div class="card-sm-header text-bg-primary">
                                                            <h6 class="text-center">Primer contacto</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="card-text"><?php echo $com_seg_1 ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($com_seg_2 != '') { ?>
                                                <div class="col-6">
                                                    <div class="card border-warning mb-2">
                                                        <div class="card-sm-header text-bg-warning">
                                                            <h6 class="text-center">En seguimiento</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="card-text"><?php echo $com_seg_2 ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($com_seg_3 != '') { ?>
                                                <div class="col-sm-6">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <h5 class="card-title"> <span class="badge text-bg-primary">Venta realizada</span> </h5>
                                                            <p class="card-text"><?php echo $com_seg_3 ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                            if ($com_seg_4 != '') { ?>
                                                <div class="col-6">
                                                    <div class="card border-danger mb-2">
                                                        <div class="card-sm-header text-bg-danger">
                                                            <h6 class="text-center">venta cancelada</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="card-text"><?php echo $com_seg_4 ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($idestado <= 3) { ?>
                                <div class="col-xl-6">
                                    <div class="card border-success mb-4">
                                        <div class="card-header text-bg-success">
                                            <i class="fa-solid fa-hand-holding-dollar"></i>
                                            Venta
                                        </div>
                                        <div class="card-body">

                                            <form action="seguimiento.php?id=<?php echo $id ?>" method="post">
                                                <div class="mb-3 row">
                                                    <input type="hidden" name="acction" id="acction" value="SS">
                                                    <input type="hidden" name="id_mensaje" id="id_mensaje" value="<?php echo $id ?>">
                                                    <input type="hidden" name="id_customer" id="id_customer" value="<?php echo $id_customer ?>">
                                                    <label for="inputPassword" class="col-sm-4 col-form-label">Monto de la venta</label>
                                                    <div class="col-sm-8">
                                                        <div class=" input-group">
                                                            <span class="input-group-text"> $</span>
                                                            <input type="text" class="form-control" id="monto" name="monto" <?php if ($idestado == 3) { ?> value="<?php echo $venta['monto'] ?>" disabled readonly<?php } ?>>
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="inputPassword" class="col-sm-4 col-form-label">Descuento</label>
                                                    <div class="col-sm-8">
                                                        <div class=" input-group mb-3">
                                                            <input type="text" class="form-control" <?php if ($idestado == 3) { ?> value="<?php echo $venta['descuento'] ?>" disabled readonly<?php } else { ?>value="0" <?php } ?> id="descuento" name="descuento">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="inputPassword" class="col-sm-4 col-form-label">Total de la venta</label>
                                                    <div class="col-sm-8">
                                                        <div class=" input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="text" class="form-control" id="total" name="total" disabled readonly>
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if ($idestado != 3) { ?>
                                                    <div class=" input-group mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-notes-medical fa-lg"></i></span>
                                                        <textarea class="form-control" placeholder="Comentarios" name="comentario" id="comentario"></textarea>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-primary">Realizar venta</button>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }  ?>
                        </div>
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <!-- calculadora de precio de venta -->
        <script>
            function calcularTotal() {
                var monto = parseFloat(document.getElementById('monto').value) || 0;
                var descuento = parseFloat(document.getElementById('descuento').value) || 0;

                var total = monto - (monto * (descuento / 100));

                document.getElementById('total').value = total.toFixed(2);
            }

            document.getElementById('monto').addEventListener('input', calcularTotal);
            document.getElementById('descuento').addEventListener('input', calcularTotal);

            calcularTotal();
        </script>
        <script>
            let cancelarModal = document.getElementById('cancelarModal')
            cancelarModal.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                let buttonCancelar = cancelarModal.querySelector('.modal-footer #btn-cancela')
                msgCancela = cancelarModal.querySelector('.modal-body #motCancela')
                buttonCancelar.value = id
            })

            function addCliente() {

                let buttonCancelar = document.getElementById('btn-cancela')
                let id = buttonCancelar.value
                let msg = msgCancela.value

                let url = 'clases/addCliente.php'
                let formData = new FormData()
                formData.append('action', 'cancelar')
                formData.append('id', id)
                formData.append('msg', msg)

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