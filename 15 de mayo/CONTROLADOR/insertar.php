<!DOCTYPE html>
<?php

include("conexion.php");
?>




<meta charset="UTF-8">
<html>

<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
	<title>CRUD PHP & SQL SERVER</title>
	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/estilo.css">

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
							<li><a class="dropdown-item text-dark fs-4" href="insertar.php">INSERTAR</a></li>
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

	<div class="col-md-8 col-md-offset-2 text-white">
		<h1>SISTEMA DE MARCACION </h1>

		<form method="POST" action="insertar.php">

			<div class="form-group">

				<label>Nombre</label>
				<input type="text" name="nombre" class="form-control" placeholder="Escriba su nombre"><br />
			</div>

			<div class="form-group">
				<label>Contraseña</label>
				<input type="text" name="passw" class="form-control" placeholder="Escriba su contraseña"><br />
			</div>

			<div class="form-group">
				<label>Celuda de identidad </label>
				<input type="text" name="CI" class="form-control" placeholder="Cedula de identidad"> <br />
			</div>

			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" class="form-control" placeholder="Escriba su email"><br />
			</div>

			<div class="form-group">
				<input type="submit" name="insert" class="btn btn-warning" value="INSERTAR DATOS"><br />
			</div>



		</form>

		<form class="d-flex" method="POST" action="buscar.php">
			<input type="text" name="busqueda" placeholder="buscador" class="form-control me-2 w-25"><br />
			<button type="submit" name="buscar" class="btn btn-warning" value="BUSCAR"> BUSCAR </button> <br />
		</form>


	</div>


	<br /><br /><br />



	<?php

	if (isset($_POST["insert"])) {

		$usuario = $_POST["nombre"];
		$pass = $_POST["passw"];
		$carnet = $_POST["CI"];
		$email = $_POST["email"];


		$insertar = "INSERT INTO userinfo_backup (BADGENUMBER,NAME, password, email,ATT,INLATE,OUTEARLY,OVERTIME,SEP,HOLIDAY,LUNCHDURATION) VALUES('$carnet','$usuario','$pass','$email',1,1,1,1,1,1,1)";


		$ejecutar = sqlsrv_query($conn, $insertar);

		if ($ejecutar) {

			echo "<script> alert('INSERTADO CORRECTAMENTE')</script>";
		} else {
			echo "<h3>la consulta no fue realizada correctamente </h3>";
		}
	}

	if (isset($_POST['buscar'])) {
		include('buscar.php');
	}

	?>


	<div class="col-md-8 col-md-offset-2 text-white">
		<table class="table table-bordered table-responsive text-white">

			<tr align="center">
				<td>ID</td>
				<td>Usuario</td>
				<td>Password</td>
				<td>Email</td>
				<td>Accion</td>
				<td>Accion</td>
			</tr>

			<?php

			$consulta = "select BADGENUMBER,NAME,PASSWORD,EMail from userinfo_backup";
			$ejecutar = sqlsrv_query($conn, $consulta);

			$i = 0;

			while ($fila = sqlsrv_fetch_array($ejecutar)) {

				$id = $fila['BADGENUMBER'];
				$usuario = $fila['NAME'];
				$pass = $fila['PASSWORD'];
				$email = $fila['EMail'];
				$i++;

			?>


				<tr align="center">

					<td><?php echo $id; ?></td>
					<td><?php echo $usuario; ?></td>
					<td><?php echo $pass; ?></td>
					<td><?php echo $email; ?></td>
					<td> <a class="btn btn-warning" href="editar.php?editar=<?php echo $id; ?>">Editar</a></td>

					<td> <a class="btn btn-danger" href="formulario.php?borrar=<?php echo $id; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este registro?');">Borrar</a></td>

				</tr>

			<?php
			}
			?>

		</table>
	</div>

	<?php
	if (isset($_GET['editar'])) {
		include("editar.php");
	}
	?>



	<?php

	if (isset($_GET['borrar'])) {

		$borrar_id = $_GET['borrar'];

		$borrar = "DELETE  FROM userinfo_backup WHERE BADGENUMBER = '$borrar_id'";

		$ejecutar = sqlsrv_query($conn, $borrar);



		if ($ejecutar) {
			echo "<script> alert('El usuario ha sido borrado')</script>";
			echo "<script> window.open('insertar.php', '_self')</script>";
		}
	}

	?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>