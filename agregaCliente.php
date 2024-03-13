<?php
/*
Script: formulario para agregar un nuevo cliente al CRM de SICOV  
autor: Zarquiz ortega 
fecha: 25-09-2023
*/

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/userFunciones.php';

//conecion a la base de datos
$db = new Database();
$con = $db->conectar();



//consulta  de conteo de estdos 
$sql = $con->prepare("SELECT id, nombre FROM modulos");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_ASSOC);


if (isset($_SESSION['user']['user_name'])) {
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
    <title>SICOV | Agregar cliente</title>
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
                    <h1 class="mt-4">Agregar un cliente</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nuevo cliente</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            Formulario para agregar un nuevo cliente

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card border-success mb-4">
                                <div class="card-header text-bg-success">
                                    <i class="fa-solid fa-user-plus fa-sm"></i> Datos del cliente
                                </div>
                                <div class="card-body">
                                    <div id="alerta_nulo" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;"></div>
                                    <div id="alerta_telefono" class="alert alert-danger" role="alert" style="display: none;"></div>
                                    <div id="alerta_email" class="alert alert-danger" role="alert" style="display: none;"></div>
                                    <form action="agregaCliente.php" method="post" id="cliente">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i></span>
                                            <input type="text" class="form-control" placeholder="Nombre" name="Nombre" id="nombre">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i></span>
                                            <input type="text" class="form-control" placeholder="Apellido" name="Apellido" id="apellido">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i></span>
                                            <input type="text" class="form-control" placeholder="Telefono" name="Telefono" id="telefono">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i></span>
                                            <input type="text" class="form-control" placeholder="Nombre de la empresa" name="nom_empresa" id="nom_empresa">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i></span>
                                            <input type="text" class="form-control" placeholder="Correo electronico" name="email" id="email">
                                            <span class="input-group-text" id="basic-addon2">@ejemplo.com</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" name="contacto" id="contacto" for="inputGroupSelect01">Medio de contacto</label>
                                            <select class="form-select" id="inputGroupSelect01">
                                                <option selected>seleccione una opcion</option>
                                                <option value="1">Llamada</option>
                                                <option value="2">Correo</option>
                                                <option value="2">Conferencias</option>
                                            </select>
                                        </div>
                                        <i><b>Nota: </b> Los campos con asterisco (<span> <i class="fa-solid fa-asterisk fa-sm" style="color: #d50000;"></i> </span>) son obligatorios </i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card border-primary mb-4">
                                <div class="card-header text-bg-primary">
                                    <i class="fa-solid fa-list"></i>
                                    Cotización
                                </div>
                                <div class="card-body">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" name="modulos" for="modulos">Modulos</label>
                                        <select class="form-select" id="modulo">
                                            <option selected>seleccione una opción</option>
                                            <?php foreach ($row as $modulo) {
                                                $id = $modulo['id'];
                                            ?>
                                                <option value="<?php echo $id ?>"><?php echo $modulo['nombre'] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-success" onclick="addProducto()"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                    <hr>
                                    <ul class="list-group" id="content"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-dark mb-4">
                        <div class="card-header text-bg-dark">
                            <i class="fa-solid fa-comments"></i> comentarios (opcional)
                        </div>
                        <div class="card-body">
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-notes-medical fa-lg"></i></span>
                                <textarea class="form-control" placeholder="Comentarios" name="Comentarios" id="comentario"></textarea>
                            </div><br>
                            <div class="text-center">
                                <button type="button"class="btn btn-success" onclick="validarFormulario()"><i class="fa-solid fa-user-plus fa-sm"></i> Agregar</button>
                            </div>
                            </form>
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
        let datosSeleccionados = [];

        function addProducto() {
            let id = document.getElementById('modulo').value;
            let content = document.getElementById("content");
            let select = document.getElementById("modulo");
            let url = 'clases/cliente.php';
            let formData = new FormData();
            formData.append('id', id);

            select.querySelector(`option[value="${id}"]`).disabled = true;
            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json()).then(data => {
                content.innerHTML += data.data;
                datosSeleccionados.push(data.data);
            }).catch(err => console.log(err))
        }

        function validarFormulario() {
            var alerta_nulo = document.getElementById('alerta_nulo');
            var alerta_telefono = document.getElementById('alerta_telefono');
            var alerta_email = document.getElementById('alerta_email');
            alerta_nulo.style.display = 'none'; 
            alerta_telefono.style.display = 'none'; 
            alerta_email.style.display = 'none'; 

            var nombre = document.getElementById('nombre').value;
            var apellido = document.getElementById('apellido').value;
            var telefono = document.getElementById('telefono').value;
            var nom_empresa = document.getElementById('nom_empresa').value;
            var email = document.getElementById('email').value;
            var error = 0

            if (nombre.trim() === '' || apellido.trim() === '' || telefono.trim() === '' || nom_empresa.trim() === '' || email.trim() === '') {
                alerta_nulo.innerHTML = 'Todos los campos son obligatorios.';
                alerta_nulo.style.display = 'block';
                error += 1
            } else {
                if (!/^55|56\d{8}$/.test(telefono)) {
                    alerta_telefono.innerHTML = 'El teléfono debe comenzar con 55 o 56 y contener 10 dígitos numéricos.';
                    alerta_telefono.style.display = 'block'; // Muestra la alerta
                    error += 1
                }

                // Validar que el correo electrónico sea válido
                if (!/^\S+@\S+\.\S+$/.test(email)) {
                    alerta_email.innerHTML = 'El correo electrónico no es válido.';
                    alerta_email.style.display = 'block'; // Muestra la alerta
                    error += 1

                }
            }

            if (error == 0) {
                guardarDatosEnBaseDeDatos()
            }

        }

        function guardarDatosEnBaseDeDatos() {
            let nombre = document.getElementById('nombre').value;
            let apellido = document.getElementById('apellido').value;
            let telefono = document.getElementById('telefono').value;
            let nom_empresa = document.getElementById('nom_empresa').value;
            let email = document.getElementById('email').value;
            let medio_contacto = document.getElementById('inputGroupSelect01').value;
            let comentario = document.getElementById('comentario').value;

            let formData = {
                nombre: nombre,
                apellido: apellido,
                telefono: telefono,
                nom_empresa: nom_empresa,
                email: email,
                medio_contacto: medio_contacto,
                datosSeleccionados: datosSeleccionados,
                comentario: comentario
            };


            fetch('clases/nuevoCliente.php', {
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers: {
                        'Content-Type': 'application/json'
                    },
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        window.location.href = 'customers.php';
                    }
                }).catch(err => console.log(err));
        }
    </script>
</body>

</html>