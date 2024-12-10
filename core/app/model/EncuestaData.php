<?php 

class EncuestaData{

    public static $tablename = "surveys";
    public function __construct() {
        $this->id = "";
        $this->title = "";
        $this->description = "";
        $this->created_at = "";
    }

    public static function getAll(){
        $sql = "SELECT * FROM ".self::$tablename;
        $query = Executor::doit($sql);
        return Model::many($query[0], new EncuestaData());
    }
    // Obtener encuestas asignadas a un empleado
    public static function getAssignedSurveys($personal_id) {
        $sql = "SELECT s.* 
                FROM personal_surveys ps
                INNER JOIN " . self::$tablename . " s ON ps.survey_id = s.id
                WHERE ps.personal_id = $personal_id AND ps.completed = 0";
        $query = Executor::doit($sql);
        return Model::many($query[0], new EncuestaData());
    }

    // Obtener preguntas de una encuesta específica
    public static function getQuestions($survey_id) {
        $sql = "SELECT * FROM survey_questions WHERE survey_id = $survey_id";
        $query = Executor::doit($sql);
        return Model::many($query[0], new EncuestaData());
    }
    public static function assignToPersonal($personalId, $surveyIds) {
        foreach ($surveyIds as $surveyId) {
            $sql = "INSERT INTO personal_surveys (personal_id, survey_id, completed, assigned_at) 
                    VALUES ($personalId, $surveyId, 0, NOW())";
            Executor::doit($sql);
        }
        return true;
    }
    
     // Método para asignar encuestas a un empleado
     public static function assignSurveysToEmployee($personalId, $surveyIds) {
        if (empty($surveyIds)) {
            throw new Exception("No se proporcionaron encuestas para asignar.");
        }

        foreach ($surveyIds as $surveyId) {
            $surveyId = intval($surveyId); // Asegúrate de que sea un número
            $sql = "INSERT INTO personal_surveys (personal_id, survey_id, completed, assigned_at)
                    VALUES ($personalId, $surveyId, 0, NOW())";
            Executor::doit($sql);
        }
        return true;


    }
    public static function getAssignedSurveyById($personal_id, $survey_id) {
        $sql = "SELECT s.* 
                FROM personal_surveys ps
                INNER JOIN " . self::$tablename . " s ON ps.survey_id = s.id
                WHERE ps.personal_id = $personal_id AND ps.survey_id = $survey_id";
        $query = Executor::doit($sql);
    
        // Convertimos el resultado en un array
        $result = mysqli_fetch_all($query[0], MYSQLI_ASSOC); // Usamos MYSQLI_ASSOC para obtener un array asociativo
    
        // Si hay resultados, retornamos el primero, mapeado al objeto EncuestaData
        if (count($result) > 0) {
            $encuesta = new EncuestaData();
            $encuesta->id = $result[0]['id'] ?? ""; // Asignamos el ID, o cadena vacía si no está presente
            $encuesta->title = $result[0]['title'] ?? ""; // Asignamos el título
            $encuesta->description = $result[0]['description'] ?? ""; // Asignamos la descripción
            $encuesta->created_at = $result[0]['created_at'] ?? ""; // Asignamos la fecha de creación (si está presente)
            return $encuesta;
        }
    
        // Si no se encuentra la encuesta asignada, retornamos null
        return null;
    }
    
    
    
}

?>