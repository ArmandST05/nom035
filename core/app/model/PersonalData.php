<?php
class PersonalData{

    public static $tablename = "personal";
    public function __construct()
    {
        $this -> name = "";
        $this -> email = "";
        $this -> id_departamento = "";
        $this -> id_puesto = "";
        $this -> fecha_alta = "";
        $this -> phone = "";
        $this -> usuario = "";
        $this -> clave ="";
    }

    public function add() {
        $sql = "INSERT INTO " . self::$tablename . " 
                (nombre, correo, id_departamento, id_puesto, fecha_alta, telefono, usuario, clave)
                VALUES (\"$this->name\", \"$this->email\", \"$this->id_departamento\", \"$this->id_puesto\", \"$this->fecha_alta\", \"$this->phone\", \"$this->usuario\", \"$this->clave\")";
        return Executor::doit($sql);
    }



    public static function getAll(){
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new EncuestaData());
    }
    
    public static function getAllWithDetails() {
        $sql = "SELECT 
                    personal.id, 
                    personal.nombre, 
                    personal.correo, 
                    departamentos.nombre AS departamento_nombre, 
                    puestos.nombre AS puesto_nombre, 
                    personal.usuario, 
                    personal.clave, 
                    personal.telefono 
                FROM personal
                INNER JOIN departamentos ON personal.id_departamento = departamentos.idDepartamento
                INNER JOIN puestos ON personal.id_puesto = puestos.id";
        
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonalData());
    }
    

}




?>