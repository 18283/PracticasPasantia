<?php


$serverName = "10.100.16.11"; //serverName\instanceName
$connectionInfo = array ( "Database"=>"zktimedb", "UID"=>"desarrollo", "PWD"=>"2024*madock*","CharacterSet"=>"UTF-8","TrustServerCertificate"=>"yes");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Conexión establecida";
}else{
     echo "Conexión no se pudo establecer";
     die( print_r( sqlsrv_errors(), true));
}
