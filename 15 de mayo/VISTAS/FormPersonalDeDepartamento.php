<?php
require_once 'BarraNavegacion.php';
require_once '../CONTROLADOR/conexion.php';
require_once 'menu.php';

$barra = new BarraNavegacion();
//$barra->configurarLetra('Verdana', '20px', '#ffffff', true, true);
$barra->render();

$menuLateral = new Menu('menuDepartamento.json', 'menuDepartamento');
$menuLateral->render();

// Conexión
$conexion = new ConexionBaseDeDatos("10.100.16.11");
$conn = $conexion->conectar("desarrollo", "2024*madock*");

if (!$conn) {
    die("<div class='alert alert-danger'>❌ Conexión fallida.</div>");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Personal por Departamento</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/reboost.css" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet">
    <link href="../css/stiloUser.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            padding-top: 70px;
            padding-left: 220px;
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #343a40;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8 form-container">
                <form method="post" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="ID">Seleccione un Departamento:</label>
                        <select name="ID" id="ID" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            <?php
                            $sql = "{CALL PA_ListaDeDPTO}";
                            $stmt = sqlsrv_query($conn, $sql);
                            if ($stmt === false) {
                                echo "<div class='alert alert-warning'>⚠️ Error al cargar departamentos.</div>";
                            } else {
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $id = $row['DEPTID'];
                                    $nombre = $row['DEPTNAME'];
                                    echo "<option value='$id'>$id - $nombre</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        // Si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ID'])) {
            $ID_Departamento = $_POST['ID'];

            echo "<div class='row justify-content-center mt-5'>";
            echo "<div class='col-md-10'>";
            $sql = "{CALL PA_PersonalDeDPTO(?)}";
            $params = array($ID_Departamento);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                echo "<div class='alert alert-danger'>❌ Error al obtener datos: ";
                print_r(sqlsrv_errors());
                echo "</div>";
            } else {
                require_once 'view_tabla0.2.php';
                $tabla = new view_tabla($stmt, $_GET['pagina'] ?? 1);

                // Estilos visuales
                $tabla->establecerColorFondo("#f9f9f9");
                $tabla->establecerColorEncabezado("#007bff");
                $tabla->establecerColorHover("#eaf4ff");
                $tabla->establecerEstiloColorido();

                $tabla->mostrarTabla();
            }

            echo "</div></div>";
        }

        sqlsrv_close($conn);
        ?>
    </div>

    <!-- FontAwesome para íconos -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Bootstrap (si no está incluido por tus archivos) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>