<?php

require '../config/config.php';
require '../config/database.php';

$db = new Database();
$con = $db->conectar();

$output = [];
$output['data'] = [];

if (isset($_POST['id'])) {

    $id = $_POST['id'];

    //consulta  de conteo de estdos 
    $sql = $con->prepare("SELECT nombre FROM modulos WHERE id LIKE ? LIMIT 1");
    $sql->execute([$id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if (isset($_SESSION['carrito']['productos'][$id])) {
        $_SESSION['carrito']['productos'][$id] += 1;
    } else {
        $_SESSION['carrito']['productos'][$id] = 1;
    }
    $nombre = $row['nombre'];
    $output['data'][]= '<li class="list-group-item">'. $nombre.'</li>';

    
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
