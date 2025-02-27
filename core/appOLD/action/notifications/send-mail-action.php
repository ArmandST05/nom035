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

    // Validar correo del remitente
    $fromEmail = $configuration['email']->value;
    if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Dirección de correo inválida en la configuración.']);
        exit;
    }

    // Configurar PHPMailer
    $mail = new PHPMailer();
    $mail->isMail(); // Usa mail() en lugar de SMTP
    $mail->From = $fromEmail;
    $mail->FromName = $configuration['name']->value;
    $mail->Subject = "Tus credenciales de acceso";
    $mail->AddAddress($personal->correo);
    $mail->CharSet = 'UTF-8';
    $mail->IsHTML(true);

    // Contenido del correo
    $mail->Body = "
        <p>Hola <b>{$personal->nombre}</b>,</p>
        <p>Estas son tus credenciales de acceso:</p>
        <ul>
            <li><b>Usuario:</b> {$personal->usuario}</li>
            <li><b>Clave:</b> {$personal->clave}</li>
        </ul>
        <p>Por favor, guarda esta información de manera segura.</p>
        <p>Saludos cordiales,<br>Equipo de {$configuration['name']->value}</p>
    ";

    // Enviar correo y manejar la respuesta
    if ($mail->Send()) {
        // Registrar notificación en el sistema
        $notification = new NotificationData();
        $notification->personal_id = $personal->id;
        $notification->type_id = 1; // Correo
        $notification->status_id = 1; // Enviado
        $notification->message = $mail->Body;
        $notification->receptor = $personal->correo;
        $notification->add();

        echo json_encode(['success' => true, 'message' => 'Correo enviado exitosamente.']);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo enviar el correo.',
            'error' => $mail->ErrorInfo
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
