<?php 

class Departamento{
    public $deptid, $deptname, $supdeptid;

    public function __construct($deptid, $deptname, $supdeptid = null) {
        $this->deptid = $deptid;
        $this->deptname = $deptname;
        $this->supdeptid = $supdeptid;
    }

}

?>