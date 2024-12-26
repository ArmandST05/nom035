<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['message' => 'No autenticado']);
    exit;
}

// Obtener el ID del personal al que se le enviarán las credenciales
$id = $_POST['id'] ?? null; // Suponiendo que el ID se pasa por POST

if ($id === null) {
    echo json_encode(['message' => 'ID de personal no proporcionado']);
    exit;
}

// Obtener las credenciales del personal
$credentials = NotificationData::getCredentials($clave, $correo, $usuario, $id);

if ($credentials) {
    $clave = $credentials->clave; // Asumiendo que el objeto tiene estas propiedades
    $usuario = $credentials->usuario;
    $correo = $credentials->correo;

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'armandsuarez064@gmail.com'; // Tu correo
        $mail->Password = 'chipitin05'; // Tu contraseña o app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('armandsuarez064@gmail.com', 'Prueba'); // Correo y nombre del remitente
        $mail->addAddress($correo); // Correo del destinatario
        $mail->isHTML(true); // Habilitar contenido HTML
        $mail->Subject = 'Tus credenciales';
        $mail->Body = "Usuario: <b>$usuario</b><br>Contraseña: <b>$clave</b>";

        // Enviar correo
        $mail->send();
        echo json_encode(['message' => "Correo enviado a $correo"]);
    } catch (Exception $e) {
        echo json_encode(['message' => "Error al enviar correo: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['message' => 'No se encontraron credenciales']);
}
?>
