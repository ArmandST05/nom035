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
    console.log("personal_id:", personal_id);
    var view_mode = $("#view_mode").val(); // Ver si es categoría o dominio
    console.log("view_mode:", view_mode); // Verifica el valor de view_mode

    if (encuesta_id && personal_id) {
        $.ajax({
            url: './?action=resultados/get-domain-results',
            type: 'GET',
            data: {
                survey_id: encuesta_id,
                personal_id: personal_id,
                view_mode: view_mode
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);  // Intentar analizarlo
                    console.log("Respuesta completa del servidor:", data);

                    var labels = [];
                    var valores = [];
                    var colores = [];
                    var agrupacion = {};

                    // Verifica si 'view_mode' es 'categoria'
                    if (view_mode === "categoria") {
                        console.log("Verificando si data.categorias existe...");
                        if (data.categorias) {
                            agrupacion = data.categorias;
                        } else {
                            console.warn("No se encontró 'data.categorias' en la respuesta.");
                        }
                    } else if (view_mode === "dominio") {
                        console.log("Verificando si data.dominios existe...");
                        if (data.dominios) {
                            agrupacion = data.dominios;
                        } else {
                            console.warn("No se encontró 'data.dominios' en la respuesta.");
                        }
                    } else {
                        console.warn("view_mode no es válido:", view_mode);
                    }

                    console.log("Agrupación seleccionada:", agrupacion); // Ver la agrupación obtenida

                    if (Object.keys(agrupacion).length === 0) {
                        console.warn("No se encontraron categorías o dominios válidos.");
                    }

                    if (agrupacion) {
                        Object.keys(agrupacion).forEach(function(key) {
                            var item = agrupacion[key];
                            // Ajusta según las claves del JSON
                            labels.push(item.categoria_nombre || item.dominio_nombre);
                            valores.push(item.total_valor);

                            // Asignar el nivel según el valor de total_valor
                            var nivel = "";
                            if (item.categoria_id == 1) { // Ambiente de trabajo
                                if (item.total_valor < 3) {
                                    nivel = "Nulo";
                                } else if (item.total_valor < 5) {
                                    nivel = "Bajo";
                                } else if (item.total_valor < 7) {
                                    nivel = "Medio";
                                } else if (item.total_valor < 9) {
                                    nivel = "Alto";
                                } else {
                                    nivel = "Muy Alto";
                                }
                            } else if (item.categoria_id == 3) { // Factores propios de la actividad
                                if (item.total_valor < 10) {
                                    nivel = "Nulo";
                                } else if (item.total_valor < 20) {
                                    nivel = "Bajo";
                                } else if (item.total_valor < 30) {
                                    nivel = "Medio";
                                } else if (item.total_valor < 40) {
                                    nivel = "Alto";
                                } else {
                                    nivel = "Muy Alto";
                                }
                            } else if (item.categoria_id == 4) { // Organización del tiempo de trabajo
                                if (item.total_valor < 4) {
                                    nivel = "Nulo";
                                } else if (item.total_valor < 6) {
                                    nivel = "Bajo";
                                } else if (item.total_valor < 9) {
                                    nivel = "Medio";
                                } else if (item.total_valor < 12) {
                                    nivel = "Alto";
                                } else {
                                    nivel = "Muy Alto";
                                }
                            } else if (item.categoria_id == 5) { // Liderazgo y relaciones en el trabajo
                                if (item.total_valor < 10) {
                                    nivel = "Nulo";
                                } else if (item.total_valor < 18) {
                                    nivel = "Bajo";
                                } else if (item.total_valor < 28) {
                                    nivel = "Medio";
                                } else if (item.total_valor < 38) {
                                    nivel = "Alto";
                                } else {
                                    nivel = "Muy Alto";
                                }
                            } else {
                                nivel = "Sin Nivel";
                            }

                            // Asignar el nivel calculado al objeto
                            item.nivel = nivel;

                            // Asignar colores según el nivel
                            switch (nivel) {
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

                            console.log("Nivel calculado para", item.categoria_nombre || item.dominio_nombre, ":", nivel);
                        });
                    } else {
                        // Si no hay agrupación, mostrar resultados vacíos
                        labels.push("Sin resultados");
                        valores.push(0);
                        colores.push("rgba(200, 200, 200, 0.8)");
                    }

                    // Generar o actualizar gráfico
                    generarGrafico(labels, valores, colores);
                } catch (error) {
                    console.error('Error al procesar la respuesta:', error);
                    alert('Error al procesar los datos recibidos del servidor. Revisa la consola para más detalles.');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:");
                console.error("Status:", status);
                console.error("Error:", error);
                console.error("Detalles de la respuesta:", xhr.responseText);
                alert('Ocurrió un error al cargar los resultados.');
            }
        });
    } else {
        console.error("Parámetros inválidos: encuesta_id y personal_id son requeridos.");
        alert("Por favor, asegúrate de seleccionar una encuesta y un empleado.");
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
