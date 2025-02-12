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
                        <option value="todos">Todos los empledos</option>
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
                                if($encuesta->id == 1){
                                    continue;
                                }

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
        <div class="row">
           
        </div>             
        
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


    // Selección de la URL según el ID de la encuesta y si es un cálculo general o por empleado
   // Seleccionar la URL según la opción seleccionada
var url = '';
if (personal_id === "todos") {
    // Si se seleccionan todos los empleados
    if (encuesta_id == 2) {
        url = './?action=resultados/get-general-category-results2'; // URL para resultados generales de encuesta 2
    } else if (encuesta_id == 3) {
        url = './?action=resultados/get-general-category-results3'; // URL para resultados generales de encuesta 3
    } 
} else {
    // Si se selecciona un empleado específico
    if (encuesta_id == 2) {
        url = './?action=resultados/get-category-results-survey2'; // URL para resultados por empleado en encuesta 2
    } else if (encuesta_id == 3) {
        url = './?action/resultados/get-category-results-survey3'; // URL para resultados por empleado en encuesta 3
    } 
}

    // Realizar la petición AJAX
    $.ajax({
        url: url,
        type: 'GET',
        data: {
            survey_id: encuesta_id,
            personal_id: personal_id === 'todos' ? null : personal_id // Enviar null si es "todos"
        },
        success: function (response) {
            console.log("Respuesta cruda del servidor:", response);

            
                // Intentar parsear la respuesta JSON
                var data = JSON.parse(response);
                console.log("Respuesta procesada:", data);

                // Procesar los resultados por categoría
                var categorias = data.categorias;

                if (categorias && Object.keys(categorias).length > 0) {
                    var categoriasLabels = [];
                    var categoriasValores = [];
                    var categoriasNiveles = [];

                    // Extraer etiquetas, valores y niveles de cada categoría
                    for (var categoriaId in categorias) {
                        if (categorias.hasOwnProperty(categoriaId)) {
                            var categoria = categorias[categoriaId];
                            categoriasLabels.push(categoria.categoria_nombre);
                            categoriasValores.push(categoria.total_valor);
                            categoriasNiveles.push(categoria.nivel);
                        }
                    }

                    // Generar el gráfico con los datos procesados
                    generarGrafico(categoriasLabels, categoriasValores, categoriasNiveles);
                } else {
                    console.warn("No se encontraron categorías válidas.");
                    generarGrafico(["Sin resultados"], [0], ["Nulo"]);
                }
            
        },
        error: function (xhr, status, error) {
            console.error("Error en la petición AJAX:");
            console.error("Estado:", status);
            console.error("Error:", error);
            console.error("Detalles de la respuesta:", xhr.responseText);
            alert("Ocurrió un error al cargar los resultados. Por favor, intenta de nuevo más tarde.");
        }
    });
}


function generarGrafico(labels, data, niveles) {
    var ctx = document.getElementById('graficoDominios').getContext('2d');

    // Destruir gráfico existente si ya fue creado
    if (window.dominioChart) {
        window.dominioChart.destroy();
    }

    // Mapear los niveles a colores correspondientes
    var categoriasColores = niveles.map(function(nivel) {
        switch (nivel) {
            case "Muy Alto":
                return "rgba(255, 0, 0, 0.8)"; // Rojo
            case "Alto":
                return "rgba(255, 159, 64, 0.8)"; // Naranja
            case "Medio":
                return "rgb(251, 255, 0)"; // Amarillo
            case "Bajo":
                return "rgba(75, 192, 192, 0.8)"; // Verde claro
            case "Nulo":
                return "rgba(0, 225, 255, 0.8)"; // Gris
            default:
                return "rgba(0, 0, 0, 0.8)"; // Negro (si no se encuentra el nivel)
        }
    });

    // Crear el nuevo gráfico
    window.dominioChart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico (barras en este caso)
        data: {
            labels: labels, // Etiquetas de las categorías
            datasets: [{
                label: 'Valor Total', // Título de la gráfica
                data: data, // Los valores para cada categoría
                backgroundColor: categoriasColores, // Colores calculados para cada barra
                borderColor: categoriasColores.map(color => color.replace('0.8', '1')), // Borde con opacidad completa
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Hacer que el gráfico sea responsivo
            scales: {
                y: {
                    beginAtZero: true // Iniciar el eje Y desde cero
                }
            },
            plugins: {
                legend: {
                    display: false // Desactivar la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Valor Total: ${tooltipItem.raw}`; // Mostrar valor total en el tooltip
                        }
                    }
                }
            }
        }
    });
}

    </script>
</body>
</html>
