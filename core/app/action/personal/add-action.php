<?php
if(count($_POST) > 0) {

    $newPersonal = new PersonalData();

   // Asigna los valores del POST al objeto (como los campos del formulario)
    // Los nombres de las claves en $_POST deben coincidir con los que se usan en la base de datos.
    $newPersonal->name = trim($_POST["employeeName"]);  // "employeeName" en el formulario → "nombre" en la base de datos
    $newPersonal->email = trim($_POST["employeeEmail"]);  // "employeeEmail" → "correo"
    $newPersonal->id_departamento = $_POST["employeeDepartment"];  // "employeeDepartment" → "id_departamento"
    $newPersonal->id_puesto = $_POST["employeeRole"];  // "employeeRole" → "id_puesto"
    $newPersonal->fecha_alta = $_POST["employeeDate"];  // "employeeDate" → "fecha_alta"
    $newPersonal->phone = $_POST["employeePhone"];  // "employeePhone" → "telefono"
    $newPersonal->usuario = _$POST["employeeUsuario"];
    $newPersonal-> = $_POST["employeeClave"];


    try {
        $result = $newPersonal->add(); // Guarda en la base de datos

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
