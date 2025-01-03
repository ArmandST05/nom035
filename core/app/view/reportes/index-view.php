<?php
$encuestas = EncuestaData::getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Encuestas NOM-035</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Reportes por Empleado</h1>

    <form method="POST" action="index.php?action=reportes/get-questions">
        <label for="personal_id">Seleccionar Empleado:</label>
        <select id="personal_id" name="personal_id" required onchange="cargarPreguntas()">
        <option value="">Selecciona un empleado</option>
            <?php
            $empleados = ReporteData::getCompletedEmployees();
            if (!empty($empleados)) {
                foreach ($empleados as $empleado) {
                    echo "<option value='{$empleado['personal_id']}'>{$empleado['personal_name']}</option>";
                }
            } else {
                echo "<option value=''>No hay empleados disponibles</option>";
            }
            ?>
        </select>

        <label for="survey_id">Seleccionar Encuesta:</label>
        <select id="survey_id" name="survey_id" required onchange="cargarPreguntas()">
            <option value="">Selecciona una encuesta</option>
        <?php 
        if (!empty($encuestas)) {
            foreach ($encuestas as $encuesta) {
                echo "<option value='{$encuesta->id}'>{$encuesta->title}</option>";
            }
        } else {
            echo "<option value=''>No hay encuestas disponibles</option>";
        }
        ?>
        </select>

        <table id="tabla_preguntas" class="table table-bordered"  border="1">
            
        </table>

    </form>
    <script>
    // Función para cargar las preguntas y respuestas de la encuesta seleccionada
function cargarPreguntas() {
    var encuesta_id = $("#survey_id").val();
    var personal_id = $("#personal_id").val();

    if (encuesta_id && personal_id) {
        // Realizamos la llamada AJAX para obtener las preguntas y respuestas
        $.ajax({
            url: './?action=reportes/get-questions', // El archivo PHP que procesará la solicitud
            type: 'GET',
            data: {
                encuesta_id: encuesta_id,
                personal_id: personal_id
            },
            success: function(response) {
                var result = JSON.parse(response); // Convertir la respuesta JSON a objeto JavaScript
                var preguntasHTML = '';
                
                // Verificar si existen preguntas y respuestas
                if (result.preguntas && result.respuestas) {
                    result.preguntas.forEach(function(pregunta) {
                        // Encontrar la respuesta correspondiente para cada pregunta
                        var respuesta = result.respuestas.find(function(res) {
                            return res.question_id == pregunta.id;
                        });

                        // Crear las filas de la tabla con radio buttons para las respuestas
                        preguntasHTML += `
                            <tr>
                                <td>${pregunta.question_text}</td>
                                <td>
                                    <input disabled type="radio" name="pregunta_${pregunta.id}" value="1" ${respuesta && respuesta.response == 1 ? 'checked' : ''}> Sí
                                </td>
                                <td>
                                    <input disabled type="radio" name="pregunta_${pregunta.id}" value="0" ${respuesta && respuesta.response == 0 ? 'checked' : ''}> No
                                </td>
                            </tr>
                        `;
                    });
                }
                // Insertar las preguntas generadas en la tabla
                $("#tabla_preguntas").html(preguntasHTML);
            },
            error: function() {
                alert('Ocurrió un error al cargar las preguntas y respuestas.');
            }
        });
    }
}

</script>
</body>
</html>
