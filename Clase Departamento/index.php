<?php

// Incluir tu clase
require_once("conexion.php"); // Asegúrate que este nombre sea correcto

// Crear una instancia
$bd = new BaseDeDatos("10.100.16.11"); // Cambia por tu IP o servidor real

// Intentar conectar
$usuario = "desarrollo";
$contrasena = "2024*madock*";

/*if ($bd->conectar($usuario, $contrasena)) {
    echo "✅ Conexión exitosa a la base de datos.";
} else {
    echo "❌ No se pudo conectar a la base de datos.";
}*/

$conn = $bd->conectar($usuario, $contrasena);

if ($conn) {
    //echo "✅ Conexión exitosa a la base de datos.<br>";

    // Definir la consulta SQL
    $sql = "SELECT DEPTID AS 'SUB_AREA', DEPTNAME AS 'NOMBRE', (SELECT DEPTNAME FROM DEPARTMENTS AS d2 WHERE d2.DEPTID = d1.SUPDEPTID) AS 'AREA'
            FROM DEPARTMENTS AS d1"; // Asegúrate de que 'DEPARTMENTS' sea la tabla correcta

    // Llamar a la función listar para ejecutar la consulta
    $resultados = $bd->listar($conn, $sql);

    // Verificar si los resultados están vacíos
    if (!empty($resultados)) {
        echo "Resultados encontrados:<br>";

        // Depuración: Imprimir todo el array de resultados para ver cómo están estructurados
        echo "<pre>";
        print_r($resultados);  // Esto te mostrará todas las columnas y sus valores
        echo "</pre>";

        // Mostrar los resultados de manera correcta
        foreach ($resultados as $fila) {
            // Aquí debes acceder a las columnas por su nombre real. Por ejemplo, si la columna es 'department_id', usa eso:
            echo "ID: " . $fila['department_id'] . " - Nombre: " . $fila['department_name'] . "<br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
} else {
    echo "❌ No se pudo conectar a la base de datos.";
}