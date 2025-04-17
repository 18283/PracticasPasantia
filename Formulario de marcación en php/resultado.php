<?php
require_once 'conexion.php';  // Asegúrate de que la conexión esté bien configurada

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

// Obtener datos del formulario
$ci = $_POST['ci'];
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];

// Verificar que los datos del formulario son correctos
echo "CI: $ci, Fecha de inicio: $fechaInicio, Fecha de fin: $fechaFin";  // Esto es para depurar

// Formatear fechas
$fechaInicioSQL = $fechaInicio . " 00:00:00";
$fechaFinSQL = $fechaFin . " 23:59:59";

// Llamar al procedimiento almacenado
$sql = "{CALL PA_RegistrosDeMarcacionEntreFechas(?, ?, ?)}";
$params = array($ci, $fechaInicioSQL, $fechaFinSQL);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
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
        <th>ID Usuario</th>
        <th>Nombre</th>
        <th>Marcador</th>
        <th>Fecha</th>
        <th>Tipo</th>
      </tr>";

// Mostrar resultados
$hayResultados = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hayResultados = true;
    echo "<tr>";
    echo "<td>" . $row['USERID'] . "</td>";
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

// Botón para volver
echo "<div style='text-align: center; margin-top: 20px;' >
        <form action='formulario.html' method='get'>
            <input type='submit' value='Volver al formulario'>
        </form>
      </div>";

sqlsrv_close($conn);
?>