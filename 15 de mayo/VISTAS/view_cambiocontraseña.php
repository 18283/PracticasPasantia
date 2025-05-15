<?php
session_start();
require_once '../CONTROLADOR/conexion.php';
require_once '../CONTROLADOR/CambioContraseña.php';
echo "hola";
$conexion = new ConexionBaseDeDatos("10.100.16.11");
$conn = $conexion->conectar("desarrollo", "2024*madock*");

$cambioContraseña = new CambioContraseña($conn);
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $nuevaContraseña = $_POST['nuevaContraseña'];

    // Validar si la contraseña es válida (por ejemplo, longitud mínima)
    if (strlen($nuevaContraseña) < 6) {
        $mensaje = "⚠️ La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Intentar actualizar la contraseña
        if ($cambioContraseña->actualizarContraseña($id, $nuevaContraseña)) {
            $mensaje = "✅ Contraseña actualizada con éxito.";
        } else {
            $mensaje = "❌ Error al actualizar la contraseña o ID no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f5;
        }

        .form-container {
            text-align: center;
            padding: 20px;
        }

        button.open-modal {
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button.open-modal:hover {
            background-color: #1976D2;
        }

        /* Estilo del modal */
        .modal {
            display: none; /* Ocultamos el modal por defecto */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4); /* Fondo semitransparente */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 12px;
            width: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

        button.submit {
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

        button.submit:hover {
            background-color: #1976D2;
        }

        .error, .success {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

    <!-- Botón para abrir el modal -->
    <div class="form-container">
        <button class="open-modal" id="openModalBtn">Cambiar Contraseña</button>
    </div>

    <!-- Incluir el modal -->
    <?php include('V_EMERGENTES/modalCambioContraseña.php'); ?>

    <script>
        // Función para mostrar el modal
        const openModalBtn = document.getElementById("openModalBtn");
        const modal = document.getElementById("modalCambioContraseña");
        const closeModal = document.getElementsByClassName("close")[0];

        openModalBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeModal.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        // Mostrar el mensaje desde PHP
        <?php if (isset($mensaje)) { ?>
            alert('<?php echo $mensaje; ?>');  // Mostrar el mensaje como una alerta o usar el modal
        <?php } ?>
    </script>

</body>
</html>
