<?php
require_once '../../CONTROLADOR/conexion.php';

class UsuariosController {
    private $conn;

    // Constructor recibe la conexión con sqlsrv_connect
    public function __construct() {
       // Crear instancia de la clase de conexión
            $conexion = new ConexionBaseDeDatos();
            // Obtener conexión
            $this->conn = $conexion->getConexion();
            if (!$this->conn) {
                die("❌ Conexión fallida.");
            }
    } 

    //Insertar solo los 5 parametros principales, los demas seran null
    public function añadirUsuarios($BADGENUMBER, $NAME, $privilege, $GENDER, $EMail, $HIREDDAY, $BIRTHDAY, $DEFAULTDEPTID, $OPHONE, $FPHONE, $otros = []) {
        // Definir los valores por defecto como NULL
        $params = array_merge([
            'SSN' => null, 
            'TITLE' => null, 
            'PAGER' => null, 
            'STREET' => null, 
            'CITY' => null, 
            'STATE' => null, 
            'ZIP' => null, 
            'VERIFICATIONMETHOD' => null, 
            'SECURITYFLAGS' => null, 
            'ATT' => 1, 
            'INLATE' => 1, 
            'OUTEARLY' => 1, 
            'OVERTIME' => 1, 
            'SEP' => 1, 
            'HOLIDAY' => 1, 
            'MINZU' => null, 
            'PASSWORD' => null, 
            'LUNCHDURATION' => 1, 
            'MVerifyPass' => null, 
            'PHOTO' => null, 
            'Notes' => null, 
            'InheritDeptSch' => 1, 
            'InheritDeptSchClass' => 1, 
            'AutoSchPlan' => 1, 
            'MinAutoSchInterval' => 24, 
            'RegisterOT' => 1, 
            'InheritDeptRule' => 1, 
            'EMPRIVILEGE' => 0, 
            'CardNo' => null, 
            'FaceGroup' => 0, 
            'AccGroup' => 1, 
            'UseAccGroupTZ' => 1, 
            'VerifyCode' => 0, 
            'Expires' => 0, 
            'ValidCount' => 0, 
            'ValidTimeBegin' => null, 
            'ValidTimeEnd' => null, 
            'TimeZone1' => 1, 
            'TimeZone2' => 0, 
            'TimeZone3' => 0, 
            'IDCardNo' => null, 
            'IDCardValidTime' => null, 
            'IDCardName' => null, 
            'IDCardBirth' => null, 
            'IDCardSN' => null, 
            'IDCardDN' => null, 
            'IDCardAddr' => null, 
            'IDCardNewAddr' => null, 
            'IDCardISSUER' => null, 
            'IDCardGender' => null, 
            'IDCardNation' => null, 
            'IDCardReserve' => null, 
            'IDCardNotice' => null, 
            'IDCard_MainCard' => null, 
            'IDCard_ViceCard' => null, 
            'FSelected' => 0, 
            'Pin1' => null
        ], $otros);

        $sql = "EXEC PA_CrearUsuario
            @BADGENUMBER = ?, 
            @SSN = ?, 
            @NAME = ?, 
            @GENDER = ?,  
            @TITLE = ?, 
            @PAGER = ?, 
            @BIRTHDAY = ?, 
            @HIREDDAY = ?, 
            @STREET = ?, 
            @CITY = ?,  
            @STATE = ?, 
            @ZIP = ?, 
            @OPHONE = ?, 
            @FPHONE = ?, 
            @VERIFICATIONMETHOD = ?, 
            @DEFAULTDEPTID = ?,
            @SECURITYFLAGS = ?,
            @ATT = ?,
            @INLATE = ?,
            @OUTEARLY = ?,
            @OVERTIME = ?,
            @SEP = ?,
            @HOLIDAY = ?,
            @MINZU = ?,
            @PASSWORD = ?,
            @LUNCHDURATION = ?,
            @MVerifyPass = ?,
            @PHOTO = ?,
            @Notes = ?,
            @privilege = ?,
            @InheritDeptSch = ?,
            @InheritDeptSchClass = ?,
            @AutoSchPlan = ?,
            @MinAutoSchInterval = ?,
            @RegisterOT = ?,
            @InheritDeptRule = ?,
            @EMPRIVILEGE = ?,
            @CardNo = ?,
            @FaceGroup = ?,
            @AccGroup = ?,
            @UseAccGroupTZ = ?,
            @VerifyCode = ?,
            @Expires = ?,
            @ValidCount = ?,
            @ValidTimeBegin = ?,
            @ValidTimeEnd = ?,
            @TimeZone1 = ?,
            @TimeZone2 = ?,
            @TimeZone3 = ?,
            @IDCardNo = ?,
            @IDCardValidTime = ?,
            @EMail = ?,
            @IDCardName = ?,
            @IDCardBirth = ?,
            @IDCardSN = ?,
            @IDCardDN = ?,
            @IDCardAddr = ?,
            @IDCardNewAddr = ?,
            @IDCardISSUER = ?,
            @IDCardGender = ?,
            @IDCardNation = ?,
            @IDCardReserve = ?,
            @IDCardNotice = ?,
            @IDCard_MainCard = ?,
            @IDCard_ViceCard = ?,
            @FSelected = ?,
            @Pin1 = ?"; 

        $queryParams = [
            $BADGENUMBER,
            $params['SSN'],
            $NAME,
            $GENDER,
            $params['TITLE'], 
            $params['PAGER'],
            $BIRTHDAY,
            $HIREDDAY,
            $params['STREET'], 
            $params['CITY'], 
            $params['STATE'], 
            $params['ZIP'], 
            $OPHONE,
            $FPHONE,
            $params['VERIFICATIONMETHOD'], 
            $DEFAULTDEPTID,
            $params['SECURITYFLAGS'], 
            $params['ATT'], 
            $params['INLATE'], 
            $params['OUTEARLY'], 
            $params['OVERTIME'], 
            $params['SEP'], 
            $params['HOLIDAY'], 
            $params['MINZU'], 
            $params['PASSWORD'], 
            $params['LUNCHDURATION'], 
            $params['MVerifyPass'], 
            $params['PHOTO'], 
            $params['Notes'], 
            $privilege,
            $params['InheritDeptSch'], 
            $params['InheritDeptSchClass'], 
            $params['AutoSchPlan'], 
            $params['MinAutoSchInterval'], 
            $params['RegisterOT'], 
            $params['InheritDeptRule'], 
            $params['EMPRIVILEGE'], 
            $params['CardNo'], 
            $params['FaceGroup'], 
            $params['AccGroup'], 
            $params['UseAccGroupTZ'], 
            $params['VerifyCode'], 
            $params['Expires'], 
            $params['ValidCount'], 
            $params['ValidTimeBegin'], 
            $params['ValidTimeEnd'], 
            $params['TimeZone1'], 
            $params['TimeZone2'], 
            $params['TimeZone3'], 
            $params['IDCardNo'], 
            $params['IDCardValidTime'], 
            $EMail,
            $params['IDCardName'], 
            $params['IDCardBirth'], 
            $params['IDCardSN'], 
            $params['IDCardDN'],
            $params['IDCardAddr'],
            $params['IDCardNewAddr'],
            $params['IDCardISSUER'],
            $params['IDCardGender'],
            $params['IDCardNation'],
            $params['IDCardReserve'],
            $params['IDCardNotice'],
            $params['IDCard_MainCard'],
            $params['IDCard_ViceCard'],
            $params['FSelected'],
            $params['Pin1']
        ];

        $stmt = sqlsrv_query($this->conn, $sql, $queryParams);

        if ($stmt === false) {
            $errores = sqlsrv_errors();
            return [
                'exito' => false,
                'mensaje' => 'Error: Usuario existente',
                'errores' => $errores
            ];
        } else {
            // Limpiar mensajes adicionales si los hay
            while (sqlsrv_next_result($stmt)) {}
            return [
                'exito' => true,
                'mensaje' => '✅ Usuario insertado correctamente.'
            ];
        }
    } //ok

    //falta modificar
    function obtenerListaUsuarios() {
        // Nombre del procedimiento almacenado
        $procedure = "{CALL PA_ListaDeUsuarios}";

        // Ejecutar el procedimiento
        $stmt = sqlsrv_query($this->conn, $procedure);

        if ($stmt === false) {
            // Manejo de error
            die(print_r(sqlsrv_errors(), true));
        }

        $resultados = [];

        // Recorrer los resultados y guardarlos en el array
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        sqlsrv_free_stmt($stmt);

        return $resultados;
    } //ok

    //falta modificar
    function actualizarUsuario($USERID, $campos = []) {
        if (empty($USERID)) {
            throw new Exception("El USERID del usuario (USERID) es obligatorio.");
        }

        // Lista de todos los campos que acepta el procedimiento
        $todosLosCampos = [
            'BADGENUMBER', 
            'SSN', 
            'NAME', 
            'GENDER',  
            'TITLE', 
            'PAGER', 
            'BIRTHDAY', 
            'HIREDDAY', 
            'STREET', 
            'CITY',  
            'STATE', 
            'ZIP', 
            'OPHONE', 
            'FPHONE', 
            'VERIFICATIONMETHOD', 
            'DEFAULTDEPTID',
            'SECURITYFLAGS',
            'ATT',
            'INLATE',
            'OUTEARLY',
            'OVERTIME',
            'SEP',
            'HOLIDAY',
            'MINZU',
            'PASSWORD',
            'LUNCHDURATION',
            'MVerifyPass',
            'PHOTO',
            'Notes',
            'privilege',
            'InheritDeptSch',
            'InheritDeptSchClass',
            'AutoSchPlan',
            'MinAutoSchInterval',
            'RegisterOT',
            'InheritDeptRule',
            'EMPRIVILEGE',
            'CardNo',
            'FaceGroup',
            'AccGroup',
            'UseAccGroupTZ',
            'VerifyCode',
            'Expires',
            'ValidCount',
            'ValidTimeBegin',
            'ValidTimeEnd',
            'TimeZone1',
            'TimeZone2',
            'TimeZone3',
            'IDCardNo',
            'IDCardValidTime',
            'EMail',
            'IDCardName',
            'IDCardBirth',
            'IDCardSN',
            'IDCardDN',
            'IDCardAddr',
            'IDCardNewAddr',
            'IDCardISSUER',
            'IDCardGender',
            'IDCardNation',
            'IDCardReserve',
            'IDCardNotice',
            'IDCard_MainCard',
            'IDCard_ViceCard',
            'FSelected',
            'Pin1'
        ];

        // Crear el procedimiento con parámetros nombrados
        $procedure = "{CALL PA_ActualizarUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";

        // Preparar array de parámetros (USERID obligatorio + opcionales o NULL)
        $params = [$USERID]; // primer parámetro obligatorio

        foreach ($todosLosCampos as $campo) {
            $params[] = array_key_exists($campo, $campos) ? $campos[$campo] : null;
        }

        // Ejecutar el procedimiento
        $stmt = sqlsrv_query($this->conn, $procedure, $params);

        if ($stmt === false) {
            // Mostrar error detallado
            die(print_r(sqlsrv_errors(), true));
        }

        return true;
    } //ok

    public function obtenerUsuarioPorId($USERID) {
        $sql = "{CALL PA_ObtenerUsuarioPorID(?)}"; // Llamada al procedimiento
        $params = [$USERID]; 
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            // Lista de campos mínimos necesarios para el formulario
            $camposEsperados = [
                'USERID', 'BADGENUMBER', 'NAME', 'privilege', 'GENDER',
                'EMail', 'HIREDDAY', 'BIRTHDAY', 'DEPTID',
                'OPHONE', 'FPHONE'
            ];

            foreach ($camposEsperados as $campo) {
                if (!array_key_exists($campo, $row)) {
                    $row[$campo] = '';
                }
            }

            // ⚠️ Asegurarse de que las fechas sean cadenas formateadas
            if ($row['HIREDDAY'] instanceof DateTime) {
                $row['HIREDDAY'] = $row['HIREDDAY']->format('Y-m-d');
            }

            if ($row['BIRTHDAY'] instanceof DateTime) {
                $row['BIRTHDAY'] = $row['BIRTHDAY']->format('Y-m-d');
            }

            return $row;
        }

        return null;
    }

    public function CarnetAUserID(string $carnet): ?int {
        $sql = "{CALL PA_CarnetAIdDeUsuario(?)}";
        $params = [$carnet];

        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if ($stmt === false) {
            throw new Exception("❌ Error al ejecutar PA_CarnetAIdDeUsuario: " . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);

        return $row['USERID'] ?? null;
    }
}
