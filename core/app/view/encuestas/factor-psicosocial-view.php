<?php

$questions = EncuestaData::getPsychosocialRiskQuestions($survey_id);


    echo "<pre>";
    print_r($questions); // Verifica si las preguntas están siendo obtenidas correctamente
    echo "</pre>";
?>
    <form action="submit_answers.php" method="POST">
        <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
    
        <?php foreach ($questions as $question) { ?>
            <div class="question">
                <p><?php echo $question->text; ?></p>
                
                <!-- Opciones de respuesta -->
                <label>
                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Siempre" required> Siempre
                </label>
                <label>
                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi siempre"> Casi siempre
                </label>
                <label>
                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Algunas veces"> Algunas veces
                </label>
                <label>
                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Casi nunca"> Casi nunca
                </label>
                <label>
                    <input type="radio" name="answers[<?php echo $question->id; ?>]" value="Nunca"> Nunca
                </label>
            </div>
        <?php } ?>

        <button type="submit">Enviar respuestas</button>
    </form>
