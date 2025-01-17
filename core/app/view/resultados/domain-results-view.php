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
                        <option value="todos">Todos los empleados</option>
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
        
        
    </form>
    <canvas id="graficoDominios"></canvas>

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


    // Seleccionar la URL según la opción seleccionada
    var url = '';
    if (personal_id === "todos") {
        // Si se seleccionan todos los empleados
        if (encuesta_id == 2) {
            url = './?action=resultados/get-general-domains-results2'; // URL para resultados generales de encuesta 2
        } else if (encuesta_id == 3) {
            url = './?action=resultados/get-general-domains-results3'; // URL para resultados generales de encuesta 3
        } else {
            console.error("Encuesta no válida.");
            alert("Por favor, selecciona una encuesta válida.");
            return;
        }
    } else {
        // Si se selecciona un empleado específico
        if (encuesta_id == 2) {
            url = './?action=resultados/get-domain-results-survey2'; // URL para resultados por empleado en encuesta 2
        } else if (encuesta_id == 3) {
            url = './?action/resultados/get-domain-results-survey3'; // URL para resultados por empleado en encuesta 3
        } else {
            console.error("Encuesta no válida.");
            alert("Por favor, selecciona una encuesta válida.");
            return;
        }
    }
    $.ajax({
    url: url,
    type: 'GET',
    data: {
        survey_id: encuesta_id,
        personal_id: personal_id
    },
    success: function (response) {
        console.log("Raw response from server:", response);

        try {
            var jsonResponse = response; // No es necesario hacer un trim si es objeto
            var dominios = jsonResponse.dominios;

            // Asegurarse de que "dominios" no esté vacío
            if (dominios && Object.keys(dominios).length > 0) {
                var categoriasLabels = [];
                var categoriasValores = [];
                var categoriasNiveles = [];

                // Extraemos las categorías, valores y niveles
                for (var dominioId in dominios) {
                    if (dominios.hasOwnProperty(dominioId)) {
                        var dominio = dominios[dominioId];
                        categoriasLabels.push(dominio.dominio_nombre);
                        categoriasValores.push(dominio.total_valor);
                        categoriasNiveles.push(dominio.nivel);
                    }
                }

                // Llamar a la función para generar el gráfico de dominios
                generarGrafico(categoriasLabels, categoriasValores, categoriasNiveles);
            } else {
                console.warn("No se encontraron dominios válidos.");
                generarGrafico(["Sin resultados"], [0], ["Nulo"]);
            }
        } catch (error) {
            console.error("Error al procesar la respuesta:", error);
            alert("Error al procesar los datos recibidos del servidor. Revisa la consola para más detalles.");
        }
    },
    error: function (xhr, status, error) {
        console.error("Error en la petición AJAX:");
        console.error("Status:", status);
        console.error("Error:", error);
        console.error("Detalles de la respuesta:", xhr.responseText);
        alert("Ocurrió un error al cargar los resultados.");
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
                return "rgba(255, 128, 0, 0.8)"; // Naranja
            case "Medio":
                return "rgb(251, 255, 0)"; // Amarillo
            case "Bajo":
                return "rgb(0, 255, 115)"; // Verde claro
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
