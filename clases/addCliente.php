<?php
require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$action = $_POST['action'];

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$id_user = $_SESSION['user']['user_id'];

if ($action == 'añade') {
    if (actualizaSeguimiento($id, $con)) {
        if (reguistroSeguimiento([$id_user, $id], $con)) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
    } else {
        $datos['ok'] = false;
    }
} else if ($action == 'cancelar') {
    $msg = $_POST['msg'];
    if (cancelaVenta($id, $con)) {
        if (motCancelacion($msg, $id, $con)) {
            if (reguistraCancelacion([$id_user, $id], $con)) {
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
}


echo json_encode($datos);


// funciones de añadir 
function actualizaSeguimiento($id, $con)
{

    $sql = $con->prepare("UPDATE mensajes SET enseguimiento = 1 WHERE id = ?");
    if ($sql->execute([$id])); {
        return true;
    }
    //return false;
}

function reguistroSeguimiento(array $datos, $con)
{

    $sql = $con->prepare("INSERT INTO seguimiento (id_user, id_mensaje ) VALUES (?,?)");

    if ($sql->execute($datos)); {
        return true;
    }
    //return false;
}

// funciones de cancelar
function cancelaVenta($id, $con)
{

    $sql = $con->prepare("UPDATE mensajes SET id_estado = 4 WHERE id = ?");
    if ($sql->execute([$id])); {
        return true;
    }
    //return false;
}

function reguistraCancelacion(array $datos, $con)
{

    $sql = $con->prepare("INSERT INTO cancelacion (id_user, id_mensaje ) VALUES (?,?)");

    if ($sql->execute($datos)); {
        return true;
    }
    //return false;
}

function motCancelacion($msg, $id, $con)
{
    $sql = $con->prepare("UPDATE seguimiento SET com_seg_4 = ? WHERE id_mensaje = ?");
    if ($sql->execute([$msg, $id])); {
        return true;
    }
    //return false;
}
