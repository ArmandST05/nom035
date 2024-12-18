<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficas Encuestas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        button{
            background-color:rgb(70, 166, 250);
            border-radius: 6px;
            border-color: none;
            margin: 5px;
            padding: 10px 15px;
            cursor: pointer;
        }
        button:hover{
            background-color:rgb(31, 139, 234);
            border-radius: 6px;
            border-color: none;
        }
        .section {
            display: none;
            transition: opacity 0.5s ease-in-out;
           
            
        }
        .active {
            display: block;
            opacity: 1;
        }
        nav {
            text-align: center;
            margin-bottom: 5px;
        }

       

    </style>
</head>
<body>

    <!-- Menú de navegación -->
    <nav>
        <button class="nav-link" data-section="encuesta1">Encuesta 1</button>
        <button class="nav-link" data-section="encuesta2">Encuesta 2</button>
    </nav>
    <div style="text-align: center; margin-top: 20px;">
        <button id="barChartButton" onclick="changeChartType('bar')">Gráfico de Barras</button>
        <button id="pieChartButton" onclick="changeChartType('pie')">Gráfico de Pastel</button>
    </div>
    <!-- Sección Encuesta 1 -->
    <div id="encuesta1" class="section active">
        <h2 style="text-align: center;">Gráfica Encuesta 1</h2>
        <canvas id="chartEncuesta1"></canvas>
    </div>

    <!-- Sección Encuesta 2 -->
    <div id="encuesta2" class="section">
        <h2 style="text-align: center;">Gráfica Encuesta 2</h2>
        <canvas id="chartEncuesta2"></canvas>
    </div>

    
   
    
    <script>
        let chartInstances = {}; // Variable global para almacenar gráficos
        let currentChartType = 'bar'; // Tipo de gráfico predeterminado

        $(document).ready(function () {
            // Evento de navegación entre secciones
            $('.nav-link').click(function (e) {
                e.preventDefault();
                const sectionId = $(this).data('section');

                // Mostrar la sección activa
                $('.section').removeClass('active').hide();
                $(`#${sectionId}`).addClass('active').fadeIn();

                // Cargar la gráfica correspondiente
                loadChart(sectionId);
            });

            // Función para cargar datos y renderizar la gráfica
            function loadChart(sectionId) {
                let surveyId = sectionId === 'encuesta1' ? 1 : 2;

                $.ajax({
                    url: './?action=encuestas/get-survey-data',
                    method: 'GET',
                    data: { survey_id: surveyId },
                    dataType: 'json',
                    success: function (response) {
                        console.log('Datos recibidos:', response);
                        if (response.status === 'success') {
                            renderChart(sectionId, response.data.labels, response.data.counts, currentChartType);
                        } else {
                            alert('Error al cargar datos: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud AJAX:', error);
                    }
                });
            }

            // Función para renderizar el gráfico
            function renderChart(sectionId, labels, data, chartType) {
                const ctx = document.getElementById(`chart${capitalize(sectionId)}`).getContext('2d');

                // Destruir gráfico anterior si existe
                if (chartInstances[sectionId]) {
                    chartInstances[sectionId].destroy();
                }

                // Colores predefinidos
                const colors = [
                    'rgba(54, 162, 235, 0.6)',  // Azul
                    'rgba(255, 99, 132, 0.6)',  // Rojo
                    'rgba(255, 206, 86, 0.6)',  // Amarillo
                    'rgba(75, 192, 192, 0.6)',  // Verde
                    'rgba(153, 102, 255, 0.6)', // Morado
                    'rgba(255, 159, 64, 0.6)',  // Naranja
                    'rgba(199, 199, 199, 0.6)', // Gris claro
                    'rgba(255, 140, 0, 0.6)'    // Ámbar
                ];

                const borderColors = [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(199, 199, 199, 1)',
                    'rgba(255, 140, 0, 1)'
                ];

                // Generar colores dinámicos según la cantidad de datos
                const backgroundColors = labels.map((_, index) => colors[index % colors.length]);
                const borderColorSet = labels.map((_, index) => borderColors[index % borderColors.length]);

                // Crear nuevo gráfico
                chartInstances[sectionId] = new Chart(ctx, {
                    type: chartType, // Usar el tipo de gráfico dinámico
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Número de respuestas',
                            data: data,
                            backgroundColor: backgroundColors, // Usar colores dinámicos
                            borderColor: borderColorSet, // Bordes dinámicos
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: `Resultados ${capitalize(sectionId)}` }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Función para capitalizar texto
            function capitalize(text) {
                return text.charAt(0).toUpperCase() + text.slice(1);
            }

            // Función para cambiar el tipo de gráfico
            window.changeChartType = function(type) {
                currentChartType = type; // Actualizamos el tipo de gráfico
                const activeSection = $('.section.active').attr('id'); // Obtenemos la sección activa
                loadChart(activeSection); // Recargamos el gráfico con el nuevo tipo
            }

            // Cargar la gráfica de la encuesta 1 al inicio
            loadChart('encuesta1');
        });
    </script>
</body>
</html>
