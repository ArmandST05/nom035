<?php

// Verificar si se recibió la solicitud correcta
if (isset($_GET['encuesta_id']) && isset($_GET['personal_id'])) {
    $encuesta_id = $_GET['encuesta_id'];
    $personal_id = $_GET['personal_id'];

    // Obtener las preguntas de la encuesta
    $preguntas_sql = "SELECT id, question_text FROM survey_questions WHERE survey_id = $encuesta_id";
    $preguntas_result = Executor::doit($preguntas_sql);
    $preguntas = [];
    if ($preguntas_result && $preguntas_result[0]) {
        while ($row = $preguntas_result[0]->fetch_assoc()) {
            $preguntas[] = $row;
        }
    }

    // Obtener las respuestas del empleado para la encuesta
    $respuestas_sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
    $respuestas_result = Executor::doit($respuestas_sql);
    $respuestas = [];
    if ($respuestas_result && $respuestas_result[0]) {
        while ($row = $respuestas_result[0]->fetch_assoc()) {
            $respuestas[] = $row;
        }
    }

    // Devolver las preguntas y respuestas como un JSON
    echo json_encode(['preguntas' => $preguntas, 'respuestas' => $respuestas]);
    exit;
}
?>
