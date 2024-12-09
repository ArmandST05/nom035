<?php

if ($_SESSION['typeUser'] !== 'e') {
    die("Acceso denegado: Solo los empleados pueden responder encuestas.");
}

$survey_id = $_GET['survey_id'] ?? null;
if (!$survey_id) {
    die("Encuesta no encontrada.");
}

// Obtener preguntas de la encuesta
$preguntas = EncuestaData::getQuestions($survey_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Responder Encuesta</title>
</head>
<body>
    <h1>Responder Encuesta</h1>
    <form action="guardar_respuestas.php" method="POST">
        <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
        <?php foreach ($preguntas as $pregunta): ?>
            <div>
                <p><?php echo htmlspecialchars($pregunta->question_text); ?></p>
                <label>
                    <input type="radio" name="respuesta[<?php echo $pregunta->id; ?>]" value="1"> Sí
                </label>
                <label>
                    <input type="radio" name="respuesta[<?php echo $pregunta->id; ?>]" value="0"> No
                </label>
            </div>
        <?php endforeach; ?>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
