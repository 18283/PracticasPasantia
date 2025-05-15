<div id="modalCambioContraseña" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Cambiar Contraseña</h2>
        <form method="POST" action="index.php">
            <label for="id">ID de Usuario:</label>
            <input type="text" id="id" name="id" required><br><br>

            <label for="nuevaContraseña">Nueva Contraseña:</label>
            <input type="password" id="nuevaContraseña" name="nuevaContraseña" required><br><br>

            <button type="submit" class="submit">Actualizar Contraseña</button>
        </form>

        <!-- Mostrar mensaje de error o éxito -->
        <?php if (isset($mensaje)) { ?>
            <div class="error"><?php echo $mensaje; ?></div>
        <?php } ?>
    </div>
</div>
