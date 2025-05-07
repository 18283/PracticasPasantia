<?php
require_once 'BarraNavegacion.php';

$items = [
    "Inicio" => "../VISTAS/mostrar.php",
    "Usuarios" => "../VISTAS/usuarios.php",
    "Contacto" => "../VISTAS/contacto.php",
    "Cerrar sesión" => "../index.html"
];

$barra = new BarraNavegacion($items);
$barra->render();

//botones o desplegable para ejecutar las funciones
// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar'])) {
    // Nueva lista desde el formulario (simulada)
    $nuevosItems = [
        "Perfil" => "../VISTAS/perfil.php",
        "Configuración" => "../VISTAS/configuracion.php",
        "Salir" => "../index.html"
    ];

    $barra = new BarraNavegacion($items); // esta inicialización no importa mucho ahora
    $barra->insertar($nuevosItems); // insertar y mostrar la nueva barra
} else {
    // Mostrar barra original
    $barra = new BarraNavegacion($items);
    $barra->render();
}

?>