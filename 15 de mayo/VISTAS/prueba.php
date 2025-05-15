
<?php
require_once '../CONTROLADOR/conexion.php';  // Asegúrate de que la conexión esté bien configurada
require_once "view_tabla.php";
require_once "BarraNavegacion2.php";
// Crear instancia de la clase de conexión




$navbar = new BarraNavegacion();  // Usa ruta por defecto del servidor
$navbar->agregarElemento("Inicio", "prueba.php");
$navbar->cambiarColorFondo("#5d5350");
$navbar->cambiarColorLetra("#fff");
$navbar->render();




$conexion = new ConexionBaseDeDatos("10.100.16.11");
// Datos de acceso
$usuarioSQL = "desarrollo";
$contrasenaSQL = "2024*madock*";
// Obtener conexión
$conn = $conexion->conectar($usuarioSQL, $contrasenaSQL);
if (!$conn) {
    die("❌ Conexión fallida.");
}else{
	echo "conexion exitosa";
}

// Preparar y ejecutar el procedimiento almacenado
$sql = "{CALL PA_PersonalDeDPTO(?)}";

$params = array(1);

$stmt = sqlsrv_query($conn, $sql, $params);


if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
}

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
}
$tabla = new view_tabla($stmt);
$tabla->establecerColorFondo("#fffae6");     // Color de fondo suave (amarillo pastel)
$tabla->establecerColorEncabezado("#2196F3"); // Azul fuerte
$tabla->establecerColorHover("#bbdefb");      // Azul claro al pasar el mouse
$tabla->establecerEstiloColorido();
$tabla->mostrarTabla();


sqlsrv_close($conn);
?>