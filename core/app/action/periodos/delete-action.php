<?php
// Verificar que se ha enviado un ID a través de GET
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // Obtener el ID del periodo a eliminar
    $idPeriodo = intval($_GET["id"]);
    echo "ID recibido: " . $idPeriodo . "<br>"; // Muestra el ID recibido
    
    // Llamar al método delete() pasando el ID
    if (PeriodoData::delete($idPeriodo)) {
        echo "¡Periodo eliminado exitosamente!<br>";
    } else {
        echo "Error: No se pudo eliminar el periodo.<br>";
    }

    // Redirigir a la lista de puestos después de la eliminación
    print "<script>window.location='index.php?view=periodos/index';</script>";
} else {
    echo "¡ID no válido para eliminar!<br>";
    print "<script>window.location='index.php?view=periodos/index';</script>";
}
?>
