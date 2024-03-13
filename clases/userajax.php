<?php

require_once '../config/database.php';
require_once 'clienteFunciones.php';


$datos = [];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectar();

    switch ($action) {

        case 'existeUsuario':
           // $datos['ok'] = usuarioExiste($_POST['usuario'], $con);
            break;

        case 'existeEmail':
           // $datos['ok'] = emailExiste($_POST['email'], $con);
            break;

        case 'existeMatricula':
           // $datos['ok'] = matriculaExiste($_POST['matricula'], $con);
            break;
        case 'coincidirPassword':
         //   $datos['ok'] = validaPassword($_POST['password'],$_POST['repassword'], $con);
    }
}

echo json_encode($datos);
