<?php
if (count($_POST) > 0) {
    // Obtener el registro del puesto por ID
    $puesto = PuestoModel::getById($_POST["id"]);

    if ($puesto) {
        // Actualizar los campos del puesto con los datos del formulario
        $puesto->nombre = $_POST["nombre"];
        $puesto->id_departamento = $_POST["id_departamento"];

        // Guardar los cambios en la base de datos
        $puesto->update(); // Método para actualizar en la base de datos

        // Redirigir al índice de puestos
        print "<script>window.location='index.php?view=puestos/index';</script>";
    } else {
        // Si no se encuentra el puesto, mostrar un mensaje de error
        echo "<script>alert('Error: No se encontró el puesto a actualizar.');</script>";
        print "<script>window.location='index.php?view=puestos/index';</script>";
    }
}
?>
