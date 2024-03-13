<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/userFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $errors[] = "Deve llenar todos los campos.";
    }

    if (count($errors) == 0) {

        $errors[] =  login($usuario, $password, $con);
    }
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
    <title>SICOV | Login</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!--Favicon-->
    <link rel="shortcut icon" href="img/sicovLogo.ico">
    <style>
        .logo {
            width: 100px !important;
            height: auto;
        }
    </style>
</head>

<body style="background-image: linear-gradient(to top, #48c6ef 0%, #6f86d6 100%);">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 align-items-center">
                            <div class="card shadow-lg border-0 rounded-4 mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><img class="logo" src="img/logoSicov.png"> <span style=" font-family: 'nasalization', sans-serif;">SICOV </span><span class="badge text-bg-dark">CRM</span></h3>
                                </div>
                                <div class="card-body">
                                    <?php mostrarErrors($errors); ?>

                                    <form action="index.php" method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="usuario" id="usuario" type="usuario" placeholder="Username" />
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="password" type="password" placeholder="contraseña" />
                                            <label for="password">Contraseña</label>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Ingresar</button>
                                        </div>
                                    </form>
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
</body>

</html>