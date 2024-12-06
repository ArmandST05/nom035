<?php 
class EmpresaData{
    public static $tablename = "empresas";
    public static $tablecantidad = "cantidad_empleados";
    public function __construct(){
        $this -> nombre = "";
        $this -> comentarios = "";
        $this -> id_cantidad = "";
        //$this -> id_gerente = "";
    } 


    public function add(){
        $sql = "INSERT INTO " . self::$tablename . "(nombre, comentarios, id_cantidad) VALUES 
        (\"$this->nombre\", \"$this->comentarios\", \"$this->id_cantidad\")";
        return Executor::doit($sql);
    }

    public static function getCantidades(){
        $sql = "SELECT * FROM " . self::$tablecantidad;
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonalData());
    }



    public static function getAll(){
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new EmpresaData());
    }
}


?>