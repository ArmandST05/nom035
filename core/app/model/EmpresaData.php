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
    public static function delete($id) {
        // Elimina el puesto por el ID
        $sql = "DELETE FROM " . self::$tablename . " WHERE id = $id";
        Executor::doit($sql);
    }
    public function update() {
        try {
            $sql = "UPDATE " . self::$tablename . " 
                    SET 
                        nombre = \"$this->nombre\",
                        comentarios = \"$this->comentarios\",
                        id_cantidad = \"$this->id_cantidad\"
                    WHERE id = $this->id";
    
            Executor::doit($sql);
            return true; // Si la actualización es exitosa.
        } catch (Exception $e) {
            return false; // En caso de error.
        }
    }
    public static function getById($id){
		$sql = "select * from ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new EmpresaData());
	}
    public static function getByCantidad($id) {
        $sql = "SELECT empresas.*, cantidad_empleados.descripcion AS cantidad_descripcion 
        FROM empresas 
        INNER JOIN cantidad_empleados ON empresas.id_cantidad = cantidad_empleados.id
        WHERE empresas.id = '$id'";

$query = Executor::doit($sql);
return Model::one($query[0], new EmpresaData());
    }
    public static function getAll() {
        $sql = "SELECT empresas.*, cantidad_empleados.descripcion AS cantidad_descripcion 
                FROM empresas 
                INNER JOIN cantidad_empleados ON empresas.id_cantidad = cantidad_empleados.id";
        $query = Executor::doit($sql);
        return Model::many($query[0], new EmpresaData());
    }
    
}


?>