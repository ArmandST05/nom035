<?php

if (!isset($_SESSION['typeUser']) || $_SESSION['typeUser'] !== 'e') {
    die("Acceso denegado.");
}

// Verificar si se recibió el formulario correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['survey_id'], $_POST['answers'])) {
    $survey_id = intval($_POST['survey_id']); // ID de la encuesta 2
    $personal_id = $_SESSION['user_id']; // ID del empleado autenticado
    $answers = $_POST['answers']; // Respuestas del formulario

    // Definir las reglas de mapeo
    $group1 = [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33];
    $group2 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46];

    $valuesGroup1 = [
        'Siempre' => 0,
        'Casi siempre' => 1,
        'Algunas veces' => 2,
        'Casi nunca' => 3,
        'Nunca' => 4,
    ];

    $valuesGroup2 = [
        'Siempre' => 4,
        'Casi siempre' => 3,
        'Algunas veces' => 2,
        'Casi nunca' => 1,
        'Nunca' => 0,
    ];

    // Función para obtener el valor según el grupo
    function getValue($questionId, $response, $group1, $group2, $valuesGroup1, $valuesGroup2) {
        if (in_array($questionId, $group1)) {
            return $valuesGroup1[$response] ?? null;
        } elseif (in_array($questionId, $group2)) {
            return $valuesGroup2[$response] ?? null;
        }
        return null; // Manejo de preguntas fuera de los grupos
    }

    // Guardar las respuestas de la encuesta
    foreach ($answers as $question_id => $answer) {
        $question_id = intval($question_id);
        $answer = htmlspecialchars($answer); // Sanitizar el texto de la respuesta
        $value = getValue($question_id, $answer, $group1, $group2, $valuesGroup1, $valuesGroup2);

        // Validar que el valor sea válido antes de guardar
        if ($value === null) {
            die("Error: Respuesta inválida para la pregunta ID $question_id.");
        }

        $sql = "INSERT INTO survey_answers (personal_id, survey_id, question_id, response, valor, answered_at)
                VALUES ($personal_id, $survey_id, $question_id, '$answer', $value, NOW())";
        Executor::doit($sql);
    }

    // Marcar la encuesta como completada
    $sql = "UPDATE personal_surveys SET completed = 1 WHERE personal_id = $personal_id AND survey_id = $survey_id";
    Executor::doit($sql);

    echo "Respuestas de la Encuesta 2 guardadas exitosamente.";
    header("Location: index.php?view=home-personal");
    exit;
} else {
    die("Datos insuficientes o método no permitido.");
}
