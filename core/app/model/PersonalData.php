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
    }

    public function add(){
        $sql = "INSERT INTO ".self::$tablename." 
                (name, email, id_departamento, id_puesto, fecha_alta, phone) 
                VALUES 
                (\"$this->name\", \"$this->email\", \"$this->id_departamento\", 
                \"$this->id_puesto\", \"$this->fecha_alta\", \"$this->phone\")";
        Executor::doit($sql);
    }
    

}




?>