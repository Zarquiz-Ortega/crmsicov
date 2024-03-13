<?php
/*
Script: calendario para el CRM de SICOV  
autor: Zarquiz ortega 
fecha: 22-09-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';
require 'clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (isset($_SESSION['user']['user_name'])) {
    if (!empty($_POST)) {
        $nomEmpresa  = $_POST['nomEmpresa'];
        $fechaHora = $_POST['fechaHora'];
        $det_cita = $_POST['detCita'];
        $usuario = $_POST['user'];
        $classname = $_POST['classname'];


        if (esNulo([$fechaHora, $det_cita,])) {
            $errors[] = "Deve llenar todos los campos.";
        }

        if (count($errors) == 0) {
            if (reguistraCita([$nomEmpresa, $usuario, $fechaHora, $det_cita, $classname], $con)) {
            } else {
                $errors[] = "Ocurrio un error inesperado";
            }
        }
    }
    $id_user = $_SESSION['user']['user_id'];
    //consulta Mis citas
    $sql = $con->prepare("SELECT id_cita, nom_empresa , fecha_hora, det_cita, classname FROM  citas  WHERE id_user = ? ");
    $sql->execute([$id_user]);
    $citas = $sql->fetchAll(PDO::FETCH_ASSOC);

    //consulta Mis Seguimientos
    $sqlSeguimientos = $con->prepare("SELECT `seguimiento`.`id_seguimiento`, `seguimiento`.`id_mensaje`, `customers`.`nom_empresa`, `mensajes`.`id_estado`, `estados`.`estado` FROM `estados` LEFT JOIN `mensajes` ON `mensajes`.`id_estado` = `estados`.`id` LEFT JOIN `seguimiento` ON `seguimiento`.`id_mensaje` = `mensajes`.`id` LEFT JOIN `customers` ON `mensajes`.`id_customers` = `customers`.`id` WHERE seguimiento.id_user = ? AND mensajes.id_estado <= 2 ");
    $sqlSeguimientos->execute([$id_user]);
    $seguimientos = $sqlSeguimientos->fetchAll(PDO::FETCH_ASSOC);

    $fecha = obtenerFecha();
    $fecha_inicial = $fecha . " 00:00:00";
    $fecha_final = $fecha . " 23:59:59";
    //consulta citas por dia
    try {
        $citaspordiasql = $con->prepare("SELECT nom_empresa , fecha_hora, det_cita, classname FROM  citas  WHERE id_user = ? AND  fecha_hora BETWEEN ? AND ?");
        $citaspordiasql->execute([$id_user, $fecha_inicial, $fecha_final]);
        $citaspordia = $citaspordiasql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
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
    <title>SICOV | Calendario</title>
    <!--Favicon-->
    <link rel="shortcut icon" href="img/sicovLogo.ico">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.css" rel="stylesheet" />
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
                    <h1 class="mt-4">Calendario de citas</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Calendario</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-9">
                                    <h6>Agendar un acita y visualizar citas pendiendes</h6>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex align-items-end flex-column">
                                        <a id="añadir" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#Modal"><i class="fa-regular fa-calendar-plus"></i> Agendar una cita</a>
                                    </div>
                                </div>
                            </div>
                            <?php mostrarErrors($errors); ?>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Agendar una cita</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="classname" name="classname" placeholder="Categoria">
                                                <option selected>Selecione una opcion</option>
                                                <option value="urgente">Urgente</option>
                                                <option value="importante">Importante</option>
                                                <option value="pendiente">Pendiente</option>
                                            </select>
                                            <label for="detCita">Categoria</label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="hidden" value="<?php echo $id_user; ?>" id="user" name="user">
                                            <label class="input-group-text" for="nomEmpresa">Cliente</label>
                                            <select class="form-select" id="nomEmpresa" name="nomEmpresa">
                                                <option selected>Selecione una opcion</option>
                                                <?php foreach ($seguimientos as $datosseg) {
                                                    $nombre = $datosseg['nom_empresa'];
                                                ?>
                                                    <option value="<?php echo $nombre ?>"><?php echo $nombre ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="datetime-local" class="form-control" id="fechaHora" name="fechaHora" placeholder="fecha y hora">
                                            <label for="fechaHora">fecha y hora</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="detCita" name="detCita" placeholder="Asunto">
                                                <option selected>Selecione una opcion</option>
                                                <option value="Llamada">Llamada</option>
                                                <option value="Reunion">Reunion</option>
                                                <option value="Conferencia">Conferencia</option>
                                                <option value="Videollamada">Videollamada</option>
                                                <option value="Visita a la empresa">Visita a la empresa</option>
                                            </select>
                                            <label for="detCita">Asunto</label>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" id="btn-cancela" class="btn btn-success">Agendra</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-bg-dark">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    Calendario
                                </div>
                                <div class="card-body">
                                    <div style="color: black;" id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header text-bg-primary">
                                    <i class="fa-regular fa-calendar-check"></i>
                                    Citas pendientes
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php
                                        foreach ($citaspordia as $dia) { ?>
                                            <li class="list-group-item">
                                                <?php echo $dia['fecha_hora']; ?><br>
                                                <?php echo $dia['det_cita']; ?><br>
                                                <?php echo $dia['nom_empresa']; ?>
                                            </li>
                                        <?php }  ?>
                                    </ul>
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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js"></script>
    <style>
        .urgente {
            background-color: red;
            border: red;
            color: white;
        }

        .urgente:hover {
            color: red;
        }

        .importante {
            background-color: green;
            border: green;
            color: white;
        }

        .importante:hover {
            color: green;
        }

        .pendiente {
            background-color: blue;
            color: white;
            border: blue;
        }

        .pendiente:hover {
            color: blue;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'UTC',
                themeSystem: 'bootstrap5',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                weekNumbers: true,
                dayMaxEvents: true,

                events: [
                    <?php foreach ($citas as $cita) { ?> {
                            id: <?php echo json_encode($cita['id_cita']) ?>,
                            title: <?php echo json_encode($cita['det_cita']) ?>,
                            start: <?php echo json_encode($cita['fecha_hora']) ?>,
                            description: <?php echo json_encode($cita['nom_empresa']) ?>,
                            className: <?php echo json_encode($cita['classname']) ?>,
                        },
                    <?php } ?>
                ]
            });
            calendar.render();
        });
    </script>


</body>

</html>