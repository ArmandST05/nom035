<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Encuestas NOM-035</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>


     
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }


        th {
            font-weight: bold;
        }
        td:first-child {
            text-align: left; /* Alinear las preguntas a la izquierda para mejor legibilidad */
        }

        /* Ajusta el ancho de las columnas */
        th:nth-child(1), td:nth-child(1) {
            width: 70%; /* 70% del ancho para la columna de preguntas */
        }

        th:nth-child(2), td:nth-child(2),
        th:nth-child(3), td:nth-child(3) {
            width: 15%; /* 15% del ancho para las columnas de Sí y No */
        }

        input[type="radio"] {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <h1>Reportes por Empleado</h1>

    <form method="POST" action="index.php?action=reportes/get-questions">
    <div class="row">
    <!-- Select para empleados -->
    <div class="col-md-5">
        <label for="personal_id">Seleccionar Empleado:</label>
        <div class="form-group">
            <select class="form-control" id="personal_id" name="personal_id" required onchange="cargarPreguntas()">
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
        </div>
    </div>

    <!-- Select para encuestas -->
    <div class="col-md-5">
        <label for="survey_id">Seleccionar Encuesta:</label>
        <div class="form-group">
            <select class="form-control" id="survey_id" name="survey_id" required onchange="cargarPreguntas()">
                <option value="">Selecciona una encuesta</option>
                <?php 
                $encuestas = EncuestaData::getAll();
                if (!empty($encuestas)) {
                    foreach ($encuestas as $encuesta) {
                        echo "<option value='{$encuesta->id}'>{$encuesta->title}</option>";
                    }
                } else {
                    echo "<option value=''>No hay encuestas disponibles</option>";
                }
                ?>
            </select>
        </div>
    </div>
</div>

        

        <table id="tabla_preguntas" class="table table-bordered">
            <thead>
                <tr>
                    
                </tr>
            </thead>
            <tbody>
                <!-- Las preguntas se cargarán aquí con AJAX -->
            </tbody>
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
                url: encuesta_id == 2 || encuesta_id == 3 ? './?action=reportes/get-questions-multiple' : './?action=reportes/get-questions',
                type: 'GET',
                data: {
                    encuesta_id: encuesta_id,
                    personal_id: personal_id
                },
                success: function(response) {
                    $("#tabla_preguntas tbody").html(response); // Insertar las filas correctamente
                },

                error: function () {
                    alert('Ocurrió un error al cargar las preguntas y respuestas.');
                }
            });
        }
    }
</script>

</body>
</html>
