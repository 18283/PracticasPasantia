<?php
// Activar modo desarrollo (puedes desactivarlo en producción)
$modoDesarrollo = true;

// Rutas
require_once '../CONTROLADOR/conexion.php';
require_once '../CONTROLADOR/login.php';
require_once 'view_tabla0.2.php';
//require_once 'BarraNavegacion2.php';
require_once 'BarraNavegacion.php';







//require_once 'BarraNavegacion.php';
//require_once 'VISTAS/menu.json';

// Barra de navegación
$navbar = new BarraNavegacion();
//$navbar->insertar("Inicio", "prueba.php");
//$navbar->cambiarFondo("#5d5350");
//$navbar->cambiarColorLetra("#fff");
//$navbar->render();
$navbar->configurarLetra('Verdana', '20px', '#000', true, true);
$navbar->render();

echo '<div style="margin-top: 80px;">';  // Asumiendo una barra de 60px + espacio







// Conexión a base de datos
$conexion = new ConexionBaseDeDatos("10.100.16.11");
$conn = $conexion->conectar("desarrollo", "2024*madock*");

if (!$conn) {
    die("<p style='color:red;'>❌ Conexión fallida.</p>");
} elseif ($modoDesarrollo) {
    //echo "<p style='color:green;'>✅ Conexión establecida correctamente.</p>";
}

// Ejecutar procedimiento almacenado
$sql = "{CALL sp_MarcacionesPorIDMarcador30dias}";
$params = array(null);
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    echo "<p style='color:red;'>❌ Error al ejecutar el procedimiento.</p>";
    if ($modoDesarrollo) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        die();
    }
}

// Mostrar la tabla
$tabla = new view_tabla($stmt);
$tabla->establecerColorFondo("#fffae6");       // Fondo suave
$tabla->establecerColorEncabezado("#2196F3");  // Azul fuerte
$tabla->establecerColorHover("#bbdefb");       // Hover claro
$tabla->establecerEstiloColorido();
$tabla->mostrarTabla();

echo '</div>';

// Cerrar conexión
sqlsrv_close($conn);
?>