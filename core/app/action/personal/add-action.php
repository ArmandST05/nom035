<?php 
    if (count($_POST) > 0){
        $newPersonal = new PersonalData();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $departamento = ['id_departamento'];
        $puesto = ['id_puesto'];
        $fecha_alta = ['fecha_alta'];
        $telefono = ['phone'];


        $newPersonal -> add();

        print "<script>window.location='index.php?view=personal/listado';</script>";
    }


?>