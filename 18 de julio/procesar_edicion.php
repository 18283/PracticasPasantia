<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /SysCA5/VISTAS/view_login.php");
    exit();
}

require_once '../../CONTROLADOR/USUARIOS/UsuariosController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $USERID = $_POST['USERID'] ?? null;
    $BADGENUMBER = $_POST['BADGENUMBER'] ?? null;
    $NAME = $_POST['NAME'] ?? null;

    if (!$USERID || !$BADGENUMBER || !$NAME) {
        die("❌ Faltan datos requeridos.");
        // Temporal para depuración
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    }

    try {
        $controller = new UsuariosController();

        $campos = [
            'BADGENUMBER' => $BADGENUMBER,
            'NAME' => $NAME,
            // Puedes incluir más campos si los tienes en el formulario
            'GENDER' => $_POST['GENDER'] ?? null,
            'EMail' => $_POST['EMail'] ?? null,
            'HIREDDAY' => $_POST['HIREDDAY'] ?? null,
            'BIRTHDAY' => $_POST['BIRTHDAY'] ?? null,
            'DEPTID' => $_POST['DEFAULTDEPTID'] ?? null,
            'OPHONE' => $_POST['OPHONE'] ?? null,
            'FPHONE' => $_POST['FPHONE'] ?? null,
            'privilege' => $_POST['privilege'] ?? null,
        ];

        $controller->actualizarUsuario($USERID, $campos);

        header("Location: Usuarios_view.php?mensaje=usuario_actualizado");
        exit();
    } catch (Exception $e) {
        echo "❌ Error al actualizar: " . $e->getMessage();
    }
} else {
    echo "❌ Acceso inválido.";
}
