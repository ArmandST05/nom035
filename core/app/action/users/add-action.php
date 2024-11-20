<?php
if (count($_POST) > 0) {
    // Crear un nuevo usuario
    $user = new UserData();
    $user->name = $_POST["name"];
    $user->username = $_POST["username"];
    $user->password = sha1(md5($_POST["password"])); // Conserva la encriptación original
    $user->user_type = $_POST["user_type"];
    
    // Procesar la imagen
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
        $base64Image = base64_encode($imageData);
        $user->image = $base64Image;
    } else {
        $user->image = null; // Si no se sube imagen, se deja como null o valor predeterminado
    }

    // Guardar usuario
    $user->add();

    // Redirigir a la lista de usuarios
    print "<script>window.location='index.php?view=users/index';</script>";
}
?>
