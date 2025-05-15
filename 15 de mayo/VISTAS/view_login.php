<?php
session_start();

require_once '../CONTROLADOR/conexion.php';

require_once '../CONTROLADOR/login.php';
// Conectar a la base de datos
$conexion = new ConexionBaseDeDatos("10.100.16.11");
$conn = $conexion->conectar("desarrollo", "2024*madock*");

$login = new Login($conn);

$mensajeError = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    if ($login->autenticar($usuario, $clave)) {
        header("Location: view_inicio.php"); // Redireccionar tras login exitoso
        exit();
    } else {
        $mensajeError = "⚠️ Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f5;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1976D2;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($mensajeError)): ?>
            <div class="error"><?php echo $mensajeError; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="clave">Contraseña:</label>
            <input type="password" name="clave" id="clave" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
