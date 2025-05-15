<?php
class CambioContraseña {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // Método para cambiar la contraseña usando el procedimiento almacenado
    public function actualizarContraseña($id, $contraseña) {
        $sql = "{CALL PA_ActualizarContraseña(?, ?)}";
        $params = array($id, $contraseña);
        // Ejecutar la consulta
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            die("❌ Error en el procedimiento almacenado: " . print_r(sqlsrv_errors(), true));
        }

        // Retornar si la actualización fue exitosa o no
        $rowsAffected = sqlsrv_rows_affected($stmt);

        // Si las filas afectadas es mayor a 0, la actualización fue exitosa
        if ($rowsAffected > 0) {
            return true; // Contraseña actualizada correctamente
        } else {
            return false; // No se encontró el usuario o no se pudo actualizar
        }
    }
}
?>
