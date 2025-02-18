<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

$conn = Database::getCon(); // Conexión a la BD

// Verificar si se recibió la solicitud correcta
if (isset($_GET['encuesta_id']) && isset($_GET['personal_id'])) {
    $encuesta_id = $_GET['encuesta_id'];
    $personal_id = $_GET['personal_id'];
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

    // Obtener el nombre del empleado
    $empleado_sql = "SELECT nombre FROM personal WHERE id = $personal_id";
    $empleado_result = Executor::doit($empleado_sql);
    $empleado_nombre = "Empleado"; // Valor por defecto en caso de error
    if ($empleado_result && $empleado_result[0]) {
        $row = $empleado_result[0]->fetch_assoc();
        $empleado_nombre = $row['nombre'];
    }
    $nombre_archivo = "Reporte_" . str_replace(" ", "_", $encuesta_nombre) . "_" . str_replace(" ", "_", $empleado_nombre) . ".pdf";

    // Obtener preguntas y respuestas según el tipo de encuesta
    if ($encuesta_id == 1) {
        // Obtener las preguntas de la encuesta 1
        $preguntas_sql = "SELECT id, question_text FROM survey_questions WHERE survey_id = $encuesta_id";
        $preguntas_result = Executor::doit($preguntas_sql);
        $preguntas = [];
        if ($preguntas_result && $preguntas_result[0]) {
            while ($row = $preguntas_result[0]->fetch_assoc()) {
                $preguntas[$row['id']] = $row['question_text'];
            }
        }

        // Obtener las respuestas del empleado para la encuesta 1
        $respuestas_sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
        $respuestas_result = Executor::doit($respuestas_sql);
        $respuestas = [];
        if ($respuestas_result && $respuestas_result[0]) {
            while ($row = $respuestas_result[0]->fetch_assoc()) {
                $respuestas[$row['question_id']] = $row['response'];
            }
        }

        // Depuración: Ver respuestas obtenidas
        // var_dump($respuestas);
        // exit;

        // Generar HTML para la tabla con preguntas y respuestas
        $html = "<h2>Reporte de Encuesta 1</h2>
                <table border='1' cellpadding='5' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Preguntas</th>
                            <th>Sí</th>
                            <th>No</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($preguntas as $id => $texto) {
            $respuesta = isset($respuestas[$id]) ? $respuestas[$id] : null;
            $checked_si = ($respuesta == "1") ? "checked" : "";  // Comparación flexible
            $checked_no = ($respuesta == "0") ? "checked" : "";

            $html .= "<tr>
                        <td>{$texto}</td>
                        <td><input type='radio' name='respuesta_{$id}' value='1'  $checked_si></td>
                        <td><input type='radio' name='respuesta_{$id}' value='0'  $checked_no></td>
                    </tr>";
        }

        $html .= "</tbody></table>";

    } elseif ($encuesta_id == 2 || $encuesta_id == 3) {
        // Obtener preguntas y respuestas para las encuestas 2 y 3
        $preguntas_sql = "SELECT id, text FROM psychosocial_risk_questions WHERE survey_id = $encuesta_id";
        $preguntas_result = Executor::doit($preguntas_sql);
        $preguntas = [];
        if ($preguntas_result && $preguntas_result[0]) {
            while ($row = $preguntas_result[0]->fetch_assoc()) {
                $preguntas[] = $row;
            }
        }

        // Obtener las respuestas del empleado para la encuesta
        $respuestas_sql = "SELECT question_id, response FROM survey_answers WHERE personal_id = $personal_id AND survey_id = $encuesta_id";
        $respuestas_result = Executor::doit($respuestas_sql);
        $respuestas = [];
        if ($respuestas_result && $respuestas_result[0]) {
            while ($row = $respuestas_result[0]->fetch_assoc()) {
                $respuestas[$row['question_id']] = $row['response'];
            }
        }

        // Depuración: Ver respuestas obtenidas
        // var_dump($respuestas);
        // exit;

        // Opciones de respuesta (para encuestas de tipo 2 y 3)
        $opciones = [
            'Siempre' => 1,
            'Casi siempre' => 2,
            'Algunas veces' => 3,
            'Casi nunca' => 4,
            'Nunca' => 5
        ];

        // Comenzar a construir el HTML de la tabla
        $html = "<h2>Reporte de Encuesta {$encuesta_id}</h2>
                <table border='1' cellpadding='5' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                            <th>Siempre</th>
                            <th>Casi siempre</th>
                            <th>Algunas veces</th>
                            <th>Casi nunca</th>
                            <th>Nunca</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($preguntas as $pregunta) {
            // Buscar la respuesta del empleado para la pregunta actual
            $respuestaSeleccionada = isset($respuestas[$pregunta['id']]) ? $respuestas[$pregunta['id']] : null;

            $filaHTML = "<tr><td>" . htmlspecialchars($pregunta['text']) . "</td>";

            foreach ($opciones as $opcion => $valor) {
                $checked = ($respuestaSeleccionada == $valor) ? 'checked' : ''; // Comparación flexible
                $filaHTML .= "<td><input  type='radio' name='pregunta_{$pregunta['id']}' value='{$opcion}' {$checked}></td>";
            }

            $filaHTML .= "</tr>";
            $html .= $filaHTML;
        }

        $html .= "</tbody></table>";
    }

    // Generar el PDF con mPDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);

    // Configurar cabeceras para la descarga del PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');

    echo $mpdf->Output('', 'S'); // Enviar el PDF al navegador
    exit;
}
