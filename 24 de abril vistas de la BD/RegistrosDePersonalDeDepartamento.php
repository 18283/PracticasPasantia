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
$ID_Departamento = $_POST['ID'];

// Mostrar para depuración
echo "ID del Departamento: $ID_Departamento<br><br>";

// Preparar y ejecutar el procedimiento almacenado
$sql = "{CALL PA_PersonalDeDPTO(?)}";
$params = array($ID_Departamento);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
}

// Estilos para la tabla
echo "
<style>
    table {
        border-collapse: collapse;
        width: 40%;
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
        <th>Ver Marcaciones</th>
      </tr>";

// Mostrar resultados
$hayResultados = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hayResultados = true;
    echo "<tr>";
    echo "<td>" . $row['BADGENUMBER'] . "</td>";
    echo "<td>" . $row['NAME'] . "</td>";
    echo "<td>
        <form id='form_" . $row['BADGENUMBER'] . "' action='RegistrosDeMarcacionEntreFechas.php' method='post' style='display: none;'>
            <input type='hidden' name='ci' value='" . $row['BADGENUMBER'] . "'>
        </form>
        <a href='#' onclick=\"document.getElementById('form_" . $row['BADGENUMBER'] . "').submit();\" style='text-decoration: underline; color: blue;'>Ver Marcaciones</a>
      </td>";
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