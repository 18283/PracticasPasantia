<!DOCTYPE html>

<?php

include("conexion.php");
				$id = null;
				$usuario = null;
				$pass = null;
				$email = null;
        
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

	<link href="../css/estilo.css" rel="stylesheet">

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
                <li class="nav-item"><a class="nav-link active text-white h1" href="../VISTAS/contacto.php">CONTACTO</a></li>
                <li class="nav-item"><a class="nav-link active text-white h1" href="../VISTAS/usuarios.php">USUARIOS</a></li>
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


<div class="col-md-8 col-md-offset-2 text-white">
<h1>INTRODUZCA LA CEDULA DE IDENTIDAD </h1>


<form class="d-flex" method="POST" action="buscar.php">

	<input  type="text" name="busqueda"  placeholder="buscador" class="form-control me-2 w-25" ><br/>
	<button type="submit" name="buscar" class="btn btn-warning" value="BUSCAR"> BUSCAR </button> <br/>

</form>




</div>
<br /><br /><br />

<div class="col-md-8 col-md-offset-2 text-white" >
<table class="table table-bordered table-responsive text-white">

<tr align ="center"	>
	<td>ID</td>
	<td>Usuario</td>
	<td>Password</td>
	<td>Email</td>
	<td>Accion</td>
	<td>Accion</td>
</tr>

<?php 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['busqueda'];

 

    if ($conn) {
        // Consulta para obtener el usuario por ID
        $sql = "SELECT BADGENUMBER,NAME,PASSWORD,EMail FROM userinfo_backup WHERE BADGENUMBER = ?";
        $params = array($userId);

        $stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
           		 $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            	if ($user)
             	{
					$id = $user['BADGENUMBER'];
					$usuario = $user['NAME'];
					$pass = $user['PASSWORD'];
					$email = $user['EMail'];
			 	}

          		 else {
              			  echo "<script> alert('NO SE ENCONTRO UN USUARIO CON ESE ID')</script>";
							echo "<script> window.open('formulario.php', '_self')</script>";
  					  }
           }
     else {
            echo "Error en la consulta.";
            
          }

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

    } 
	
	else {
        echo "Error en la conexión a la base de datos.";

    }


}
 

?>

<tr align ="center"	>

	<td><?php echo $id; ?></td>
	<td><?php echo $usuario; ?></td>
	<td><?php echo $pass; ?></td>
	<td><?php echo $email; ?></td>
		<td> <a class="btn btn-warning" href="editar.php?editar=<?php echo $id; ?>">Editar</a></td>
		
		<td> <a class="btn btn-danger" href="form ulario.php?borrar=<?php echo $id; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este registro?');">Borrar</a></td>
	
</tr>
        
        
</table>
</div>


<?php 
if(isset($_GET['editar'])){
	include ('editar.php');
}
?>


 
<?php

if(isset($_GET['borrar'])){

    $borrar_id= $_GET['borrar'];

    $borrar = "DELETE  FROM userinfo_backup WHERE BADGENUMBER = '$borrar_id'";

    $ejecutar =sqlsrv_query($conn, $borrar);

   if($ejecutar) {
        echo "<script> alert('El usuario ha sido borrado')</script>";
        echo "<script> window.open('formulario.php', '_self')</script>";
    } 
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>