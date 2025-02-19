<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

$conn = Database::getCon(); // Conexión a la BD

// Verificar si se recibió la solicitud correcta
if (isset($_GET['encuesta_id']) && isset($_GET['personal_id'])) {
    $encuesta_id = $_GET['encuesta_id'];
    $personal_id = $_GET['personal_id'];

    // Obtener el nombre de la encuesta
    $encuesta_sql = "SELECT title FROM surveys WHERE id = $encuesta_id";
    $encuesta_result = Executor::doit($encuesta_sql);
    $encuesta_nombre = "Encuesta"; // Valor por defecto en caso de error
    if ($encuesta_result && $encuesta_result[0]) {
        $row = $encuesta_result[0]->fetch_assoc();
        $encuesta_nombre = $row['title'];
    }

    // Obtener el nombre del empleado y su empresa
    $empleado_sql = "SELECT personal.nombre, empresas.logo FROM personal 
                     INNER JOIN empresas ON personal.empresa_id = empresas.id 
                     WHERE personal.id = $personal_id";
    $empleado_result = Executor::doit($empleado_sql);

    $empleado_nombre = "Empleado"; // Valor por defecto
    $logo_empleado = "";

    if ($empleado_result && $empleado_result[0]) {
        $row = $empleado_result[0]->fetch_assoc();
        $empleado_nombre = $row['nombre'];
        $logo_empleado = $row['logo'];
    }

    $nombre_archivo = "Reporte_" . str_replace(" ", "_", $encuesta_nombre) . "_" . str_replace(" ", "_", $empleado_nombre) . ".pdf";
    
    // Definir estilos para el PDF
    $html = "<style>
    body { font-family: Arial, sans-serif; font-size: medium; }
    .header { text-align: center; margin-bottom: 20px; }
    .header img { width: 150px; height: auto; display: block; margin: 0 auto; }
    h2 { text-align: center; }
    </style>";

    // Obtener la ruta absoluta del logo
    $ruta_logo = __DIR__ . "/../../../../" . $logo_empleado;

    // Agregar encabezado con el logo y nombre del empleado
    $html .= "<div class='header'>";
    if (file_exists($ruta_logo)) {
        $html .= "<img src='$ruta_logo' alt='Logo de la empresa'>";
    } else {
        $html .= "<p style='color: red;'>Logo no encontrado</p>";
    }
    $html .= "<h2>Reporte de Encuesta</h2>";
    $html .= "<h3>Empleado: $empleado_nombre</h3>";
    $html .= "</div>";

    // Obtener preguntas y respuestas según el tipo de encuesta
    if ($encuesta_id == 1) {
        $preguntas_sql = "SELECT id, question_text FROM survey_questions WHERE survey_id = $encuesta_id";
        $preguntas_result = Executor::doit($preguntas_sql);
        $preguntas = [];
        if ($preguntas_result && $preguntas_result[0]) {
            while ($row = $preguntas_result[0]->fetch_assoc()) {
                $preguntas[$row['id']] = $row['question_text'];
            }
        }
        $respuestas_sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
        $respuestas_result = Executor::doit($respuestas_sql);
        $respuestas = [];
        if ($respuestas_result && $respuestas_result[0]) {
            while ($row = $respuestas_result[0]->fetch_assoc()) {
                $respuestas[$row['question_id']] = $row['response'];
            }
        }
        
        $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
                    <thead>
                        <tr><th>Preguntas</th><th>Sí</th><th>No</th></tr>
                    </thead><tbody>";

        foreach ($preguntas as $id => $texto) {
            $checked_si = (isset($respuestas[$id]) && $respuestas[$id] == "1") ? "✔" : "";
            $checked_no = (isset($respuestas[$id]) && $respuestas[$id] == "0") ? "✔" : "";
            $html .= "<tr><td>{$texto}</td><td style='text-align: center;'>$checked_si</td><td style='text-align: center;'>$checked_no</td></tr>";
        }
        $html .= "</tbody></table>";
    }
    
    if ($encuesta_id == 2 || $encuesta_id == 3) {
        $preguntas_sql = "SELECT id, text FROM psychosocial_risk_questions WHERE survey_id = $encuesta_id";
        $preguntas_result = Executor::doit($preguntas_sql);
        $preguntas = [];
        if ($preguntas_result && $preguntas_result[0]) {
            while ($row = $preguntas_result[0]->fetch_assoc()) {
                $preguntas[] = $row;
            }
        }
        $respuestas_sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
        $respuestas_result = Executor::doit($respuestas_sql);
        $respuestas = [];
        if ($respuestas_result && $respuestas_result[0]) {
            while ($row = $respuestas_result[0]->fetch_assoc()) {
                $respuestas[$row['question_id']] = $row['response'];
            }
        }
        $opciones = ['Siempre' => 1, 'Casi siempre' => 2, 'Algunas veces' => 3, 'Casi nunca' => 4, 'Nunca' => 5];
        $html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
                    <thead><tr><th>Pregunta</th><th>Siempre</th><th>Casi siempre</th><th>Algunas veces</th><th>Casi nunca</th><th>Nunca</th></tr></thead>
                    <tbody>";
        foreach ($preguntas as $pregunta) {
            $respuestaSeleccionada = $respuestas[$pregunta['id']] ?? null;
            $html .= "<tr><td>" . htmlspecialchars($pregunta['text']) . "</td>";
            foreach ($opciones as $opcion => $valor) {
                $marcado = ($respuestaSeleccionada == $valor) ? '✔' : '';
                $html .= "<td style='text-align: center;'>$marcado</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";
    }

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output($nombre_archivo, 'D');
    exit;
}
