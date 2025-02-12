<?php
// Obtener los datos de encuestas asignadas con su estado
$datos = EncuestaData::getAllSurveyStatuses();

// Agrupar los datos en un formato más estructurado
$empleados = [];
$encuestas = [];

foreach ($datos as $dato) {
    $empleados[$dato->personal_id]['nombre'] = $dato->nombre;
    $empleados[$dato->personal_id]['encuestas'][$dato->survey_id] = $dato->completed == 1 ? 'Terminado' : 'Pendiente';
    
    // Guardamos las encuestas únicas para el encabezado de la tabla
    if (!isset($encuestas[$dato->survey_id])) {
        $encuestas[$dato->survey_id] = $dato->title;
    }
}
?>
<table border="2" class="table table-bordered"> 
    <thead>
        <tr>
            <th>Empleado</th>
            <?php foreach ($encuestas as $surveyId => $title): ?>
                <th><?php echo htmlspecialchars($title); ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($empleados as $empleado): ?>
            <tr>
                <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                <?php foreach ($encuestas as $surveyId => $title): ?>
                    <td>
                        <?php 
                            echo isset($empleado['encuestas'][$surveyId]) 
                                ? $empleado['encuestas'][$surveyId] 
                                : 'Pendiente'; 
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
