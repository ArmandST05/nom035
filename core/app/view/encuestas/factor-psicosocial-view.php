<?php
// Verificar que 'survey_id' esté definido y válido
if (isset($_GET['survey_id']) && is_numeric($_GET['survey_id'])) {
    $survey_id = intval($_GET['survey_id']); // Convertir a entero por seguridad
} else {
    die("ID de encuesta inválido.");
}

// Obtener las preguntas de la encuesta de riesgo psicosocial
$questions = EncuestaData::getPsychosocialRiskQuestions($survey_id);

// Depuración: Asegúrate de que se están obteniendo las preguntas correctamente
if (empty($questions)) {
    die("No se encontraron preguntas para esta encuesta.");
}
?>
<style>
.table{
    
    border-collapse: collapse;
    width: 80%;
    margin: 0 auto; 
    text-align: center;
    background-color: #e5e8e8;
    padding: 10px;
}
thead{
    background-color: #3498db;
    text-align: left;
}
tr {
        border-bottom: 1px solid #ccc;
    }
</style>
<form action="index.php?action=encuestas/save-answers-factorpsicosocial" method="POST">
    <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">

    <table class="table table-hover ">
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
        </tbody>
    </table>

    <button type="submit">Enviar respuestas</button>
</form>
