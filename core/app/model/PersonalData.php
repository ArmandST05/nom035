<?php
class PersonalData{

    public static $tablename = "personaler";
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
		$sql = "select * from ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonalData());
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
 
    public function update() {
        try {
            $sql = "UPDATE " . self::$tablename . " 
                    SET 
                        nombre = \"$this->nombre\",
                        correo = \"$this->correo\",
                        id_departamento = \"$this->id_departamento\",
                        id_puesto = \"$this->id_puesto\",
                        fecha_alta = \"$this->fecha_alta\",
                        telefono = \"$this->telefono\" 
                    WHERE id = $this->id";
    
            Executor::doit($sql);
            return true; // Si llega aquí, la operación fue exitosa.
        } catch (Exception $e) {
            return false; // En caso de cualquier error.
        }
    }
    public static function getByRole($role_id) {
        $sql = "SELECT * FROM personal WHERE id_puesto = $role_id";
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonalData());
    }
    

    public static function getPersonalByPuesto($puestoId) {
        $sql = "SELECT id FROM personal WHERE id_puesto = " . intval($puestoId);
        $query = Executor::doit($sql);
        return Model::many($query[0], new PersonalData()); // Suponiendo que PersonalData es el modelo para los empleados
    }

}




?>