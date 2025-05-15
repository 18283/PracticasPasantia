<?php
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

// Obtener parámetros POST
$ci = $_POST['ci'] ?? null;
$fechaInicioSQL = $_POST['fechaInicio'] ?? null;
$fechaFinSQL = $_POST['fechaFin'] ?? null;

if (!$ci) {
    die("⚠️ No se recibió un CI válido.");
}

$sql = "{CALL PA_RegistrosDeMarcacionEntreFechas(?, ?, ?)}";
$params = array($ci, $fechaInicioSQL, $fechaFinSQL);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Headers para forzar la descarga como archivo Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=registros_marcacion.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Generar tabla
echo "<table border='1'>";
echo "<tr>
        <th>CI Usuario</th>
        <th>Nombre</th>
        <th>Marcador</th>
        <th>Fecha</th>
        <th>Entrada/Salida</th>
      </tr>";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['BADGENUMBER'] . "</td>";
    echo "<td>" . $row['NAME'] . "</td>";
    echo "<td>" . $row['MachineAlias'] . "</td>";
    echo "<td>" . $row['CHECKTIME']->format('Y-m-d H:i:s') . "</td>";
    echo "<td>" . $row['CHECKTYPE'] . "</td>";
    echo "</tr>";
}

echo "</table>";
sqlsrv_close($conn);
exit;
