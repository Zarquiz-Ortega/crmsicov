<?php
require '../config/config.php';
require '../config/database.php';

//conecion a la base de datos
$db = new Database();
$con = $db->conectar();

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'];
$apellido = $data['apellido'];
$telefono = $data['telefono'];
$nom_empresa = $data['nom_empresa'];
$email = $data['email'];
$medio_contacto = $data['medio_contacto'];
$comentario = $data['comentario'];
$fecha = obtenerFecha();
$id_user = $_SESSION['user']['user_id'];

$datosSeleccionados = $data['datosSeleccionados'];
$count = count($datosSeleccionados);
$modulos = '';
for ($i = 0; $i < $count; $i++) {
    $etiqueta =  implode('', $datosSeleccionados[$i]);
    $item = strip_tags($etiqueta);
    if ($modulos != '') {
        $modulos .= ','. $item;
    } else {
        $modulos = $item;
    }
}

$id_customer = reguistracliente([$nombre, $apellido, $telefono, $nom_empresa, $email], $con);

if ($id_customer > 0) {
    $id_mensaje = reguistraMensaje([$comentario, $modulos, 'Cotización', $fecha, $id_customer, 1, 1, $medio_contacto], $con);
    if ($id_mensaje > 0) {
        if (reguistraSeguimiento([$id_user, $id_mensaje], $con)) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);

function reguistracliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO customers (nombres, apellidos, telefono, nom_empresa, email) VALUES (?, ?, ?, ?, ?)");
    if ($sql->execute($datos)) {
        $id_customers = $con->lastInsertId();
        return $id_customers;
    }
    return 0;
}

function reguistraMensaje(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO mensajes (mensaje, modulos, asunto, fecha, id_customers, id_estado, enseguimiento, medio_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($sql->execute($datos)) {
        $id_mensaje = $con->lastInsertId();
        return $id_mensaje;
    }
    return 0;
}
function reguistraSeguimiento(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO seguimiento (id_user, id_mensaje) VALUES (?, ?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function obtenerFecha()
{
    $fecha_actual = date("Y-m-d H:i:s");
    return $fecha_actual;
}
