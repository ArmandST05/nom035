<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar si los datos necesarios están presentes
    if (isset($_POST['employeeId']) && isset($_POST['surveys'])) {
        $personalId = intval($_POST['employeeId']);
        $surveyIds = array_map('intval', $_POST['surveys']);

        // Asignar encuestas al empleado
        $result = EncuestaData::assignToPersonal($personalId, $surveyIds);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Encuestas asignadas correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al asignar encuestas."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos insuficientes."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
file_put_contents('debug.txt', print_r($_POST, true), FILE_APPEND);

?>
