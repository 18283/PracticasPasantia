<!DOCTYPE html>


<?php

include("../CONTROLADOR/conexion.php");

// Crear instancia de la clase de conexión
$conexion = new ConexionBaseDeDatos("10.100.16.11");

// Datos de acceso
$usuarioSQL = "desarrollo";
$contrasenaSQL = "2024*madock*";

// Obtener conexión
$conn = $conexion->conectar($usuarioSQL, $contrasenaSQL);
if (!$conn) {
    die("❌ Conexión fallida.");
}

?>
<meta charset="UTF-8">

<head>

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
					<li class="nav-item"><a class="nav-link active text-white h1" href="FormPersonalDeDepartamento.php">DEPARTAMENTOS</a></li>
					<li class="nav-item"><a class="nav-link active text-white h1" href="FormRegistroDeMarcacion.php">MARCACIONES</a></li>
					<li class="nav-item"><a class="nav-link active text-white h1" href="RegistrosDePasantia.php">PASANTIA</a></li>
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
    <!-- BARRA DE NAVEGACION TOP FIN -->

    <!-- LISTA DE USUARIO INICIO-->

    <?php
    // Parámetros para la paginación
    $rango_paginas = 5;
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $registros_por_pagina = isset($_GET['registros_por_pagina']) ? (int)$_GET['registros_por_pagina'] : 10;
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    // Consulta SQL con paginación
    $consulta = "SELECT BADGENUMBER, NAME, PASSWORD, EMail 
             FROM userinfo_backup 
             ORDER BY BADGENUMBER
             OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params = array($offset, $registros_por_pagina);
    $ejecutar = sqlsrv_query($conn, $consulta, $params);

    // Consulta total de registros
    $total_consulta = "SELECT COUNT(*) AS total FROM userinfo_backup";
    $total_resultado = sqlsrv_query($conn, $total_consulta);
    $total_fila = sqlsrv_fetch_array($total_resultado, SQLSRV_FETCH_ASSOC);
    $total_registros = $total_fila['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);
    ?>

    <!-- Contenedor principal -->
    <div class="container my-4">
        <!-- Tabla de usuarios -->
        <table class="table table-hover table-bordered">
            <thead>
                <tr align="center">
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = sqlsrv_fetch_array($ejecutar, SQLSRV_FETCH_ASSOC)) { ?>
                    <tr align="center">
                        <td><?php echo $fila['BADGENUMBER']; ?></td>
                        <td><?php echo $fila['NAME']; ?></td>
                        <td><?php echo $fila['PASSWORD']; ?></td>
                        <td><?php echo $fila['EMail']; ?></td>
                     <!--   <td><a class="btn btn-editar" href="editar.php?editar=<?php echo $fila['BADGENUMBER']; ?>">Editar</a></td>
                        <td><a class="btn btn-borrar" href="formulario.php?borrar=<?php echo $fila['BADGENUMBER']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este registro?');">Borrar</a></td>
                    -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Contenedor para la paginación y selector -->
        <div class="container mb-3">
            <div class="row justify-content-center">
                <div class="col-md-6 d-flex flex-column align-items-center">

                    <!-- Formulario para seleccionar registros -->
                    <form method="GET" class="d-flex align-items-center justify-content-center mb-3">
                        <label for="registros_por_pagina" class="me-2 text-white">Registros por página:</label>
                        <select name="registros_por_pagina" id="registros_por_pagina" class="form-select w-auto">
                            <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="20" <?php echo $registros_por_pagina == 20 ? 'selected' : ''; ?>>20</option>
                            <option value="50" <?php echo $registros_por_pagina == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                        <button type="submit" class="btn btn-warning ms-2">Actualizar</button>
                    </form>

                    <!-- Navegación de paginación -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php
                            // Rango reducido de páginas
                            $rango_paginas = 5;
                            $inicio_rango = max(1, $pagina_actual - floor($rango_paginas / 2));
                            $fin_rango = min($total_paginas, $inicio_rango + $rango_paginas - 1);

                            // Asegurar que el rango no se desborde
                            if ($fin_rango - $inicio_rango < $rango_paginas) {
                                $inicio_rango = max(1, $fin_rango - $rango_paginas + 1);
                            }

                            // Botón "Anterior"
                            if ($pagina_actual > 1) {
                                echo '<li class="page-item">
                    <a class="page-link" href="?pagina=' . ($pagina_actual - 1) . '&registros_por_pagina=' . $registros_por_pagina . '">Anterior</a>
                  </li>';
                            }

                            // Primera página y puntos suspensivos
                            if ($inicio_rango > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=1&registros_por_pagina=' . $registros_por_pagina . '">1</a></li>';
                                if ($inicio_rango > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            // Rango de páginas visibles
                            for ($i = $inicio_rango; $i <= $fin_rango; $i++) {
                                $active = $i == $pagina_actual ? 'active' : '';
                                echo '<li class="page-item ' . $active . '">
                    <a class="page-link" href="?pagina=' . $i . '&registros_por_pagina=' . $registros_por_pagina . '">' . $i . '</a>
                  </li>';
                            }

                            // Última página y puntos suspensivos
                            if ($fin_rango < $total_paginas) {
                                if ($fin_rango < $total_paginas - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item">
                    <a class="page-link" href="?pagina=' . $total_paginas . '&registros_por_pagina=' . $registros_por_pagina . '">' . $total_paginas . '</a>
                  </li>';
                            }

                            // Botón "Siguiente"
                            if ($pagina_actual < $total_paginas) {
                                echo '<li class="page-item">
                    <a class="page-link" href="?pagina=' . ($pagina_actual + 1) . '&registros_por_pagina=' . $registros_por_pagina . '">Siguiente</a>
                  </li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>




        <!-- FIN LISTA USUARIO FIN-->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->

</body>

</html>