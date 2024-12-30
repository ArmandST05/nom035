<?php
require_once 'core/app/model/NotificationData.php'; // Incluye la clase para obtener datos del usuario

// Obtén el ID del usuario del parámetro POST
if (isset($_POST['id'])) {
    $userId = intval($_POST['id']);

    // Obtén las credenciales del usuario
    $credentials = NotificationData::getCredentials($userId);

    if ($credentials) {
        $telefono = $credentials->telefono ?? null;
    
        // Validar que el número existe y no incluye ya el prefijo +52
        if (!$telefono) {
            echo json_encode(['success' => false, 'message' => 'El teléfono no está disponible para este usuario.']);
            exit;
        }
    
        // Eliminar espacios o caracteres innecesarios
        $telefono = preg_replace('/\s+/', '', $telefono);
    
        // Agregar el prefijo +52 si no lo tiene
        if (!str_starts_with($telefono, '+52')) {
            $telefono = '+52' . $telefono;
        }
    
        $usuario = $credentials->usuario;
        $clave = $credentials->clave;
    
        // Construir el mensaje
        $mensaje = "Hola, aquí tienes tus credenciales de acceso:\n\n";
        $mensaje .= "Usuario: $usuario\n";
        $mensaje .= "Contraseña: $clave\n\n";
        $mensaje .= "Accede al sistema desde el siguiente enlace: https://tu-dominio.com/login";
    
        // Generar el enlace de WhatsApp
        $whatsappLink = "https://wa.me/" . urlencode($telefono) . "?text=" . urlencode($mensaje);
    
        echo json_encode(['success' => true, 'whatsappLink' => $whatsappLink]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron credenciales para este usuario.']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado.']);
}
?>
*/