<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $period_id = intval($_GET['id']); // Aseguramos que el ID es un número entero
    $periodo = PeriodoData::getById($period_id);

    if ($periodo) {
        // Devolver la información del periodo en formato JSON
        echo json_encode([
            "id" => $periodo->id,
            "name" => $periodo->name,
            "start_date" => $periodo->start_date,
            "end_date" => $periodo->end_date,
            "status" => $periodo->status,
        ]);
    } else {
        // Mensaje de error si no se encuentra el periodo
        echo json_encode(["error" => "No se encontró el registro."]);
    }
} else {
    // Mensaje de error si el ID no es válido
    echo json_encode(["error" => "ID no válido."]);
}
