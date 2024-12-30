<?php 
class NotificationData {

    public static function sendEmail($email, $username, $password) {
        return [
            'email' => $email,
            'username' => $username,
            'password' => $password
        ];
    }

    public static function getCredentials($id) {
        // Escapar el ID manualmente para evitar problemas de inyección SQL
        $id = intval($id); // Asegúrate de que sea un número entero
    
        // Definir la consulta SQL
        $sql = "SELECT clave, usuario, correo, telefono FROM personal WHERE id = $id";
    
        // Ejecutar la consulta
        $query = Executor::doit($sql);
    
        // Verificar si hay resultados
        if ($query[0] && $query[0]->num_rows > 0) {
            // Obtener el primer resultado como objeto
            $data = $query[0]->fetch_object();
            return $data;
        } else {
            return null; // No se encontraron resultados
        }
    }
    
    
}
