<!DOCTYPE html>

<?php

include("../CONTROLADOR/conexion.php");
?>

<meta charset="UTF-8">

<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, user-scalable=no,
	initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">

	<title>CRUD PHP & SQL SERVER</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">

	<link href="bootstrap.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>




<body>
  
  <!-- BARRA DE NAVEGACION TOP INICIO -->
  <nav class="navbar navbar-expand-lg my-4 py-4 navbar-light shadow-sm" style="background-color:#b19146;">
    <div class="container-fluid">
        <a class="navbar-left" href="#"><img src="../Recursos/logo1.png" alt="logo" width="250px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active text-white h1" href="../VISTAS/mostrar.php">INICIO</a></li>
                <li class="nav-item"><a class="nav-link active text-white h1" href="contacto.php">CONTACTO</a></li>
                <li class="nav-item"><a class="nav-link active text-white h1" href="usuarios.php">USUARIOS</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white h1" href="#" id="navbarDropdown" data-bs-toggle="dropdown">SERVICIOS</a>
                    <ul class="dropdown-menu text-white" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item text-dark fs-4" href="../CONTROLADOR/editar.php">EDITAR</a></li>
                        <li><a class="dropdown-item text-dark fs-4" href="../CONTROLADOR/buscar.php">BUSCAR</a></li>
                        <li><a class="dropdown-item text-dark fs-4" href="../CONTROLADOR/insertar.php">INSERTAR</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item text-white">
                    <a class="nav-link text-white h1" href="../index.html" onclick="return confirm('¿ESTAS ? SEGURO QUE QUIERES SALIR DE LA PAGINA');">CERRAR</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>
<!-- BARRA DE NAVEGACION TOP FIN -->

<div class="fondol d-flex justify-content-center align-items-center" >

    <h1 class="titulo " >BIENVENIDO</h1>

 </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>