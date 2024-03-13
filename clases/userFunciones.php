<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) { 
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function esTelefono($telefono)
{
    if (preg_match("/^([0-9](10))$/", $telefono)) {
        return true;
    }
    return false;
}

function esNacional($telefono)
{
    if (preg_match("/^(55|56)[0-9]{8}$/", $telefono)) {
        return true;
    }
    return false;
}

function obtenerFecha()
{
    date_default_timezone_set('America/Mexico_City');
    $fecha_actual = date("Y-m-d");
    return $fecha_actual;
}

function mostrarErrors(array $erros)
{
    if (count($erros) > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><dl>';
        foreach ($erros as $error) {
            echo '<dd>' . $error . '</dd>';
        }
        echo '</dl>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}
function mostrarMensajes(array $mensajes)
{
    if (count($mensajes) > 0) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><dl>';
        foreach ($mensajes as $mensajes) {
            echo '<dd>' . $mensajes . '</dd>';
        }
        echo '</dl>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

function registraCustomers(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO customers (nombres, apellidos, telefono, nom_empresa, email) VALUES (?,?,?,?,?)");

    if ($sql->execute($datos)) {

        return $con->lastInsertId();
    }
}
return 0;

function reguistroMensaje(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO mensajes (mensaje, modulos, asunto, fecha, id_customers, id_estado) VALUES (?,?,?,?,?,?)");

    if ($sql->execute($datos)) {

        return $con->lastInsertId();
    }
}


function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT id, nombres, apellidos, usuario, password, rol FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user']['user_id'] = $row['id'];
            $_SESSION['user']['user_name'] = $row['usuario'];
            $_SESSION['user']['name'] = $row['nombres'];
            $_SESSION['user']['last_name'] = $row['apellidos'];
            $_SESSION['user']['user_rol'] = $row['rol'];
            header("location: dashboard.php");
        }
    }
    return 'El usuario y/o contraseña son incorrectos';
}

function reguistarSeguimiento($id, $comentario, $acction, $con)
{
    if ($acction == 'NC') {
        $sql = $con->prepare("UPDATE seguimiento SET com_seg_1 = ? WHERE id_mensaje = ?");
        if ($sql->execute([$comentario, $id])); {
            $num_estado = 2;
        }
    }
    if ($acction == 'SS') {
        $sql = $con->prepare("UPDATE seguimiento SET com_seg_2 = ? WHERE id_mensaje = ?");
        if ($sql->execute([$comentario, $id])); {
            $num_estado = 3;
        }
    }
    if (actualisaEstado($id, $num_estado, $con)) {
        return true;
    } else {
        return false;
    }
}


function reguistraVenta(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO venta (monto, descuento, fecha, id_customer, id_user, id_mensaje) VALUES (?,?,?,?,?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function actualisaEstado($id, $num_estado, $con)
{
    $sql = $con->prepare("UPDATE mensajes SET id_estado = ? WHERE id = ?");
    if ($sql->execute([$num_estado, $id])); {
        return true;
    }
}

function reguistraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (nombres, apellidos, usuario, password, nom_userFoto) VALUES (?,?,?,?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function reguistraCita(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO citas (nom_empresa, id_user, fecha_hora, det_cita, classname) VALUES (?,?,?,?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function actualizaPassword($user_id, $password, $con)
{
    $sql = $con->prepare(" UPDATE usuarios SET password=? WHERE id = ?");
    if ($sql->execute([$password, $user_id])) {
        return true;
    }
    return false;
}

function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}