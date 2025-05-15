
<?php


require_once 'BarraNavegacion.php';
require_once '../CONTROLADOR/conexion.php';
require_once 'menu.php';
// Crear instancia de la clase de conexión

$barra = new BarraNavegacion();
//$barra->configurarLetra('Verdana', '20px', '#ffffff', true, true);
$barra->render();

$menuLateral = new Menu('menuDepartamento.json', 'menuDepartamento');
$menuLateral->render();






ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
$fechaFinValue = isset($_POST['fechaFin']) 
    ? htmlspecialchars($_POST['fechaFin']) . ' 23:59:59' 
    : date('Y-m-d') . ' 23:59:59';

$fechaInicioValue = isset($_POST['fechaInicio']) 
    ? htmlspecialchars($_POST['fechaInicio']) . ' 00:00:00' 
    : date('Y-m-01', strtotime($fechaFinValue)) . ' 00:00:00';



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

//aqui
// Mostrar formulario de selección de fechas
echo "
<div style='
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #ffffff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    z-index: 1000;
    max-width: 300px;
'>
    <form method='post' action='RegistrosDeMarcacionEntreFechas.php'>
        <div style='margin-bottom: 10px;'>
            <label for='fechaInicio'>📅 Fecha Inicio:</label><br>
            <input type='date' name='fechaInicio' id='fechaInicio' value='" . date('Y-m-d', strtotime($fechaInicioValue)) . "' required>
        </div>
        <div style='margin-bottom: 10px;'>
            <label for='fechaFin'>📅 Fecha Fin:</label><br>
            <input type='date' name='fechaFin' id='fechaFin' value='" . date('Y-m-d', strtotime($fechaFinValue)) . "' required>
        </div>
        <input type='hidden' name='ci' value='" . htmlspecialchars($ci) . "'>
        <div style='text-align: right;'>
            <button type='submit' style='padding: 6px 12px; background-color: #cb8db5; color: white; border: none; border-radius: 5px;'>🔄 Filtrar</button>
        </div>
    </form>
</div>
";

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
</style>";




// Título
echo "<div style='text-align: center;'><h3>Resultados encontrados:</h3></div>";

// Cuadritos indicando para que sirve cada color (Entrada/Salida)
echo "
<div style='width: 60%; margin: 0 auto; text-align: right; font-family: Arial, sans-serif; margin-bottom: 10px;'>
    <div style='display: inline-block; margin-left: 10px;'>
        <span style='display: inline-block; width: 20px; height: 20px; background-color: #fdfefe; border: 1px solid #999; vertical-align: middle;'></span>
        <span style='margin-left: 5px;'>Entrada</span>
    </div>
    <div style='display: inline-block; margin-left: 20px;'>
        <span style='display: inline-block; width: 20px; height: 20px; background-color: #eee2ea; border: 1px solid #999; vertical-align: middle;'></span>
        <span style='margin-left: 5px;'>Salida</span>
    </div>
</div>
";


// Encabezados de la tabla
echo "<div style='width: 60%; margin: 0 auto 30px auto; max-height: 700px; overflow-y: auto; border: 1px solid #999; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";

// Tabla con encabezado fijo
echo "<table>";
echo "<thead>
        <tr>
            <th style='position: sticky; top: 0; background-color:#cb8db5; color: white; z-index: 1;'>CI Usuario</th>
            <th style='position: sticky; top: 0; background-color:#cb8db5; color: white; z-index: 1;'>Nombre</th>
            <th style='position: sticky; top: 0; background-color:#cb8db5; color: white; z-index: 1;'>Marcador</th>
            <th style='position: sticky; top: 0; background-color:#cb8db5; color: white; z-index: 1;'>Fecha</th>
            <th style='position: sticky; top: 0; background-color:#cb8db5; color: white; z-index: 1;'>Entrada/ Salida</th>
        </tr>
      </thead>";
echo "<tbody>";

// Mostrar resultados
$hayResultados = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $hayResultados = true;

    // Determinar color de fondo según Entrada o Salida
    $checkType = $row['CHECKTYPE'];
    $backgroundColor = ($checkType === 'Entrada') ? '#fdfefe' : '#eee2ea'; //blanco o rasado claro

    echo "<tr style='background-color: $backgroundColor;'>";
    echo "<td>" . $row['BADGENUMBER'] . "</td>";
    echo "<td>" . $row['NAME'] . "</td>";
    echo "<td>" . $row['MachineAlias'] . "</td>";
    echo "<td>" . $row['CHECKTIME']->format('Y-m-d H:i:s') . "</td>";
    echo "<td>" . $checkType . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>"; // fin del contenedor con scroll

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

// Verifica si se pidió descargar

sqlsrv_close($conn);
?>