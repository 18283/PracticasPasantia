<?php

class BaseDeDatos{

    public $NombreDelServidor;

    public function __construct($NombreDelServidor) {
        $this->NombreDelServidor=$NombreDelServidor;
    }

/*    public function conectar($Login, $contrasena){
        //$serverName = "10.100.16.11"; //serverName\instanceName
        $connectionInfo = array ( "Database"=>"zktimedb", "UID"=>$Login, "PWD"=>$contrasena,"CharacterSet"=>"UTF-8","TrustServerCertificate"=>"yes");
        $conn = sqlsrv_connect( $this->NombreDelServidor, $connectionInfo);

        if( $conn ) {
            //echo "Conexión establecida";
            return true;
        }else{
            //echo "Conexión no se pudo establecer";
            print_r( sqlsrv_errors());
            return false;
        }
    } */
    
    public function conectar($Login, $contrasena) {
		$connectionInfo = array(
			"Database" => "zktimedb", 
			"UID" => $Login, 
			"PWD" => $contrasena,
			"CharacterSet" => "UTF-8", 
			"TrustServerCertificate" => "yes"
		);

		$conn = sqlsrv_connect($this->NombreDelServidor, $connectionInfo);

		if (!$conn) {
			// Si no se pudo conectar, mostrar los errores
			return false; // Retornar false en caso de error
		}
		return $conn; // Devolver la conexión en caso de éxito
	}

    public function listar($conn, $sql){
        // Ejecutar la consulta
        $query = sqlsrv_query($conn, $sql);

        // Verificar si la consulta se ejecutó correctamente
        if ($query === false) {
            die( print_r(sqlsrv_errors(), true));
        }

        // Recuperar los resultados
        $resultados = array();
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        // Retornar los resultados
        return $resultados;
    }

}

?>