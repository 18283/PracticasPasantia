
<!DOCTYPE html>

<?php

include("conexion.php");
?>



<meta charset="UTF-8">
<html>
<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, user-scalable=no,
	initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">

	<title>CRUD PHP & SQL SERVER</title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="estilo.css">


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
                <li class="nav-item"><a class="nav-link active text-white h1" href="mostrar.php">INICIO</a></li>
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
                    <a class="nav-link text-white h1" href="../index.html" onclick="return confirm('¿ESTAS ? DEGURO QUE QUIERES SALIR DE LA PAGINA');">CERRAR</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>
<!-- BARRA DE NAVEGACION TOP FIN -->

<footer class="py-5" style="background-color: #b19146; color: white;">
  <div class="container">
    <div class="row">
      <!-- Detalles de la Compañía -->
      <div class="col-md-3">
        <h5 class="text-white">Detalles de la Compañía</h5>
        <hr style="border-color: #ffffff;">
        <p>
          <strong>ALEXA.NET Cooperation</strong><br>
          Calle VALLEGRANDE 8671<br>
          SUR AMÉRICA<br>
          Código Postal: 0000<br><br>
          <strong>Tel:</strong> 75692051<br>
          <strong>Fax:</strong> 78945 3698521470<br>
          <strong>Email:</strong> 
          <a href="mailto:alexa_cooperation@gmail.com" class="text-decoration-none text-white">
            alexa_cooperation@gmail.com
          </a><br><br>
          <strong>Horarios de Atención:</strong><br>
          Lunes - Viernes: 08:00 - 17:30<br>
          Sábados: 08:00 - 13:00
        </p>
      </div>

      <!-- Redes Sociales -->
      <div class="col-md-3">
        <h5 class="text-white">Redes Sociales</h5>
        <hr style="border-color: #000000;">
        <p>
          <a href="#" class="d-flex align-items-center text-white text-decoration-none mb-2">
            <img src="../Recursos/linkedin.png" alt="LinkedIn" class="me-2" style="width: 45px; background-color: #000000; padding: 2px;"> 
            ALEXA Official
          </a>
          <a href="#" class="d-flex align-items-center text-white text-decoration-none mb-2">
            <img src="../Recursos/twitter.png" alt="Twitter" class="me-2" style="width: 45px; background-color: #000000; padding: 2px;"> 
            ALEXA_Official
          </a>
          <a href="#" class="d-flex align-items-center text-white text-decoration-none mb-2">
            <img src="../Recursos/instagram.png" alt="Pinterest" class="me-2" style="width: 45px; background-color: #000000; padding: 2px;"> 
            Fotos de Instagram
          </a>
          <a href="#" class="d-flex align-items-center text-white text-decoration-none">
            <img src="../Recursos/ubicacion.png" alt="Feed" class="me-2" style="width: 45px; background-color: #000000; padding: 2px;"> 
            Ubicación
          </a>
        </p>
      </div>

      <!-- Blog -->
      <div class="col-md-3">
        <h5 class="text-white">Nuestro Blog</h5>
        <hr style="border-color: #ffffff;">
        <p><strong>Soluciones Tecnológicas</strong></p>
        <p class="text-light">Por: Tecky y 7.416 personas más</p>
        <p>Muy buena atención y el trabajo lo hacen bien programado y seguro.
          <a href="#" class="text-decoration-none text-white">Leer completo »</a>
        </p>
        <p><strong>Nuevas Ideas</strong></p>
        <p class="text-light">Por: Andrew y 4.988 personas más</p>
        <p>Trabajamos para mejorar la seguridad de datos de nuestros clientes.
          <a href="#" class="text-decoration-none text-white">Leer completo »</a>
        </p>
      </div>

      <!-- Contacto -->
      <div class="col-md-3">
        <h5 class="text-white">Contáctanos</h5>
        <hr style="border-color: #ffffff;">
        <form>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Nombre" style="background-color: #fdf6e3; border: none;" required>
          </div>
          <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email" style="background-color: #fdf6e3; border: none;" required>
          </div>
          <div class="mb-3">
            <textarea class="form-control" rows="3" placeholder="Mensaje" style="background-color: #fdf6e3; border: none;" required></textarea>
          </div>
          <button type="submit" class="btn btn-light text-dark">Enviar</button>
        </form>
      </div>
    </div>

    <div class="text-center mt-4">
      <p class="mb-0">&copy; 2024 ALEXA. All Rights Reserved.</p>
      <p class="text-white-50">ALEXA.NET Cooperation</p>
    </div>
  </div>
</footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>