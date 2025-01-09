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

        // Calcular `Ccat` y asignar niveles específicos para cada categoría
        foreach ($categorias as &$categoria) {
            $Ccat = $categoria['total_valor']; // Total valor para la categoría

            // Evaluar los niveles según el ID de la categoría
            switch ($categoria['categoria_id']) {
                case 1:
                    //Ambiente de trabajo
                    if ($Ccat < 3) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=3 && $Ccat < 5) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=5 && $Ccat < 7) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=7 && $Ccat < 9) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                case 2:
                    //Factores propios de la actividad

                    if ($Ccat < 10) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=10 && $Ccat < 20) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=20 && $Ccat < 30) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=30 && $Ccat < 40) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                case 3:
                    //Organizacion del tiempo de trabajo
                    if ($Ccat < 4) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=4 && $Ccat < 6) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=6 && $Ccat < 9) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=9 && $Ccat < 12) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                case 4:
                    //Liderazgo y relaciones en el trabajo
                    if ($Ccat < 10) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=10 && $Ccat < 18) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=18 && $Ccat < 28) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=28 && $Ccat <38) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                default:
                    $categoria['nivel'] = "Sin Nivel";
                    break;
            }
        }

        return ['categorias' => $categorias];
    }
}


// Función para obtener cálculos de la encuesta 3
function calcularEncuesta3($personal_id, $view_mode) {
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
            WHERE survey_answers.survey_id = 3 
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

        // Calcular `Ccat` y asignar niveles específicos para cada categoría
        foreach ($categorias as &$categoria) {
            $Ccat = $categoria['total_valor']; // Total valor para la categoría

            // Evaluar los niveles según el ID de la categoría
            switch ($categoria['categoria_id']) {
                case 1:
                    //Ambiente de trabajo
                    if ($Ccat < 5) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=5 && $Ccat < 9) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=9 && $Ccat < 11) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=11 && $Ccat < 14) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                case 2:
                    //Factores propios de la actividad
                    if ($Ccat < 15) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=15 && $Ccat < 30) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=30 && $Ccat < 45) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=45 && $Ccat < 60) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;
                case 3:
                    //Organizacion del tiempo de trabajo
                    if ($Ccat < 5) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=5 && $Ccat < 7) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=7 && $Ccat < 10) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=10 && $Ccat < 13) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;

                case 4:
                    //Liderazgo y relaciones en el trabajo
                    if ($Ccat < 14) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=14 && $Ccat < 29) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=29 && $Ccat < 42) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=42 && $Ccat < 58) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;
                case 5:
                   // Entorno organizacional
                    if ($Ccat < 10) $categoria['nivel'] = "Nulo";
                    elseif ($Ccat <=10 && $Ccat < 14) $categoria['nivel'] = "Bajo";
                    elseif ($Ccat <=14 && $Ccat < 18) $categoria['nivel'] = "Medio";
                    elseif ($Ccat <=18 && $Ccat < 23) $categoria['nivel'] = "Alto";
                    else $categoria['nivel'] = "Muy Alto";
                    break;


                default:
                    $categoria['nivel'] = "Sin Nivel";
                    break;
            }
        }

        return ['categorias' => $categorias];
    }
}
if (isset($_GET['personal_id']) && is_numeric($_GET['personal_id']) && isset($_GET['view_mode']) && isset($_GET['survey_id'])) {
    $personal_id = intval($_GET['personal_id']);
    $view_mode = $_GET['view_mode'];
    $survey_id = intval($_GET['survey_id']);

    // Verificar los valores de los parámetros
    var_dump($personal_id, $view_mode, $survey_id);

    if ($survey_id == 2) {
        $response = calcularEncuesta2($personal_id, $view_mode);
    } elseif ($survey_id == 3) {
        $response = calcularEncuesta3($personal_id, $view_mode);
    } else {
        $response = ['error' => 'survey_id inválido.'];
    }

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Parámetros inválidos o faltantes.']);
}
