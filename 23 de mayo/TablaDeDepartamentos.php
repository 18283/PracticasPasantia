<?php
require_once 'BarraNavegacion.php';
require_once '../CONTROLADOR/conexion.php';
require_once 'menu.php';
require_once 'view_tabla.php';
$barra = new BarraNavegacion();
//$barra->configurarLetra('Verdana', '20px', '#ffffff', true, true);
$barra->render();

$menuLateral = new Menu('menuDepartamento.json', 'menuDepartamento');
$menuLateral->render();

echo "<div style='margin-top: 60px; margin-left: 220px; padding: 20px;'>";  // Abre el contenedor con márgenes

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

// Preparar y ejecutar el procedimiento almacenado
$sql = "{CALL PA_MostrarDepartamentos}";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
}
        $tabla = new view_tabla($stmt);
		// Estilos visuales
		$tabla->establecerColorFondo("#f9f9f9");
		$tabla->establecerColorEncabezado("#007bff");
		$tabla->establecerColorHover("#eaf4ff");
		$tabla->establecerEstiloColorido();
        $tabla->buscador(['ID', 'NOMBRE', 'AREA PRINCIPAL']);
		$tabla->mostrarTabla();
sqlsrv_close($conn);

?>

