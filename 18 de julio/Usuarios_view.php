<?php
// 🔐 Iniciar sesión y restringir acceso si no hay sesión activa
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /SysCA5/VISTAS/view_login.php");
    exit();
}
// Mostrar todos los errores
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require_once '../BarraNavegacion.php';
require_once '../../CONTROLADOR/conexion.php';
require_once '../menu.php';
require_once 'tabla_usuario.php';
require_once '../../CONTROLADOR/DescargarTabla.php';
require_once 'imagen_usuario.php';

// Crear instancia de la clase de conexión
$conexion = new ConexionBaseDeDatos();
// Obtener conexión
$conn = $conexion->getConexion();
if (!$conn) {
    die("❌ Conexión fallida.");
}
// Preparar y ejecutar el procedimiento almacenado
$sql = "{CALL PA_MostrarUsuarios}";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Esto imprimirá los detalles del error SQL
}

$barra = new BarraNavegacion();
$barra->render($_SESSION['nombre'], $_SESSION['usuario']);

$menuLateral = new Menu('menuUsuario.json', 'menuUsuario');
$menuLateral->render();

echo "<div style='margin-top: 60px; margin-left: 220px; padding: 20px;'>";


if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'usuario_actualizado') {
    echo "<div id='mensajeExito' style=\"
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 15px;
        margin: 20px auto;
        width: 80%;
        text-align: center;
        border-radius: 8px;
        font-weight: bold;
    \">
        ✅ Usuario actualizado correctamente.
    </div>
    <script>
        setTimeout(() => {
            const mensaje = document.getElementById('mensajeExito');
            if (mensaje) mensaje.style.display = 'none';
        }, 4000);
    </script>";
}

//convierte el stmt a un arreglo
$datos = [];
while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //unset($fila['Foto']);  // ← Ajusta al nombre exacto de tu columna de imagen
    $datos[] = $fila;
}

//Para mostrar la tabla
        $tabla = new view_tabla($datos);
		// Estilos visuales

		$tabla->establecerColorFondo("#f9f9f9");
		$tabla->establecerColorEncabezado("#007bff");
		$tabla->establecerColorHover("#eaf4ff");
		$tabla->establecerEstiloColorido();
        $tabla->RutasDeAcciones('editarformulario.php', 'view_crear.php', 'view_crear.php');
        $tabla->establecerCampoID('Carnet');
		$tabla->buscador(['Carnet', 'Nombre', 'Privilegio', 'Genero', 'Correo', 'Ingreso','Nacimiento','Departamento','Telefono de oficina', 'Telefono personal']);
        
        // $tabla->mostrarTabla($datos);
		$tabla->mostrarBotonDescarga();
         
echo "</div>";

sqlsrv_close($conn); // Cerrar conexión
?>
