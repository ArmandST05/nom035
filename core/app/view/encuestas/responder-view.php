<?php

if (!isset($_SESSION['typeUser']) || $_SESSION['typeUser'] !== 'e') {
    die("Acceso denegado: Solo los empleados pueden responder encuestas.");
}

// Validar si se proporcionó un ID de encuesta
if (!isset($_GET['survey_id']) || !is_numeric($_GET['survey_id'])) {
    die("ID de encuesta inválido.");
}

$survey_id = intval($_GET['survey_id']);
$personal_id = $_SESSION['user_id']; // ID del empleado autenticado

// Obtener la encuesta asignada y sus preguntas
$encuesta = EncuestaData::getAssignedSurveyById($personal_id, $survey_id);
if (!$encuesta) {
    die("Encuesta no encontrada o no asignada.");
}

$preguntas = EncuestaData::getQuestions($survey_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Responder Encuesta</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($encuesta->title); ?></h1>
    <p><?php echo htmlspecialchars($encuesta->description); ?></p>
    
    <form id="surveyForm" method="POST" action="index.php?action=encuestas/save-answers">
        <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
        
        <?php if (!empty($preguntas)): ?>
            <?php foreach ($preguntas as $pregunta): ?>
                <div class="question">
                    <label for="question_<?php echo $pregunta->id; ?>">
                        <?php echo htmlspecialchars($pregunta->question_text); ?>
                    </label>
                    <br>
                    <!-- Suponiendo que todas las preguntas son de tipo "opción única" -->
                    <input type="radio" name="answers[<?php echo $pregunta->id; ?>]" value="1" required> Sí
                    <input type="radio" name="answers[<?php echo $pregunta->id; ?>]" value="0"> No
                </div>
                <br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Esta encuesta no tiene preguntas configuradas.</p>
        <?php endif; ?>

        <?php if (!empty($preguntas)): ?>
            <button type="submit">Enviar Respuestas</button>
        <?php endif; ?>
    </form>
</body>
</html>
