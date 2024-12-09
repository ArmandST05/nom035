<?php
// guardar_respuestas.php
include "../controllers/EncuestasController.php";
include "../models/RespuestaData.php";

// Verifica que el usuario esté autenticado y sea un empleado
if ($_SESSION['typeUser'] !== 'e') {
    die("Acceso denegado: Solo los empleados pueden responder encuestas.");
}

// Obtener el ID del empleado
$personal_id = $_SESSION['user_id'];

// Obtener el ID de la encuesta
$survey_id = $_POST['survey_id'] ?? null;
if (!$survey_id) {
    die("Encuesta no encontrada.");
}

// Obtener las respuestas del formulario
$respuestas = $_POST['respuesta'] ?? [];

if (empty($respuestas)) {
    die("No se enviaron respuestas.");
}

// Guardar las respuestas en la base de datos
foreach ($respuestas as $question_id => $answer_value) {
    RespuestaData::save($personal_id, $question_id, $answer_value);
}

// Marcar la encuesta como completada
RespuestaData::completeSurvey($personal_id, $survey_id);

// Redirigir al usuario después de guardar las respuestas
header("Location: encuestas.php?status=completed");
exit();
?>
