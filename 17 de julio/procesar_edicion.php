<?php
require_once '../../CONTROLADOR/USUARIO/UsuariosController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $USERID = $_POST['USERID'] ?? null;
    $BADGENUMBER = $_POST['BADGENUMBER'] ?? null;
    $NAME = $_POST['NAME'] ?? null;

    if (!$USERID || !$BADGENUMBER || !$NAME) {
        die("❌ Faltan datos requeridos.");
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
            'DEFAULTDEPTID' => $_POST['DEFAULTDEPTID'] ?? null,
            'OPHONE' => $_POST['OPHONE'] ?? null,
            'FPHONE' => $_POST['FPHONE'] ?? null,
            'privilege' => $_POST['privilege'] ?? null,
        ];

        $controller->actualizarUsuario($USERID, $campos);

        header("Location: vista_usuarios.php?mensaje=editado");
        exit;
    } catch (Exception $e) {
        echo "❌ Error al actualizar: " . $e->getMessage();
    }
} else {
    echo "❌ Acceso inválido.";
}
