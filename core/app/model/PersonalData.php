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
        $sql = "INSERT INTO " . self::$tablename . " (nombre, correo, id_departamento, id_puesto, fecha_alta, telefono, usuario, clave)
                VALUES (\"$this->name\", \"$this->email\", \"$this->id_departamento\", \"$this->id_puesto\", \"$this->fecha_alta\", \"$this->phone\", \"$this->usuario\", \"$this->clave\")";
        return Executor::doit($sql);
    }
    

}




?>