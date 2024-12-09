<?php 

class EncuestaData{

    public static $tablename = "surveys";


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
}

?>