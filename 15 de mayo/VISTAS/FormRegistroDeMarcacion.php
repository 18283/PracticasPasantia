<!--<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, user-scalable=no,
	initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">

    <title>CRUD PHP & SQL SERVER</title>
    
     <link href="../css/bootstrap.css" rel="stylesheet">
     <link href="../css/reboost.css" rel="stylesheet" type="text/css">
    <link href="../css/base.css" rel="stylesheet">
    <link href="../css/stiloUser" rel="stylesheet"> 
    <link href="../css/estilo.css" rel="stylesheet" type="text/css">

</head>-->
 <!-- BARRA DE NAVEGACION TOP INICIO 
    <nav class="navbar navbar-expand-lg my-4 py-4 navbar-light shadow-sm" style="background-color:#b19146;">
        <div class="container-fluid">
            <a class="navbar-left" href="#"><img src="../Recursos/logo1.png" alt="logo" width="250px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active text-white h1" href="mostrar.php">INICIO</a></li>
                    <li class="nav-item"><a class="nav-link active text-white h1" href="contacto.php">CONTACTO</a></li>
                    <li class="nav-item"><a class="nav-link active text-white h1" href="usuarios.php">USUARIOS</a></li>
					<li class="nav-item"><a class="nav-link active text-white h1" href="FormPersonalDeDepartamento.php">DEPARTAMENTOS</a></li>
					<li class="nav-item"><a class="nav-link active text-white h1" href="FormRegistroDeMarcacion.php">MARCACIONES</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white h1" href="#" id="navbarDropdown" data-bs-toggle="dropdown">SERVICIOS</a>
                        <ul class="dropdown-menu text-white" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item text-dark fs-4" href="editar.php">EDITAR</a></li>
                            <li><a class="dropdown-item text-dark fs-4" href="buscar.php">BUSCAR</a></li>
                            <li><a class="dropdown-item text-dark fs-4" href="formulario.php">INSERTAR</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item text-white">
                        <a class="nav-link text-white h1" href="../index.html" onclick="return confirm('¿ESTAS ? DEGURO QUE QUIERES SALIR DE LA PAGINA');">CERRAR</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
 BARRA DE NAVEGACION TOP FIN -->

 <?php
require_once '../CONTROLADOR/navBar.php';

$items = [
    "Inicio" => "../VISTAS/mostrar.php",
    "Contacto" => "../VISTAS/contacto.php",
    "Usuarios" => "../VISTAS/usuarios.php",
    "Cerrar sesión" => "../index.html"
	
];

$barra = new BarraNavegacion($items);
$barra->render();
?>
<?php

echo '<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Registros de Marcación</title>
</head>
<body>
    <h2>Consultar Registros de Marcación</h2>
    <form action="RegistrosDeMarcacionEntreFechas.php" method="post">
        <label>CI:</label>
        <input type="text" name="ci" required><br><br>

        <label>Fecha de Inicio:</label>
        <input type="date" name="fechaInicio" required><br><br>

        <label>Fecha de Fin:</label>
        <input type="date" name="fechaFin" required><br><br>

        <input type="submit" value="Buscar">
    </form>
</body>
</html>';
?>