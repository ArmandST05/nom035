<?php
$encuestas = EncuestaData::getAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Periodos</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Gestión de Periodos</h1>
        <form id="period-form" method="POST" action="assign_periods.php">
            <div class="row mb-3">
                <!-- Nombre del periodo -->
                <div class="col-md-4">
                    <label for="period-name" class="form-label">Nombre del Periodo</label>
                    <input type="text" id="period-name" name="period_name" class="form-control" required>
                </div>

                <!-- Fechas del periodo -->
                <div class="col-md-3">
                    <label for="start-date" class="form-label">Fecha de Inicio</label>
                    <input type="date" id="start-date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="end-date" class="form-label">Fecha de Fin</label>
                    <input type="date" id="end-date" name="end_date" class="form-control" required>
                </div>
            </div>

            <!-- Encuestas con Checkbox -->
            <div class="mb-3">
                <label class="form-label">Encuestas a Asignar</label>
                <div>
                    <?php foreach ($encuestas as $encuesta): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="survey<?php echo $encuesta->id; ?>" name="survey_ids[]" value="<?php echo $encuesta->id; ?>">
                            <label class="form-check-label" for="survey<?php echo $encuesta->id; ?>"><?php echo htmlspecialchars($encuesta->title); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="text-muted">Selecciona las encuestas que deseas asignar.</small>
            </div>
              <!-- Filtrar por departamento y puesto (en 2 columnas) -->
              <div class="row mb-3">
                <div class="col-md-4">
                    <label for="department" class="form-label">Departamento</label>
                    <select id="department" name="department_id"class="form-control">
                        <option value="">Todos los departamentos</option>
                        <?php
                        $departamentos = DepartamentoData::getAll();
                        foreach ($departamentos as $departamento) {
                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <label for="employeeRole" class="form-label">Puesto</label>
                        <select class="form-control" id="employeeRole" name="role">
                            <option value="">Seleccione un puesto</option>
                            <?php
                            $puestos = PuestoData::getAll();
                            foreach ($puestos as $puesto) {
                                echo "<option value='{$puesto->id}'>{$puesto->nombre}</option>";
                            }
                            ?>
                        </select>
                            </div>
                        </div>
                    </div>
                </div>                   
            

            <!-- Botón de enviar -->
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Asignar Encuestas</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
