<?php

// Verificar si la acción es 'personal/get'
if (isset($_GET['action']) && $_GET['action'] === 'personal/get') {

    // Verificar si se proporcionó el ID del empleado
    if (isset($_GET['id'])) {
        $employeeId = $_GET['id'];

        // Crear una instancia de la clase PersonalModel
        $personalModel = new PersonalModel($db);  // Asumiendo que tienes una conexión a la base de datos $db

        // Obtener los datos del empleado
        $employeeData = $personalModel->getById($employeeId);

        // Verificar si se encontró el empleado
        if ($employeeData) {
            // Devolver los datos del empleado como JSON
            echo json_encode($employeeData);
        } else {
            // Si no se encontró, devolver un mensaje de error
            echo json_encode(['error' => 'Empleado no encontrado']);
        }
    } else {
        // Si no se proporciona un ID, devolver un error
        echo json_encode(['error' => 'ID del empleado no proporcionado']);
    }
}
?>
