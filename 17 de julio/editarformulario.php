<?php
require_once '../../CONTROLADOR/DEPARTAMENTO/DepartamentoController.php';

if (!isset($_POST['datosUsuario']) || !is_array($_POST['datosUsuario'])) {
    echo "<p style='color:red;text-align:center;'>No se recibieron datos del usuario.</p>";
    exit;
}

$datosUsuario = $_POST['datosUsuario'];

$controllerD = new DepartamentoController();
$listaDepartamentos = $controllerD->obtenerListaDepartamentos();

class mostrarFormularioEdicion {
    private $datosUsuario;
    private $listaDepartamentos;

    public function __construct($datos, $departamentos) {
        $this->datosUsuario = $datos;
        $this->listaDepartamentos = $departamentos;
    }

    public function mostrarFormularioEdicion() {
        $datosUsuario = $this->datosUsuario;
        $listaDepartamentos = $this->listaDepartamentos;
        ?>
        <h2 style="text-align:center;">Editar Usuario</h2>
        <form method="POST" action="procesar_edicion.php">
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

            <label for="GENDER">Género:</label>
            <select name="GENDER" id="GENDER">
                <option value="">-- Seleccione Género --</option>
                <option value="F" <?= $datosUsuario['GENDER'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                <option value="M" <?= $datosUsuario['GENDER'] == 'M' ? 'selected' : '' ?>>Masculino</option>
            </select>

            <label for="EMail">Correo:</label>
            <input type="email" name="EMail" id="EMail"
                value="<?= htmlspecialchars($datosUsuario['EMail']) ?>">

            <label for="HIREDDAY">Ingreso:</label>
            <input type="date" name="HIREDDAY" id="HIREDDAY"
                value="<?= htmlspecialchars($datosUsuario['HIREDDAY']) ?>">

            <label for="BIRTHDAY">Nacimiento:</label>
            <input type="date" name="BIRTHDAY" id="BIRTHDAY"
                value="<?= htmlspecialchars($datosUsuario['BIRTHDAY']) ?>">

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
                value="<?= htmlspecialchars($datosUsuario['OPHONE']) ?>">

            <label for="FPHONE">Teléfono personal:</label>
            <input type="tel" name="FPHONE" id="FPHONE" pattern="[0-9]{7,15}"
                value="<?= htmlspecialchars($datosUsuario['FPHONE']) ?>">

            <br><br>
            <button type="submit">Actualizar Usuario</button>
            <button type="button" onclick="window.history.back();">Cancelar</button>
        </form>
        <?php
    }
}

$formulario = new mostrarFormularioEdicion($datosUsuario, $listaDepartamentos);
$formulario->mostrarFormularioEdicion();
?>
