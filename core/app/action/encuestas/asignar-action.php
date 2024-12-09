<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employeeId']) && isset($_POST['surveys'])) {
        $employeeId = intval($_POST['employeeId']);
        $surveys = $_POST['surveys'];

        if (!empty($employeeId) && !empty($surveys)) {
            $result = EncuestaData::assignToPersonal($employeeId, $surveys);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Encuestas asignadas correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo asignar las encuestas.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parámetros faltantes.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
