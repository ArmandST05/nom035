<?php 
if(count($_POST) > 0) {
    $newPuesto = new PuestoData();

    $newPuesto->name = trim($_POST["roleName"]);
    $newPuesto->id_departamento = $_POST["roleDepartment"];
    $newPuesto->id_encuesta = $_POST["roleEncuesta"];

    try {
        $result = $newPuesto->add(); // Guarda en la base de datos

        if($result) {
            echo json_encode(["success" => true, "message" => "Personal agregado con éxito."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al agregar el personal en la base de datos."]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "No se recibieron datos."]);
}




?>