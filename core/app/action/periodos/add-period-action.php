<?php
// Comprobar si la solicitud es una petición AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $periodName = isset($_POST['period_name']) ? $_POST['period_name'] : '';
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';  // Agregamos el status

    // Validación básica de los datos
    if (empty($periodName) || empty($startDate) || empty($endDate) || empty($status)) {
        // Responder con un mensaje de error en formato JSON
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Crear el nuevo periodo en la base de datos
    $sql = "INSERT INTO periods (name, start_date, end_date, status) 
            VALUES ('$periodName', '$startDate', '$endDate', '$status')";

    // Ejecutar la consulta
    $result = Executor::doit($sql);

    if ($result) {
        // Responder con éxito en formato JSON
        echo json_encode(['status' => 'success', 'message' => 'El periodo fue creado exitosamente.']);
    } else {
        // Responder con un mensaje de error en formato JSON
        echo json_encode(['status' => 'error', 'message' => 'Hubo un error al crear el periodo.']);
    }
}
?>
