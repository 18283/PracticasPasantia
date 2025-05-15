 <?php
require_once '../CONTROLADOR/navBar.php';

$items = [
    "Inicio" => "../VISTAS/mostrar.php",
    "Contacto" => "../VISTAS/contacto.php",
    "Usuarios" => "../VISTAS/usuarios.php",
    "Cerrar sesión" => "../index.html"
	
];

$barra = new BarraNavegacion($items);
$barra->render();
?>
<?php
require_once '../CONTROLADOR/conexion.php';  // Asegúrate de que la conexión esté bien configurada
require_once 'view_tabla0.2.php';
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
$tabla = new view_tabla($stmt);
$tabla->establecerColorFondo("#fffae6");       // Fondo suave
$tabla->establecerColorEncabezado("#2196F3");  // Azul fuerte
$tabla->establecerColorHover("#bbdefb");       // Hover claro
$tabla->establecerEstiloColorido();
$tabla->mostrarTabla();


// Botón para volver (fuera de la tabla)
echo "<div style='text-align: center; margin-top: 20px;'>
        <form action='formulario.html' method='get'>
            <input type='submit' value='Volver al formulario'>
        </form>
      </div>";

sqlsrv_close($conn);
?>