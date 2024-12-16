<?php
if (!isset($_GET['survey_id']) || !is_numeric($_GET['survey_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de encuesta no válido']);
    exit;
}

$survey_id = intval($_GET['survey_id']);

try {
    // Consulta para contar las respuestas por categoría
    $sql = "SELECT response, COUNT(*) as count 
            FROM survey_answers 
            WHERE survey_id = $survey_id 
            GROUP BY response";

    $query = Executor::doit($sql);
    $results = $query[0]->fetch_all(MYSQLI_ASSOC);

    // Procesar los datos para el gráfico
    $labels = ['Siempre', 'Casi siempre', 'Algunas veces', 'Casi nunca', 'Nunca'];
    $counts = array_fill(0, count($labels), 0); // Inicializar contadores en 0

    foreach ($results as $row) {
        $responseIndex = array_search($row['response'], $labels);
        if ($responseIndex !== false) {
            $counts[$responseIndex] = intval($row['count']);
        }
    }

    // Responder con JSON
    echo json_encode(['status' => 'success', 'data' => ['labels' => $labels, 'counts' => $counts]]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener datos: ' . $e->getMessage()]);
    exit;
}
?>
