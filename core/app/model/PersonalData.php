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
    public static function getById($id){
        $sql = "SELECT * FROM " . self::$tablename . " WHERE id = '$id'";
        $query = Executor::doit($sql);
    
        return Model::one($query[0], new PersonalData());
    }
    public static function delete($id) {
        // Elimina el puesto por el ID
        $sql = "DELETE FROM personal WHERE id = $id";
        Executor::doit($sql);
    }

    public static function getAll(){
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonalData());
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
    public function update() {
        $sql = "UPDATE " . self::$tablename . " 
                SET 
                    nombre = \"$this->nombre\",
                    correo = \"$this->correo\",
                    id_departamento = \"$this->id_departamento\",
                    id_puesto = \"$this->id_puesto\",
                    fecha_alta = \"$this->fecha_alta\",
                    telefono = \"$this->telefono\",
                    usuario = \"$this->usuario\"
                WHERE id = $this->id";
        Executor::doit($sql);
    }
    

}




?>