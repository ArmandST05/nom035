<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Encuestas por Empleado</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .form-group {
            margin: 15px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .col-md-5 {
            width: 45%;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Resultados de Encuestas por Empleado</h1>

    <form id="resultados-form">
        <div class="row">
            <!-- Select para empleados -->
            <div class="col-md-5">
                <label for="personal_id">Seleccionar Empleado:</label>
                <div class="form-group">
                    <select class="form-control" id="personal_id" name="personal_id" required>
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
                    <select class="form-control" id="survey_id" name="survey_id" required>
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

        <table id="tabla_resultados" class="table table-bordered">
            <thead>
                <tr>
                    <th>Dominio</th>
                    
                    <th>Total</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los resultados se cargarán aquí con AJAX -->
            </tbody>
        </table>
    </form>
            <!-- Agregar la librería Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Contenedor del gráfico -->
<canvas id="graficoDominios" width="400" height="200"></canvas>

<script>
        $(document).ready(function () {
            $("#personal_id, #survey_id").change(function () {
                cargarResultados();
            });
        });




function cargarResultados() {
    var encuesta_id = $("#survey_id").val();
    var personal_id = $("#personal_id").val();

    if (encuesta_id && personal_id) {
        $.ajax({
            url: './?action=resultados/get-domain-results',
            type: 'GET',
            data: {
                encuesta_id: encuesta_id,
                personal_id: personal_id
            },
            success: function(response) {
                var data = JSON.parse(response);
                var dominios = [];
                var valores = [];
                var colores = [];

                if (data.dominios) {
                    Object.keys(data.dominios).forEach(function(dominioKey) {
                        var dominio = data.dominios[dominioKey];
                        dominios.push(dominio.dominio_nombre);
                        valores.push(dominio.total_valor);

                        // Asignar colores según el nivel
                        switch (dominio.nivel) {
                            case "Muy Alto":
                                colores.push("rgba(255, 99, 132, 0.8)"); // Rojo
                                break;
                            case "Alto":
                                colores.push("rgba(255, 159, 64, 0.8)"); // Naranja
                                break;
                            case "Medio":
                                colores.push("rgba(255, 205, 86, 0.8)"); // Amarillo
                                break;
                            case "Bajo":
                                colores.push("rgba(75, 192, 192, 0.8)"); // Verde agua
                                break;
                            default: // Nulo
                                colores.push("rgba(54, 162, 235, 0.8)"); // Azul
                        }
                    });
                }

                // Generar el gráfico con los datos
                generarGrafico(dominios, valores, colores);
            },
            error: function() {
                alert('Ocurrió un error al cargar los resultados.');
            }
        });
    }
}

// Función para generar el gráfico
function generarGrafico(labels, data, colors) {
    var ctx = document.getElementById('graficoDominios').getContext('2d');

    // Destruir gráfico existente si ya fue creado
    if (window.dominioChart) {
        window.dominioChart.destroy();
    }

    // Crear el nuevo gráfico
    window.dominioChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Valor Total por Dominio',
                data: data,
                backgroundColor: colors,
                borderColor: colors.map(color => color.replace('0.8', '1')), // Bordes más oscuros
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false // Ocultar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Valor Total: ${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });
}

/*

        $(document).ready(function () {
            $("#personal_id, #survey_id").change(function () {
                cargarResultados();
            });
        });

        function cargarResultados() {
    var encuesta_id = $("#survey_id").val();
    var personal_id = $("#personal_id").val();

    if (encuesta_id && personal_id) {
        // Realizar la llamada AJAX para obtener los resultados
        $.ajax({
            url: './?action=resultados/get-domain-results',
            type: 'GET',
            data: {
                encuesta_id: encuesta_id,
                personal_id: personal_id
            },
            success: function(response) {
                // Parsear el JSON recibido y generar filas de tabla
                var data = JSON.parse(response);
                var html = "";

                if (data.dominios) {
                    // Verifica que 'dominios' es un objeto y recorre sus propiedades
                    Object.keys(data.dominios).forEach(function(dominioKey) {
                        var dominio = data.dominios[dominioKey]; // Obtiene el dominio

                        html += `
                            <tr>
                                <td>${dominio.dominio_nombre}</td>
                                <td>${dominio.total_valor}</td>
                                <td>${dominio.nivel}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = "<tr><td colspan='3'>No hay resultados disponibles.</td></tr>";
                }

                // Insertar el HTML generado en la tabla
                $("#tabla_resultados tbody").html(html);
            },
            error: function() {
                alert('Ocurrió un error al cargar los resultados.');
            }
        });
    }
}
*/


    </script>
</body>
</html>
