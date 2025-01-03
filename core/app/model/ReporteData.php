<?php

class ReporteData {
    public static $tablename = "survey_answers"; // Tabla donde se guardan las respuestas

// Función para obtener los empleados que completaron encuestas
public static function getCompletedEmployees() {
    $sql = "SELECT personal_surveys.personal_id AS personal_id, personal.nombre AS personal_name
            FROM personal_surveys
            JOIN personal ON personal_surveys.personal_id = personal.id
            WHERE personal_surveys.completed = 1";
    
    $result = Executor::doit($sql); // Ejecutar la consulta y obtener el resultado

    // Asegurarnos de que se obtuvo un objeto mysqli_result
    if ($result && $result[0] instanceof mysqli_result) {
        // Convertir el resultado en un arreglo
        $empleados = [];
        while ($row = $result[0]->fetch_assoc()) {
            $empleados[] = $row; // Agregar cada fila al arreglo
        }
        return $empleados; // Devolver el arreglo con los empleados
    }

    // En caso de error o si no hay resultados
    return [];
}



    // Función para obtener las respuestas de un empleado para una encuesta específica
public static function getAnswersByEmployeeAndSurvey($personal_id, $survey_id) {
    $sql = "SELECT *
            FROM survey_answers
            JOIN personal_surveys ON survey_answers.personal_id = personal_surveys.personal_id 
                                     AND survey_answers.survey_id = personal_surveys.survey_id
            WHERE personal_surveys.completed = 1 
              AND survey_answers.survey_id = $survey_id 
              AND survey_answers.personal_id = $personal_id";
    return Executor::doit($sql);
}

    
        public static function getAnswers($personal_id, $encuesta_id) {
            $sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
            $result = Executor::doit($sql);
    
            $respuestas = [];
            if ($result && $result[0] instanceof mysqli_result) {
                while ($row = $result[0]->fetch_assoc()) {
                    $respuestas[$row['question_id']] = $row['response'];
                }
            }
    
            return $respuestas;
        }
    
    
}
