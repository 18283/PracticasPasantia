<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';

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

// Obtener CI
$ci = $_POST['ci'] ?? null;

// Obtener fechas si existen, si no, se usan NULL
$fechaFinValue = isset($_POST['fechaFin']) ? htmlspecialchars($_POST['fechaFin']): date('Y-m-d');
$fechaInicioValue = isset($_POST['fechaInicio']) 
    ? htmlspecialchars($_POST['fechaInicio']) 
    : date('Y-m-01', strtotime($fechaFinValue));

// Verificar que CI fue enviado
if (!$ci) {
    die("⚠️ No se recibió un CI válido.");
}

// Llamar al procedimiento almacenado
$sql = "{CALL PA_RegistrosDeMarcacionEntreFechas(?, ?, ?)}";
$params = array($ci, $fechaInicioValue, $fechaFinValue);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Mostrar errores si falla
}

// Estilos para la tabla
echo "
<style>
    table {
        border-collapse: collapse;
        width: 90%;
        margin: 20px auto;
        font-family: Arial, sans-serif;
    }
    th, td {
        border: 1px solid #999;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color:rgb(182, 134, 168);
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>";

// Título
echo "<div style='text-align: center;'><h3>Resultados encontrados:</h3></div>";

// Encabezados de la tabla
echo "<table>";
echo "<tr>
        <th>CI Usuario</th>
        <th>Nombre</th>
        <th>Marcador</th>
        <th>Fecha</th>
        <th>Entrada/ Salida</th>
      </tr>";

// Mostrar resultados
$hayResultados = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hayResultados = true;
    echo "<tr>";
    echo "<td>" . $row['BADGENUMBER'] . "</td>";
    echo "<td>" . $row['NAME'] . "</td>";
    echo "<td>" . $row['MachineAlias'] . "</td>";
    echo "<td>" . $row['CHECKTIME']->format('Y-m-d H:i:s') . "</td>";
    echo "<td>" . $row['CHECKTYPE'] . "</td>";
    echo "</tr>";
}
echo "</table>";

if (!$hayResultados) {
    echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
}

// Botón para descargar PDF
if ($hayResultados) {

    echo "<div style='text-align: center; margin-top: 20px;'>
        <form method='post' target='_blank' action='DescargarExcel.php'>
            <input type='hidden' name='ci' value='" . htmlspecialchars($ci) . "'>
            <input type='hidden' name='fechaInicio' value='" . $fechaInicioValue . "'>
            <input type='hidden' name='fechaFin' value='" . $fechaFinValue . "'>
            <input type='submit' name='descargar' value='Descargar Excel'>
        </form>
    </div>";
}

// Botón para volver
echo "<div style='text-align: center; margin-top: 20px;' >
        <form action='FormRegistroDeMarcacion.php' method='get'>
            <input type='submit' value='Volver al formulario'>
        </form>
      </div>";

// Verifica si se pidió descargar

sqlsrv_close($conn);
?>