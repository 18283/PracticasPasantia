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

//convierte el stmt a un arreglo
$datos = [];
while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //unset($fila['Foto']);  // ← Ajusta al nombre exacto de tu columna de imagen
    $datos[] = $fila;
}
//$columnas_imagen = []; 
//$datos = [];

/*while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if (!empty($fila[$nombre_foto])) {
        // Convertimos el contenido binario a base64 para usarlo como imagen en línea
        $fotoBase64 = base64_encode($fila[$nombre_foto]);
        // Creamos un <img> con los datos base64
        $fila[$nombre_foto] = "<img src='data:image/jpeg;base64,{$fotoBase64}' width='60'>";
    } else {
        // En caso de no haber imagen, mostramos texto alternativo o imagen por defecto
        $fila[$nombre_foto] = "<em>Sin foto</em>";
    }

    foreach ($columnas_imagen as $columna) {
        if (!empty($fila[$columna])) {
            $fotoBase64 = base64_encode($fila[$columna]);
            if (!isset($fila["PHOTO"])) {
                echo "<p style='color:red;'>❗ USERID no está definido en esta fila:</p>";
                var_dump($fila);
            }
            $fila[$columna] = "<img src='imagen_usuario.php?id={$fila["USERID"]}' width='60'>";
        } else {
            $fila[$columna] = "<em>Sin foto</em>";
        }
    }

    $datos[] = $fila;
}*/

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
