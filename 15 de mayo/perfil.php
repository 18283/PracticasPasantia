<!DOCTYPE html>
<html lang="es">
<?php
include("conexion.php");

$usuario = null;
$pass = null;
$CI = null;
$email = null;
$foto = null;
$imagenAnonimo = 'anonimo.jpg';


if (isset($_GET['editar'])) {
    $editar_id = $_GET['editar'];
    $consulta = "SELECT BADGENUMBER, NAME, PASSWORD, EMail, PHOTO FROM USERINFO WHERE BADGENUMBER = '7826355'";
    $params = array($editar_id);
    $ejecutar = sqlsrv_query($conn, $consulta, $params);
    $fila = sqlsrv_fetch_array($ejecutar);
    
    $usuario = $fila['NAME'];
    $pass = $fila['PASSWORD'];
    $CI = $fila['BADGENUMBER'];
    $email = $fila['EMail'];
    $foto = $fila['PHOTO'];
}

if (isset($_POST['guardar_foto'])) {
  if (isset($_FILES['photoInput']) && $_FILES['photoInput']['error'] == 0) {
      $fileType = $_FILES['photoInput']['type'];
      
      if (strpos($fileType, 'image') === false) {
          echo "<script>alert('El archivo debe ser una imagen.');</script>";
          return;
      }

      $foto = file_get_contents($_FILES['photoInput']['tmp_name']); // Obtener contenido del archivo

      // Actualizar foto en la base de datos
      $updateConsulta = "UPDATE USERINFO SET PHOTO = ? WHERE BADGENUMBER = ?";
      $params = array($foto, $editar_id);
      $ejecutar = sqlsrv_query($conn, $updateConsulta, $params);
      
      if ($ejecutar) {
          echo "<script>alert('Foto actualizada correctamente.');</script>";
      } else {
          // Obtiene los errores
          $errors = sqlsrv_errors();
          foreach ($errors as $error) {
              echo "<script>alert('Error: " . $error['message'] . "');</script>";
          }
      }
  } else {
      echo "<script>alert('Error al cargar la imagen.');</script>";
  }
}

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
    <title>SISTEMA DE MARCACION</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="estilo.css" rel="stylesheet">
</head>
<body>
  <!-- BARRA DE NAVEGACION TOP INICIO -->
  <nav class="navbar navbar-expand-lg my-4 py-4 navbar-light shadow-sm" style="background-color:#b19146;">
    <div class="container-fluid">
        <a class="navbar-left" href="#"><img src="logo1.png" alt="logo" width="250px"></a>
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
                        <li><a class="dropdown-item text-dark fs-4" href="editar.php">EDITAR</a></li>
                        <li><a class="dropdown-item text-dark fs-4" href="buscar.php">BUSCAR</a></li>
                        <li><a class="dropdown-item text-dark fs-4" href="formulario.php">INSERTAR</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item text-white">
                    <a class="nav-link text-white h1" href="index.html" onclick="return confirm('¿ESTAS ? DEGURO QUE QUIERES SALIR DE LA PAGINA');">CERRAR</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>
<!-- BARRA DE NAVEGACION TOP FIN -->


    <div class="container text-white">

   
    <?php 
// Ruta de la imagen de usuario anónimo


if (isset($foto)) {
    // Si hay foto, mostrarla
    echo '<div style="text-align: center;">';
    echo '<img height="300px" src="data:image/jpeg;base64,' . base64_encode($foto) . '" />';
    echo '</div>';
} else {
    // Si no hay foto, mostrar imagen de usuario anónimo
    echo '<div style="text-align: center;">';
    echo '<img height="300px" src="' . $imagenAnonimo . '" alt="Usuario Anónimo" />';
    echo '</div>';
}
?>
<div style="text-align:center;">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#photoModal">EDITAR FOTO</button>
</div>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario) ?>"><br />
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="text" name="passw" class="form-control" value="<?= htmlspecialchars($pass) ?>"><br />
            </div>
            <div class="form-group">
                <label>Cédula de Identidad</label>
                <input type="text" name="CI" class="form-control" value="<?= htmlspecialchars($CI) ?>"><br />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>"><br />
            </div>
            <div class="form-group">
                <input type="submit" name="actualizar" class="btn btn-warning" value="ACTUALIZAR DATOS"><br />
            </div>
        </form>
    </div>

    <!-- Modal para actualizar foto -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">Actualizar Foto de Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="photoInput" class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="guardar_foto" class="btn btn-primary">Guardar Foto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php


if (isset($_POST['actualizar'])) {

  $actualizar_nombre = $_POST['nombre'];
  $actualizar_pass = $_POST['passw'];
  $actualizar_CI = $_POST['CI'];
  $actualizar_email = $_POST['email'];

  $consulta = "UPDATE USERINFO SET BADGENUMBER = '$actualizar_CI', NAME ='$actualizar_nombre', PASSWORD='$actualizar_pass ', EMail='$actualizar_email' 
  WHERE  BADGENUMBER='$editar_id'";
  $ejecutar = sqlsrv_query($conn, $consulta);
  $fila = sqlsrv_fetch_array($ejecutar);


  if ($ejecutar) {
    echo "<script> alert('Datos actualizados')</script>";
    echo "<script> window.open('formulario.php', '_self')</script>";

  }
}

?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
