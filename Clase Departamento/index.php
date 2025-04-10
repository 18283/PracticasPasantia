<?php

// Incluir tu clase
require_once("conexion.php"); // Asegúrate que este nombre sea correcto

// Crear una instancia
$bd = new BaseDeDatos("10.100.16.11"); // Cambia por tu IP o servidor real

// Intentar conectar
$usuario = "desarrollo";
$contrasena = "2024*madock*";

if ($bd->conectar($usuario, $contrasena)) {
    echo "✅ Conexión exitosa a la base de datos.";
} else {
    echo "❌ No se pudo conectar a la base de datos.";
}
