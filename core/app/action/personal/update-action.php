<?php
if (count($_POST) > 0) {
    // Obtener el registro del empleado por ID
    $personal = PersonalModel::getById($_POST["id"]);

    // Actualizar los campos del empleado con los datos del formulario
    $personal->nombre = $_POST["nombre"];
    $personal->correo = $_POST["correo"];
    $personal->id_departamento = $_POST["id_departamento"];
    $personal->id_puesto = $_POST["id_puesto"];
    $personal->fecha_alta = $_POST["fecha_alta"];
    $personal->telefono = $_POST["telefono"];
    $personal->usuario = $_POST["usuario"];
    $personal->clave = $_POST["clave"]; // Encriptar la contraseña

    $personal->update(); // Método para actualizar en la base de datos

    // Redirigir al índice
    print "<script>window.location='index.php?view=personal/index';</script>";
}
?>
