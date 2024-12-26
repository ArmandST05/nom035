<?php 
class NotificationData {

    public static function sendEmail($email, $username, $password) {
        return [
            'email' => $email,
            'username' => $username,
            'password' => $password
        ];
    }

    public static function getCredentials($clave, $correo, $usuario, $id) {
        // Definir la consulta SQL
        $sql = "SELECT clave, usuario, correo, id FROM personal WHERE id = '$id'";

        // Ejecutar la consulta
        $query = Executor::doit($sql);

        // Verificar si hay resultados
        if ($query[0] && count($query[0]) > 0) {
            return Model::one($query[0], new PersonalData());
        } else {
            return null; // No se encontraron resultados
        }
    }
}
