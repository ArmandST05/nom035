<?php
class PeriodoData {

    public static $tablename = "periods";
    // Constructor para el modelo
    public function __construct()
    {
        $this->name = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->status = ""; // Puedes agregar un campo de estado (activo o inactivo)
    }

   
    // Obtener un periodo por ID
    public static function getById($id) {
        $sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
        $query = Executor::doit($sql);
        return Model::one($query[0], new PeriodoData());
    }

    // Actualizar los datos de un periodo
    public function update() {
        try {
            $sql = "UPDATE " . self::$tablename . " 
                    SET 
                        name = \"$this->name\",
                        start_date = \"$this->start_date\",
                        end_date = \"$this->end_date\",
                        status = \"$this->status\"
                    WHERE id = $this->id";
            Executor::doit($sql);
            return true; // Si se actualiza correctamente
        } catch (Exception $e) {
            return false; // En caso de error
        }
    }

    // Obtener todos los periodos
    public static function getAll() {
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new PeriodoData());
    }

    // Eliminar un periodo por ID
    public static function delete($id) {
        $sql = "DELETE FROM periodos WHERE id = $id";
        Executor::doit($sql);
    }
    

}  
