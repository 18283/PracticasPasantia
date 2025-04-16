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
    // Definir la consulta SQL
    $sql = "SELECT DEPTID AS 'SUB_AREA', DEPTNAME AS 'NOMBRE', (SELECT DEPTNAME FROM DEPARTMENTS AS d2 WHERE d2.DEPTID = d1.SUPDEPTID) AS 'AREA'
            FROM DEPARTMENTS AS d1"; 

    // Llamar a la función listar para ejecutar la consulta
    $resultados = $bd->listar($conn, $sql);

    //Impresion de datos
    if (!empty($resultados)) {
        //para estética 💅
        echo "
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 10px;
                font-family: Arial, sans-serif;
            }
            th, td {
                border: 1px solid #999;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color:rgb(182, 134, 168);
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        </style>";

        //imprimiendo tabla
        echo "<div style='text-align: center;'>
                <h3 style='margin-bottom: 30px;'>Resultados encontrados:</h3>
              </div>";

        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>
                <th>SUB_AREA</th>
                <th>NOMBRE</th>
                <th>AREA</th>
            </tr>";

        foreach ($resultados as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['SUB_AREA'] . "</td>";
            echo "<td>" . $fila['NOMBRE'] . "</td>";
            echo "<td>" . $fila['AREA'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
} else {
    echo "❌ No se pudo conectar a la base de datos.";
}