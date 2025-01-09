<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Encuestas por Empleado</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
       
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
             <!-- Selección de vista por categoría o dominio -->
             <div class="col-md-5">
                <label for="view_mode">Ver resultados por:</label>
                <div class="form-group">
                    <select class="form-control" id="view_mode" name="view_mode" onchange="cargarResultados()">
                        <option value="dominio">Dominio</option>
                        <option value="categoria">Categoría</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
           
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
    var view_mode = $("#view_mode").val(); // Ver si es categoría o dominio

    if (encuesta_id && personal_id) {
        $.ajax({
            url: './?action=resultados/get-domain-results',
            type: 'GET',
            data: {
                encuesta_id: encuesta_id,
                personal_id: personal_id,
                view_mode: view_mode
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    

                    var labels = [];
                    var valores = [];
                    var colores = [];
                    var agrupacion = view_mode === "categoria" ? data.categorias : data.dominios;
                    console.log(data);
                    if (agrupacion) {
                        Object.keys(agrupacion).forEach(function(key) {
                            var item = agrupacion[key];
                            console.log('Item:', item);

                            // Ajusta según las claves del JSON
                            labels.push(item.dominio_nombre || item.categoria_nombre);
                            valores.push(item.total_valor);

                            // Asignar colores según el nivel
                            switch (item.nivel) {
                                case "Muy Alto":
                                    colores.push("rgba(255, 0, 55, 0.8)");
                                    break;
                                case "Alto":
                                    colores.push("rgba(255, 128, 0, 0.8)");
                                    break;
                                case "Medio":
                                    colores.push("rgba(255, 221, 0, 0.8)");
                                    break;
                                case "Bajo":
                                    colores.push("rgb(0, 255, 255)");
                                    break;
                                default:
                                    colores.push("rgba(0, 149, 255, 0.8)");
                            }
                        });
                    } else {
                        labels.push("Sin resultados");
                        valores.push(0);
                        colores.push("rgba(200, 200, 200, 0.8)");
                    }

                    // Generar o actualizar gráfico
                    generarGrafico(labels, valores, colores);
                } catch (error) {
                    console.error('Error al procesar la respuesta:', error);
                }
            },
            error: function() {
                alert('Ocurrió un error al cargar los resultados.');
            }
        });
    }
}

// Función para generar o actualizar el gráfico
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
                label: 'Valor Total',
                data: data,
                backgroundColor: colors,
                borderColor: colors.map(color => color.replace('0.8', '1')),
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
                    display: false
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
