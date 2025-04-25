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
        width: 100%;
        font-family: Arial, sans-serif;
    }
    th, td {
        border: 1px solid #999;
        padding: 12px;
        text-align: center;
    }
    th {
        background-color:#cb8db5;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Estilo para el contenedor de la tabla con scroll */
    .tabla-contenedor {
        width: 60%; 
        margin: 0 auto 30px auto; 
        max-height: 700px; /*LARGO DE LA TABLA*/
        overflow-y: auto; 
        border: 1px solid #999;   
        border-radius: 6px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background-color: white;
    }

    /* Fijar encabezado de la tabla */
    th {
        position: sticky;
        top: -1;
        background-color:#cb8db5;
        color: white;
        z-index: 10;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Quitar espacio entre la tabla y el encabezado */
    .tabla-contenedor table {
        margin-top: 0;
    }
</style>";

// Título
echo "<div style='text-align: center;'><h3>Resultados encontrados:</h3></div>";

// Encabezados de la tabla
echo "<div class='tabla-contenedor'>";
echo "<table>";
echo "<thead>
        <tr>
            <th>CI Usuario</th>
            <th>Nombre</th>
            <th>Ver Marcaciones</th>
        </tr>
      </thead>";
echo "<tbody>";

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
echo "</tbody>";
echo "</table>";
echo "</div>"; // fin del contenedor con scroll

if (!$hayResultados) {
    echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
}

// Botón para volver (fuera de la tabla)
echo "<div style='text-align: center; margin-top: 20px;'>
        <form action='formulario.html' method='get'>
            <input type='submit' value='Volver al formulario'>
        </form>
      </div>";

sqlsrv_close($conn);
?>
