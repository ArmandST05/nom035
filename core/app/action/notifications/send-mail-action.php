<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Obtener la configuración
    $configuration = ConfigurationData::getAll();
    $personal = PersonalData::getById($userId);

    if (!$personal) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }

    // Configurar PHPMailer
    $mail = new PHPMailer();
    $mail->isMail(); // Usa mail() en lugar de SMTP
    $mail->Host = $configuration['name']->value;
    
    $fromEmail = filter_var($configuration['email']->value, FILTER_VALIDATE_EMAIL);
    if (!$fromEmail) {
        echo json_encode(['success' => false, 'message' => 'Dirección de correo inválida en la configuración.']);
        exit;
    }

    $mail->From = $fromEmail;
    $mail->FromName = $configuration['name']->value;
    $mail->Subject = "Tus credenciales de acceso";
    $mail->AddAddress($personal->correo);

    // Contenido del correo
    $body = "
        <p>Hola <b>{$personal->nombre}</b>,</p>
        <p>Estas son tus credenciales de acceso:</p>
        <ul>
            <li><b>Usuario:</b> {$personal->usuario}</li>
            <li><b>Clave:</b> {$personal->clave}</li>
        </ul>
        <p>Por favor, guarda esta información de manera segura.</p>
        <p>Saludos cordiales,<br>Equipo de {$configuration['name']->value}</p>
    ";
    $mail->Body = $body;
    $mail->IsHTML(true);

    if ($mail->Send()) {
        // Registrar notificación en el sistema
        $notification = new NotificationData();
        $notification->personal_id = $personal->id;
        $notification->type_id = 1; // Correo
        $notification->status_id = 1; // Enviado
        $notification->message = $body;
        $notification->receptor = $personal->correo;
        $notification->add();
        
        echo json_encode(['success' => true, 'message' => 'Correo enviado exitosamente.']);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo enviar el correo.',
            'errors' => [$mail->ErrorInfo]
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
