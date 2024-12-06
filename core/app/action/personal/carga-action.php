<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $handler = new CargaData();
    $response = $handler->uploadFile($_FILES['file']);
    echo $response;
} else {
    echo "No se envió ningún archivo.";
}
?>