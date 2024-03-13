<?php
/*
Script: Plantilla para el CRM de SICOV  
autor: Zarquiz ortega 
fecha: 22-09-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';
require 'clases/userFunciones.php';

//areglos para los mensajes
$errors = [];
$mensajes = [];

$db = new Database();
$con = $db->conectar();

if (isset($_SESSION['user']['user_name'])) {
    if (!empty($_POST)) {
        if (isset($_POST['submit'])) {
            $nombre = $_POST['Nombre'];
            $apellido = $_POST['apellido'];
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
            $rep_password = $_POST['rep_password'];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            if (esNulo([$nombre, $apellido, $user_name, $password, $rep_password])) {
                $errors[] = "Deve llenar todos los campos.";
            } else {
                if ($password !== $rep_password) {
                    $errors[] = "Las contraseñas no coinciden.";
                } else {
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $nombre_temporal = $_FILES['imagen']['tmp_name'];
                        $nombre_archivo = $_FILES['imagen']['name'];
                        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                        $nuevo_nombre = $nombre . '_' . $apellido . '.' . $extension;
                        $ruta_destino = 'img/usuarios/' . $nuevo_nombre;


                        if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                            if (reguistraUsuario([$nombre, $apellido, $user_name, $pass_hash, $nuevo_nombre], $con)) {
                                $mensajes[] = "Usuario ". $user_name ." reguistrado Corectamente";
                            }
                        } else {

                            $errors[] = "Error al subir la imagen.";
                        }
                    } else {

                        $errors[] = "Error al cargar la imagen.";
                    }
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
    <title>SICOV | Usuarios</title>
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
        <?php include 'sidebard.php';?>

        <div id="layoutSidenav_content" <?php echo ESTILO_CONTENT ?>>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Agregar usuario</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nuevo usuario</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            Formulario para dar de alta a un nuevo usuario.
                            <?php mostrarErrors($errors); ?>
                            <?php mostrarMensajes($mensajes); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <i class="fa-solid fa-database"></i>
                                    Datos del usuario
                                </div>
                                <div class="card-body">
                                    <form action="agregaUsuario.php" method="post" enctype="multipart/form-data">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-n"></i></span>
                                            <input type="text" class="form-control" placeholder="Nombre" name="Nombre" id="nombre" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-a"></i></span>
                                            <input type="text" class="form-control" placeholder="apellido" name="apellido" id="apellido" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag "></i></span>
                                            <input type="text" class="form-control" placeholder="Nombre de usuario" name="user_name" id="user_name" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key fa-sm"></i></span>
                                            <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" class="form-control" placeholder="Repita Contraseña" name="rep_password" id="rep_password" required>
                                        </div>
                                        
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <i class="fa-solid fa-photo-film"></i>
                                    Fotografia del usuario
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="img/user.png" class="img-fluid" id="foto" style="max-width:200px; ">
                                    </div>
                                    <div class="mb-3">
                                        <input class="form-control" type="file" name="imagen" id="inputFoto" onchange="mostrarFoto()" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto mb-4">
                            <button name="submit" class="btn btn-success" type="submit"><i class="fa-solid fa-user-plus"></i> Agregar usuario</button>
                        </div>
                        </form>
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
        function mostrarFoto() {
            var inputFoto = document.getElementById('inputFoto');
            var foto = document.getElementById('foto');

            if (inputFoto.files && inputFoto.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    foto.src = e.target.result;
                };
                reader.readAsDataURL(inputFoto.files[0]);
            }
        }
    </script>
</body>

</html>