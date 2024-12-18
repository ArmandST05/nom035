<?php
// Verificar que 'survey_id' esté definido y válido
if (isset($_GET['survey_id']) && is_numeric($_GET['survey_id'])) {
    $survey_id = intval($_GET['survey_id']); // Convertir a entero por seguridad
} else {
    die("ID de encuesta inválido.");
}

// Obtener las preguntas de la encuesta de riesgo psicosocial
$questions = EncuestaData::getPsychosocialRiskQuestions($survey_id);

// Obtener preguntas de servicio al cliente (41, 42, 43)
$serviceClientQuestions = EncuestaData::getServiceClientQuestions();

// Obtener preguntas de jefe (44, 45, 46)
$bossQuestions = EncuestaData::getBossQuestions();

// Depuración: Asegúrate de que se están obteniendo las preguntas correctamente
if (empty($questions)) {
    die("No se encontraron preguntas para esta encuesta.");
}
?>
<style>
.table {
    border-collapse: collapse;
    width: 80%;
    margin: 0 auto;
    text-align: center;
    background-color: #e5e8e8;
    padding: 10px;
}
thead {
    background-color: #3498db;
    text-align: left;
}
tr {
    border-bottom: 1px solid #ccc;
}

/* Asegurarse de que las preguntas adicionales están ocultas por defecto */
.hidden {
    display: none;
}
</style>

<form action="index.php?action=encuestas/save-answers-factorpsicosocial" method="POST">
    <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Pregunta</th>
                <th scope="col">Siempre</th>
                <th scope="col">Casi siempre</th>
                <th scope="col">Algunas veces</th>
                <th scope="col">Casi nunca</th>
                <th scope="col">Nunca</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <!-- Pregunta -->
                    <td><?php echo htmlspecialchars($question->text); ?></td>

                    <!-- Opciones de respuesta -->
                    <td>
                        <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Siempre" required>
                    </td>
                    <td>
                        <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi siempre">
                    </td>
                    <td>
                        <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Algunas veces">
                    </td>
                    <td>
                        <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi nunca">
                    </td>
                    <td>
                        <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Nunca">
                    </td>
                </tr>
            <?php endforeach; ?>

            <!-- Pregunta de servicio al cliente -->
            <tr>
                <td><strong>¿En tu trabajo debes brindar servicio a clientes o usuarios?</strong></td>
                <td>
                    <input type="radio" name="service_client" value="Si" id="service_yes" onclick="toggleServiceClientQuestions(true)" required> Sí
                </td>
                <td>
                    <input type="radio" name="service_client" value="No" id="service_no" onclick="toggleServiceClientQuestions(false)"> No
                </td>
            </tr>

            <!-- Preguntas de servicio al cliente (inicialmente ocultas) -->
            <?php foreach ($serviceClientQuestions as $question): ?>
                <tr class="hidden" id="service_client_question_<?php echo $question->id; ?>">
                    <td><?php echo htmlspecialchars($question->text); ?></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Siempre" required></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi siempre"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Algunas veces"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi nunca"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Nunca"></td>
                </tr>
            <?php endforeach; ?>

            <!-- Pregunta de jefe -->
            <tr>
                <td><strong>¿Eres jefe de otros trabajadores?</strong></td>
                <td>
                    <input type="radio" name="is_boss" value="Si" id="boss_yes" onclick="toggleBossQuestions(true)" required> Sí
                </td>
                <td>
                    <input type="radio" name="is_boss" value="No" id="boss_no" onclick="toggleBossQuestions(false)"> No
                </td>
            </tr>

            <!-- Preguntas de jefe (inicialmente ocultas) -->
            <?php foreach ($bossQuestions as $question): ?>
                <tr class="hidden" id="boss_question_<?php echo $question->id; ?>">
                    <td><?php echo htmlspecialchars($question->text); ?></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Siempre"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi siempre"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Algunas veces"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi nunca"></td>
                    <td><input type="radio" name="answers[<?php echo $question->id; ?>]" value="Nunca"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit">Enviar respuestas</button>
</form>

<script>
// Función para mostrar/ocultar las preguntas de servicio al cliente
function toggleServiceClientQuestions(show) {
    var questions = document.querySelectorAll('.hidden[id^="service_client_question_"]');
    questions.forEach(function(question) {
        if (show) {
            question.classList.remove('hidden');
        } else {
            question.classList.add('hidden');
        }
    });
}

// Función para mostrar/ocultar las preguntas de jefe
function toggleBossQuestions(show) {
    var questions = document.querySelectorAll('.hidden[id^="boss_question_"]');
    questions.forEach(function(question) {
        if (show) {
            question.classList.remove('hidden');
        } else {
            question.classList.add('hidden');
        }
    });
}
</script>
