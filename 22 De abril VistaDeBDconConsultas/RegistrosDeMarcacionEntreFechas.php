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

// Obtener CI
$ci = $_POST['ci'] ?? null;

// Obtener fechas si existen, si no, se usan NULL
$fechaInicioSQL = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] . " 00:00:00" : null;
$fechaFinSQL = isset($_POST['fechaFin']) ? $_POST['fechaFin'] . " 23:59:59" : null;

// Verificar que CI fue enviado
if (!$ci) {
    die("⚠️ No se recibió un CI válido.");
}

// Depuración (puede quitarse luego)
echo "CI: $ci<br>";
echo "Fecha de inicio: " . ($fechaInicioSQL ?? 'NULL') . "<br>";
echo "Fecha de fin: " . ($fechaFinSQL ?? 'NULL') . "<br>";

// Llamar al procedimiento almacenado
$sql = "{CALL PA_RegistrosDeMarcacionEntreFechas(?, ?, ?)}";
$params = array($ci, $fechaInicioSQL, $fechaFinSQL);

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

// Botón para volver
echo "<div style='text-align: center; margin-top: 20px;' >
        <form action='formulario.html' method='get'>
            <input type='submit' value='Volver al formulario'>
        </form>
      </div>";

sqlsrv_close($conn);
?>