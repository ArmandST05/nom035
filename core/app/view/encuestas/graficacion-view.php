

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtros para Gráficos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1 style="text-align: center;">Gráficos con Filtros</h1>

    <form id="filterForm" class="p-4 border rounded shadow-sm" style="width: 80%; margin: 0 auto;">
    <div class="row mb-3">
        <!-- Filtro de encuesta -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="survey" class="form-label">Encuesta</label>
                <select id="survey" name="survey" class="form-control">
                    <option value="">Seleccione una encuesta</option>
                    <?php 
                        $encuestas = EncuestaData::getAll();
                        if(!empty($encuestas)){
                            foreach($encuestas as $encuesta){
                                echo "<option value='" . $encuesta->id . "'>" . $encuesta->title . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Filtro de departamento -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="department" class="form-label">Departamento</label>
                <select id="department" name="department" class="form-control">
                    <option value="all">Selecciona un departamento</option>
                    <?php 
                        $departamento = DepartamentoData::getAll();
                        if (!empty($departamento)) {
                            foreach ($departamento as $departamentos) {
                                echo "<option value='" . $departamentos->idDepartamento . "'>" . $departamentos->nombre . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Filtro de puesto -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="role" class="form-label">Puesto</label>
                <select id="role" name="role" class="form-control">
                    <option value="">Seleccione un puesto</option>
                    <!-- Los puestos se cargarán aquí dinámicamente con AJAX -->
                </select>
            </div>
        </div>

        <!-- Filtro de personal -->
        <div class="col-md-6">
            <div class="form-group">
            <button type="button" id="applyFilters" class="btn btn-primary ">Aplicar Filtros</button>

            </div>
        </div>
    </div>

    <!-- Botón para aplicar filtros -->
    <div class="text-center">
    </div>
</form>


    <!-- Contenedor para el gráfico -->
    <div style="width: 80%; margin: 50px auto;">
        <canvas id="filteredChart"></canvas>
    </div>

    <script>
 
$(document).ready(function () {
        $('#department').change(function () {
            var departmentId = $(this).val(); 

            if (departmentId) {
                $.ajax({
                    url: './?action=puestos/get-by-department', 
                    method: 'GET',
                    data: { department_id: departmentId }, // Enviar el ID del departamento
                    dataType: 'json',
                    success: function (response) {
                        // Limpiar el select de puestos antes de agregar nuevas opciones
                        $('#role').empty();
                        $('#role').append('<option value="">Seleccione un puesto</option>'); // Agregar la opción predeterminada

                        // Verificar si hay puestos disponibles
                        if (response.status === 'success') {
                            // Iterar a través de los puestos y agregarlos al select de puestos
                            response.data.forEach(function (puesto) {
                                $('#role').append('<option value="' + puesto.id + '">' + puesto.nombre + '</option>');
                            });
                        } else {
                            alert('No se encontraron puestos para este departamento.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar los puestos:', error);
                    }
                });
            } else {
                // Si no hay departamento seleccionado, limpiar el select de puestos
                $('#role').empty();
                $('#role').append('<option value="">Seleccione un puesto</option>');
            }
        });
    });
        $(document).ready(function () {
            $('#applyFilters').on('click', function () {
                // Obtener valores del formulario
                const filters = {
                    survey: $('#survey').val(),
                    department: $('#department').val(),
                    position: $('#role').val(),
                    
                };

                // Validar filtros (opcional)
                if (!filters.survey) {
                    alert('Por favor, selecciona una encuesta.');
                    return;
                }

                // Solicitud AJAX al servidor para obtener datos filtrados
                $.ajax({
                    url: './?action=encuestas/assign-survey', // URL a tu archivo PHP
                    method: 'GET',
                    data: filters,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            renderChart(response.data.labels, response.data.counts);
                        } else {
                            alert('Error al cargar datos: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud AJAX:', error);
                    }
                });
            });

            // Función para renderizar el gráfico con Chart.js
            function renderChart(labels, data) {
                const ctx = document.getElementById('filteredChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // Cambiar a 'pie', 'line', etc., si prefieres
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Respuestas',
                            data: data,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 99, 132, 0.6)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Resultados Filtrados' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
