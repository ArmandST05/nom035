<?php 

class PuestoData{
    public static $tablename = "puestos";

    public function __construct(){
        $this -> nombre = "";
        $this -> id_departamento = "";
        $this -> id_encuesta = "";
    }

    public function add(){
        $sql = "INSERT INTO " . self::$tablename . " (nombre, id_departamento, id_encuesta) 
        VALUES (\"$this->nombre\", \"$this->id_departamento\", $this->id_encuesta)";


        return Executor::doit($sql);
    }


    public static function getAll(){
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new EncuestaData());
    }
}

?>