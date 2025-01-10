<?php
function calcularEncuesta2($personal_id, $view_mode) {
    if ($view_mode === 'dominio') {
        // Consulta para obtener valores agrupados por dominio
        $sql = "SELECT 
                dominio.id AS dominio_id, 
                dominio.name AS dominio_nombre, 
                dimensiones.id AS dimension_id, 
                dimensiones.name AS dimension_nombre, 
                survey_answers.valor AS respuesta_valor 
            FROM survey_answers 
            INNER JOIN psychosocial_risk_questions 
                ON survey_answers.question_id = psychosocial_risk_questions.id 
            INNER JOIN dimensiones 
                ON psychosocial_risk_questions.id_dimension = dimensiones.id 
            INNER JOIN dominio 
                ON dimensiones.dominio_id = dominio.id 
            WHERE survey_answers.survey_id = 2 
              AND survey_answers.personal_id = $personal_id
        ";

        $result = Executor::doit($sql);

        $dominios = [];
        if ($result && $result[0]) {
            while ($row = $result[0]->fetch_assoc()) {
                $dominio_id = $row['dominio_id'];
                if (!isset($dominios[$dominio_id])) {
                    $dominios[$dominio_id] = [
                        'dominio_nombre' => $row['dominio_nombre'],
                        'total_valor' => 0,
                    ];
                }
                $dominios[$dominio_id]['total_valor'] += $row['respuesta_valor'];
            }
        }

        foreach ($dominios as &$dominio) {
            $total_valor = $dominio['total_valor'];
            if ($total_valor >= 90) $dominio['nivel'] = "Muy Alto";
            elseif ($total_valor >= 70) $dominio['nivel'] = "Alto";
            elseif ($total_valor >= 45) $dominio['nivel'] = "Medio";
            elseif ($total_valor >= 20) $dominio['nivel'] = "Bajo";
            else $dominio['nivel'] = "Nulo";
        }

        return ['dominios' => $dominios];
    } elseif ($view_mode === 'categoria') {
        // Consulta para obtener valores agrupados por categoría
        $sql = "SELECT 
                category.id AS categoria_id, 
                category.name AS categoria_nombre, 
                dominio.id AS dominio_id, 
                dimensiones.id AS dimension_id, 
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
            WHERE survey_answers.survey_id = 2 
              AND survey_answers.personal_id = $personal_id;
        ";

        $result = Executor::doit($sql);

        $categorias = [];
        if ($result && $result[0]) {
            while ($row = $result[0]->fetch_assoc()) {
                $categoria_id = $row['categoria_id'];
                if (!isset($categorias[$categoria_id])) {
                    $categorias[$categoria_id] = [
                        'categoria_nombre' => $row['categoria_nombre'],
                        'total_valor' => 0,
                    ];
                }
                $categorias[$categoria_id]['total_valor'] += $row['respuesta_valor'];
            }
        }
       
        foreach ($categorias as &$categoria) {
            if (!isset($categoria['categoria_id'])) {
                $categoria['nivel'] = "Nivel de prueba";  // Opción por defecto
                continue;
            }
            
            // Comprobar el valor de total_valor y asignar el nivel según las categorías
            if ($categoria['categoria_id'] == 1) { // Ambiente de trabajo
                if ($categoria['total_valor'] < 3) {
                    $categoria['nivel'] = "Nulo";
                } elseif ($categoria['total_valor'] < 5) {
                    $categoria['nivel'] = "Bajo";
                } elseif ($categoria['total_valor'] < 7) {
                    $categoria['nivel'] = "Medio";
                } elseif ($categoria['total_valor'] < 9) {
                    $categoria['nivel'] = "Alto";
                } else {
                    $categoria['nivel'] = "Muy Alto";
                }
            } elseif ($categoria['categoria_id'] == 3) { // Factores propios de la actividad
                if ($categoria['total_valor'] < 10) {
                    $categoria['nivel'] = "Nulo";
                } elseif ($categoria['total_valor'] < 20) {
                    $categoria['nivel'] = "Bajo";
                } elseif ($categoria['total_valor'] < 30) {
                    $categoria['nivel'] = "Medio";
                } elseif ($categoria['total_valor'] < 40) {
                    $categoria['nivel'] = "Alto";
                } else {
                    $categoria['nivel'] = "Muy Alto";
                }
            } elseif ($categoria['categoria_id'] == 4) { // Organización del tiempo de trabajo
                if ($categoria['total_valor'] < 4) {
                    $categoria['nivel'] = "Nulo";
                } elseif ($categoria['total_valor'] < 6) {
                    $categoria['nivel'] = "Bajo";
                } elseif ($categoria['total_valor'] < 9) {
                    $categoria['nivel'] = "Medio";
                } elseif ($categoria['total_valor'] < 12) {
                    $categoria['nivel'] = "Alto";
                } else {
                    $categoria['nivel'] = "Muy Alto";
                }
            } elseif ($categoria['categoria_id'] == 5) { // Liderazgo y relaciones en el trabajo
                if ($categoria['total_valor'] < 10) {
                    $categoria['nivel'] = "Nulo";
                } elseif ($categoria['total_valor'] < 18) {
                    $categoria['nivel'] = "Bajo";
                } elseif ($categoria['total_valor'] < 28) {
                    $categoria['nivel'] = "Medio";
                } elseif ($categoria['total_valor'] < 38) {
                    $categoria['nivel'] = "Alto";
                } else {
                    $categoria['nivel'] = "Muy Alto";
                }
            }
        }
        
        
        return ['categorias' => $categorias];
    }
}

function calcularEncuesta3(){

}
if (isset($_GET['personal_id']) && 
    is_numeric($_GET['personal_id']) 
    && isset($_GET['view_mode']) 
    && isset($_GET['survey_id'])) {
    $personal_id = intval($_GET['personal_id']);
    $view_mode = $_GET['view_mode'];
    $survey_id = intval($_GET['survey_id']);

 
    if ($survey_id == 2) {
        $response = calcularEncuesta2($personal_id, $view_mode);
    } elseif ($survey_id == 3) {
        $response = calcularEncuesta3($personal_id, $view_mode);
    } else {
        $response = ['error' => 'survey_id inválido.'];
    }
    $response = trim(json_encode($response));  // Elimina cualquier espacio o salto de línea

    echo json_encode($response);
} else {
    error_log(json_encode($response));
    echo json_encode(['error' => 'Parámetros inválidos o faltantes.']);
}
