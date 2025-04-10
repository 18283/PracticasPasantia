<?php

class BaseDeDatos{

    public $NombreDelServidor;

    public function __construct($NombreDelServidor) {
        $this->NombreDelServidor=$NombreDelServidor;
    }

    public function conectar($Login, $contrasena){
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
    }
    
}

?>