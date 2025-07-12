<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /SysCA5/VISTAS/view_login.php");
    exit();
}

require_once '../BarraNavegacion.php';
require_once '../menu.php';
require_once '../../CONTROLADOR/DEPARTAMENTO/DepartamentoController.php';
require_once '../../CONTROLADOR/USUARIOS/UsuariosController.php';
require_once '../view_tabla.php';

$barra = new BarraNavegacion();
$barra->render($_SESSION['nombre'], $_SESSION['usuario']);
$menuLateral = new Menu('menuUsuario.json', 'menuUsuario');
$menuLateral->render();

//echo "<div style='margin-top: 60px; margin-left: 220px; padding: 20px;'>";

$controllerD = new DepartamentoController();
$controllerU = new UsuariosController();

$listaDepartamentos =$controllerD->obtenerListaDepartamentos();
$listaUsuarios = $controllerU->obtenerListaUsuarios();

$deptid = null;
$usrid = null;
$datosDepartamento = [];
$datosUsuario = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['USERID'], $_POST['BADGENUMBER'], $_POST['NAME'], $_POST['privilege'], $_POST['GENDER'], $_POST['EMail'], $_POST['HIREDDAY'], $_POST['BIRTHDAY'], $_POST['DEFAULTDEPTID'], $_POST['OPHONE'], $_POST['FPHONE'])) {
        $usrid = $_POST['USERID'];
        $campos = [
            'BADGENUMBER' => $_POST['BADGENUMBER'],
            'NAME' => $_POST['NAME'],
            'privilege' => $_POST['privilege'],
            'GENDER' => $_POST['GENDER'],
            'EMail' => $_POST['EMail'],
            'HIREDDAY' => $_POST['HIREDDAY'],
            'BIRTHDAY' => $_POST['BIRTHDAY'],
            'DEPTID' => $_POST['DEFAULTDEPTID'],
            'OPHONE' => $_POST['OPHONE'],
            'FPHONE' => $_POST['FPHONE'],
        ];
        $controllerU->actualizarUsuario($usrid, $campos);
        echo "<script>alert('✅ Usuario actualizado correctamente.'); location.href='';</script>";
        exit;
    }

    if (isset($_POST['selectUserId']) && !empty($_POST['selectUserId'])) {
        $usrid = $_POST['selectUserId'];
        $datosUsuario = $controllerU->obtenerUsuarioPorId($usrid);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /*display: flex;*/
            background-color:rgb(255, 255, 255);
            overflow-y: auto;
        }

        #contenidoPrincipal {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* ✅ Esto centra verticalmente */
            min-height: 90vh;   /* ✅ Asegura que ocupe toda la altura */
            box-sizing: border-box;
            padding: 0px;
        }

        .contenedor-formulario {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        .contenedor-formulario h2 {
            margin-top: 0;
            color: #333;
            margin-bottom: 20px;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        p {
            color: #c00;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="contenidoPrincipal">
    <div class="contenedor-formulario">
        <h2>Editar Usuario</h2>

        <!-- Formulario para elegir el usuario -->
        <form method="POST" action="">
            <label for="selectUserId">Seleccione el usuario a editar:</label>
            <select name="selectUserId" id="selectUserId" required>
                <option value="">-- Seleccione --</option>
                <?php foreach ($listaUsuarios as $usr): ?>
                    <option value="<?= htmlspecialchars($usr['USERID']) ?>"
                        <?= ($usrid == $usr['USERID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($usr['NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Cargar Usuario</button>
        </form>

        <hr>
<!-- Ok -->
        <!-- Formulario para editar los datos -->
        <?php if ($datosUsuario): ?>
            <form method="POST" action="">
                <input type="hidden" name="USERID" value="<?= htmlspecialchars($datosUsuario['USERID']) ?>">

                <label for="BADGENUMBER">Carnet:</label>
                <input type="number" name="BADGENUMBER" id="BADGENUMBER"
                       value="<?= htmlspecialchars($datosUsuario['BADGENUMBER']) ?>" min="0" step="1">

                <label for="NAME">Nombre de Usuario:</label>
                <input type="text" name="NAME" id="NAME"
                       value="<?= htmlspecialchars($datosUsuario['NAME']) ?>">

                <label for="privilege">Privilegio:</label>
                <select name="privilege" id="privilege">
                    <option value="">-- Seleccione un usuario --</option>
                    <option value="0" <?= $datosUsuario['privilege'] == 0 ? 'selected' : '' ?>>Usuario</option>
                    <option value="1" <?= $datosUsuario['privilege'] == 1 ? 'selected' : '' ?>>Enrolador</option>
                    <option value="2" <?= $datosUsuario['privilege'] == 2 ? 'selected' : '' ?>>Administrador</option>
                    <option value="3" <?= $datosUsuario['privilege'] == 3 ? 'selected' : '' ?>>Supervisor</option>
                    <option value="-1" <?= $datosUsuario['privilege'] == -1 ? 'selected' : '' ?>>Deshabilitado</option>
                </select>

                <label>Género:</label><br>
                <select name="GENDER">
                    <option value="">-- Seleccione Género --</option>
                    <option value="F" <?= $datosUsuario['GENDER'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                    <option value="M" <?= $datosUsuario['GENDER'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                </select><br>

                <label for="EMail">Correo:</label>
                <input type="email" name="EMail" id="EMail"
                       value="<?= htmlspecialchars($datosUsuario['EMail'] ?? '') ?>">

                <label for="HIREDDAY">Ingreso:</label>
                <input type="date" name="HIREDDAY" id="HIREDDAY"
                       value="<?= htmlspecialchars($datosUsuario['HIREDDAY'] ?? '') ?>">

                <label for="BIRTHDAY">Nacimiento:</label>
                <input type="date" name="BIRTHDAY" id="BIRTHDAY"
                       value="<?= htmlspecialchars($datosUsuario['BIRTHDAY'] ?? '') ?>">

                <label for="DEFAULTDEPTID">Departamento:</label>
                <select name="DEFAULTDEPTID" id="DEFAULTDEPTID">
                    <option value="">-- Seleccione un departamento --</option>
                    <?php foreach ($listaDepartamentos as $dpto): ?>
                        <option value="<?= $dpto['DEPTID'] ?>"
                            <?= ($datosUsuario['DEPTID'] == $dpto['DEPTID']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dpto['DEPTNAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="OPHONE">Teléfono de oficina:</label>
                <input type="tel" name="OPHONE" id="OPHONE" pattern="[0-9]{7,15}"
                       value="<?= htmlspecialchars($datosUsuario['OPHONE'] ?? '') ?>">

                <label for="FPHONE">Teléfono personal:</label>
                <input type="tel" name="FPHONE" id="FPHONE" pattern="[0-9]{7,15}"
                       value="<?= htmlspecialchars($datosUsuario['FPHONE'] ?? '') ?>">

                <button type="submit">Actualizar Usuario</button>
                <button type="button" onclick="window.location.href=window.location.pathname">Cancelar</button>
            </form>
        <?php elseif ($usrid): ?>
            <p>❌ Usuario no encontrado.</p>
        <?php endif; ?>

        <!-- mismo formulario version funcion, para mandar datos desde otro archivo -->
        <!-- $datosUsuario -->
        <?php
        function mostrarFormularioEdicion($datosUsuario) {
            global $listaDepartamentos; // accede a la variable global

            if (!$datosUsuario) {
                return;
            }
            ?>
            <form method="POST" action="">
                <input type="hidden" name="USERID" value="<?= htmlspecialchars($datosUsuario['USERID']) ?>">

                <label for="BADGENUMBER">Carnet:</label>
                <input type="number" name="BADGENUMBER" id="BADGENUMBER"
                    value="<?= htmlspecialchars($datosUsuario['BADGENUMBER']) ?>" min="0" step="1">

                <label for="NAME">Nombre de Usuario:</label>
                <input type="text" name="NAME" id="NAME"
                    value="<?= htmlspecialchars($datosUsuario['NAME']) ?>">

                <label for="privilege">Privilegio:</label>
                <select name="privilege" id="privilege">
                    <option value="">-- Seleccione un usuario --</option>
                    <option value="0" <?= $datosUsuario['privilege'] == 0 ? 'selected' : '' ?>>Usuario</option>
                    <option value="1" <?= $datosUsuario['privilege'] == 1 ? 'selected' : '' ?>>Enrolador</option>
                    <option value="2" <?= $datosUsuario['privilege'] == 2 ? 'selected' : '' ?>>Administrador</option>
                    <option value="3" <?= $datosUsuario['privilege'] == 3 ? 'selected' : '' ?>>Supervisor</option>
                    <option value="-1" <?= $datosUsuario['privilege'] == -1 ? 'selected' : '' ?>>Deshabilitado</option>
                </select>

                <label>Género:</label><br>
                <select name="GENDER">
                    <option value="">-- Seleccione Género --</option>
                    <option value="F" <?= $datosUsuario['GENDER'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                    <option value="M" <?= $datosUsuario['GENDER'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                </select><br>

                <label for="EMail">Correo:</label>
                <input type="email" name="EMail" id="EMail"
                    value="<?= htmlspecialchars($datosUsuario['EMail'] ?? '') ?>">

                <label for="HIREDDAY">Ingreso:</label>
                <input type="date" name="HIREDDAY" id="HIREDDAY"
                    value="<?= htmlspecialchars($datosUsuario['HIREDDAY'] ?? '') ?>">

                <label for="BIRTHDAY">Nacimiento:</label>
                <input type="date" name="BIRTHDAY" id="BIRTHDAY"
                    value="<?= htmlspecialchars($datosUsuario['BIRTHDAY'] ?? '') ?>">

                <label for="DEFAULTDEPTID">Departamento:</label>
                <select name="DEFAULTDEPTID" id="DEFAULTDEPTID">
                    <option value="">-- Seleccione un departamento --</option>
                    <?php foreach ($listaDepartamentos as $dpto): ?>
                        <option value="<?= $dpto['DEPTID'] ?>"
                            <?= ($datosUsuario['DEPTID'] == $dpto['DEPTID']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dpto['DEPTNAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="OPHONE">Teléfono de oficina:</label>
                <input type="tel" name="OPHONE" id="OPHONE" pattern="[0-9]{7,15}"
                    value="<?= htmlspecialchars($datosUsuario['OPHONE'] ?? '') ?>">

                <label for="FPHONE">Teléfono personal:</label>
                <input type="tel" name="FPHONE" id="FPHONE" pattern="[0-9]{7,15}"
                    value="<?= htmlspecialchars($datosUsuario['FPHONE'] ?? '') ?>">

                <button type="submit">Actualizar Usuario</button>
                <button type="button" onclick="window.location.href=window.location.pathname">Cancelar</button>
            </form>
        <?php
        }
        ?>

    </div>
</div>

</body>
</html>
