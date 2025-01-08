<?php
// Verificar si se recibió la solicitud correcta
if (isset($_GET['personal_id']) && is_numeric($_GET['personal_id'])) {
    $personal_id = intval($_GET['personal_id']); // Sanitizar el valor recibido

    // Consulta para obtener los valores por dominio
    $sql = "
        SELECT 
            category.id AS categoria_id, 
            category.name AS categoria_nombre, 
            dominio.id AS dominio_id, 
            dominio.name AS dominio_nombre, 
            dimensiones.id AS dimension_id, 
            dimensiones.name AS dimension_nombre, 
            psychosocial_risk_questions.id AS pregunta_id, 
            psychosocial_risk_questions.text AS pregunta_texto, 
            survey_answers.valor AS respuesta_valor 
        FROM survey_answers 
        INNER JOIN psychosocial_risk_questions 
            ON survey_answers.question_id = psychosocial_risk_questions.id 
        INNER JOIN dimensiones 
            ON psychosocial_risk_questions.id_dimension = dimensiones.id 
        INNER JOIN dominio 
            ON dimensiones.dominio_id = dominio.id 
        INNER JOIN category 
            ON dominio.category_id = category.id 
        WHERE survey_answers.survey_id IN (2, 3) 
          AND survey_answers.personal_id = $personal_id;
    ";

    // Ejecutar la consulta
    $result = Executor::doit($sql);

    // Verificar si se obtuvieron resultados
    $dominios = [];
    if ($result && $result[0]) {
        while ($row = $result[0]->fetch_assoc()) {
            $dominio_id = $row['dominio_id'];

            // Agrupar valores por dominio
            if (!isset($dominios[$dominio_id])) {
                $dominios[$dominio_id] = [
                    'dominio_nombre' => $row['dominio_nombre'],
                    'dimension_nombre' => $row['dimension_nombre'],
                    'total_valor' => 0,
                    'preguntas' => [],
                ];
            }

            // Sumar valores de respuesta al total del dominio
            $dominios[$dominio_id]['total_valor'] += $row['respuesta_valor'];
            $dominios[$dominio_id]['preguntas'][] = [
                'pregunta_id' => $row['pregunta_id'],
                'pregunta_texto' => $row['pregunta_texto'],
                'respuesta_valor' => $row['respuesta_valor'],
            ];
        }
    }

    // Calcular el resultado por dominio
    foreach ($dominios as &$dominio) {
        $total_valor = $dominio['total_valor'];

        if ($total_valor >= 90) {
            $dominio['nivel'] = "Muy Alto";
        } elseif ($total_valor >= 70) {
            $dominio['nivel'] = "Alto";
        } elseif ($total_valor >= 45) {
            $dominio['nivel'] = "Medio";
        } elseif ($total_valor >= 20) {
            $dominio['nivel'] = "Bajo";
        } else {
            $dominio['nivel'] = "Nulo";
        }
    }

    // Devolver los resultados como JSON
    echo json_encode(['dominios' => $dominios]);
    exit;
} else {
    // Respuesta en caso de solicitud inválida
    echo json_encode(['error' => 'Parámetro personal_id inválido o faltante.']);
    exit;
}
?>
