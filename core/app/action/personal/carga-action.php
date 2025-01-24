<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Asegúrate de verificar si el campo empresa_id está presente
    if (isset($_POST['empresa_id']) && !empty($_POST['empresa_id'])) {
        // Obtener el valor de empresa_id desde el formulario
        $empresa_id = intval($_POST['empresa_id']); // Convertir a entero para evitar inyecciones

        // Instanciar la clase y pasar el archivo y el empresa_id
        $handler = new CargaData();
        $response = $handler->uploadFile($_FILES['file'], $empresa_id);
        echo $response;
    } else {
        echo "Error: No se seleccionó una empresa.";
    }
} else {
    echo "No se envió ningún archivo.";
}
?>
