<?php
require_once 'conexion.php';  // Asegúrate de que este archivo tenga tu clase de conexión

// Conectar a la base de datos
$conexion = new ConexionBaseDeDatos("10.100.16.11");
$usuarioSQL = "desarrollo";
$contrasenaSQL = "2024*madock*";
$conn = $conexion->conectar($usuarioSQL, $contrasenaSQL);

if (!$conn) {
    die("❌ Conexión fallida.");
}

// Ejecutar el procedimiento para obtener los DEPTID
$sql = "{CALL PA_ListaDeDPTO}";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Comenzar HTML
echo '<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Personal de Departamento</title>
</head>
<body>
    <h2>Consultar Personal de Departamento</h2>
    <form action="RegistrosDePersonalDeDepartamento.php" method="post">
        <label for="ID">Seleccione un Departamento:</label>

        <select name="ID" id="ID" required>
            <option value="">-- Seleccione --</option>';

// Agregar cada DEPTID como opción en el select
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $id = $row['DEPTID'];
    $nombre = $row['DEPTNAME'];
    echo "<option value='$id'>$id - $nombre</option>";
}

echo '      </select><br><br>

        <input type="submit" value="Buscar">
    </form>
</body>
</html>';

// Cerrar conexión
sqlsrv_close($conn);
?>