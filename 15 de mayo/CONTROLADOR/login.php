<?php
class login {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // Autenticación usando PA_login que devuelve 1 o 0
    public function autenticar($usuario, $clave) {
        $sql = "{CALL PA_login(?, ?)}";
        $params = array($usuario, $clave);

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            die("❌ Error en el procedimiento almacenado: " . print_r(sqlsrv_errors(), true));
        }

        $resultado = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($resultado && isset($resultado['Resultado']) && $resultado['Resultado'] == 1) {
            // Login correcto: guardar usuario en sesión
            $_SESSION['usuario'] = $usuario;
            return true;
        }

        return false; // Login fallido
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
    }

    public function estaAutenticado() {
        return isset($_SESSION['usuario']);
    }
}
?>
