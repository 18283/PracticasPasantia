


<?php
 include("../CONTROLADOR/conexion.php");

$conexion = new ConexionBaseDeDatos("10.100.16.11");
$usuarioSQL = "desarrollo";
$contrasenaSQL = "2024*madock*";
$conn = $conexion->conectar($usuarioSQL, $contrasenaSQL);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Suponiendo que recibes el nombre de usuario y la contraseña de un formulario
$inputUser = $_POST['user'];
$inputPass =$_POST['pass'];

//$hashedPassword = password_hash($inputPass, PASSWORD_BCRYPT);


// Consulta para verificar las credenciales
$sql = "SELECT * FROM USERINFO WHERE BADGENUMBER = ? AND MVerifyPass = ?";

$params = array($inputUser, $inputPass);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Verificar si hay un resultado
if (sqlsrv_has_rows($stmt)) {
    header('Location: mostrar.php');
} else {
    echo " USUARIO O CONTRASEÑA INCORRECTO')";
   echo "<script> window.open('../index.html', '_self')</script>";
}

// Cerrar la conexión
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>