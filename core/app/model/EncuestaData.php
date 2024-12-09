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
    
    public static function getQuestionsBySection($survey_id) {
        $sql = "SELECT s.id AS section_id, s.name AS section_name, q.id AS question_id, q.question_text
                FROM sections s
                INNER JOIN survey_questions q ON s.id = q.section_id
                WHERE s.survey_id = $survey_id
                ORDER BY s.id, q.id";
        $query = Executor::doit($sql);
    
        // Convertimos el resultado en un array asociativo
        $result = mysqli_fetch_all($query[0], MYSQLI_ASSOC);
    
        // Estructuramos los datos en un formato por secciones
        $sections = [];
        foreach ($result as $row) {
            $section_id = $row['section_id'];
            if (!isset($sections[$section_id])) {
                $sections[$section_id] = [
                    'name' => $row['section_name'],
                    'questions' => []
                ];
            }
            $sections[$section_id]['questions'][] = [
                'id' => $row['question_id'],
                'text' => $row['question_text']
            ];
        }
    
        return $sections; // Retornamos las secciones con sus preguntas agrupadas
    }
    public static function getSurveyResponses($survey_id, $personal_id) {
        $sql = "SELECT sa.* 
                FROM survey_answers sa
                WHERE sa.survey_id = $survey_id AND sa.personal_id = $personal_id";
        $query = Executor::doit($sql);
        
        // Convertimos el resultado en un array asociativo
        $result = mysqli_fetch_all($query[0], MYSQLI_ASSOC);
        
        // Si hay resultados, retornamos las respuestas mapeadas a un formato adecuado
        if (count($result) > 0) {
            return $result; // Puedes optar por devolver los resultados como objetos si prefieres
        }
        
        // Si no se encuentran respuestas, retornamos un array vacío
        return [];
    }
    
    public static function getQuestionById($question_id) {
        $sql = "SELECT q.*, s.id AS section_id
                FROM survey_questions q
                LEFT JOIN sections s ON q.section_id = s.id
                WHERE q.id = $question_id";
        $query = Executor::doit($sql);
        
        // Convertimos el resultado en un array asociativo
        $result = mysqli_fetch_all($query[0], MYSQLI_ASSOC);
        
        // Si se encuentra la pregunta, la retornamos como objeto
        if (count($result) > 0) {
            $question = new EncuestaData(); // Puedes cambiar EncuestaData si es necesario
            $question->id = $result[0]['id'] ?? "";
            $question->title = $result[0]['question_text'] ?? ""; // Asumí que 'question_text' es el texto de la pregunta
            $question->section_id = $result[0]['section_id'] ?? ""; // Aseguramos que 'section_id' esté presente
            return $question;
        }
        
        // Si no se encuentra la pregunta, retornamos null
        return null;
    }
    
    
    
}

?>