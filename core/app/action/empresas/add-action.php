<?php 
if (count($_POST) > 0) {
    $newEmpresa = new EmpresaData();

    $newEmpresa -> nombre = trim($_POST["id_nombre"]);
    $newEmpresa -> comentarios = $_POST["id_comentarios"];
    $newEmpresa -> id_cantidad= $_POST["id_cantidad"];


    try {
        $result = $newEmpresa->add(); // Guarda en la base de datos

        if ($result) {
            echo json_encode(["success" => true, "message" => "Puesto agregado con éxito."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al agregar el puesto en la base de datos."]);
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