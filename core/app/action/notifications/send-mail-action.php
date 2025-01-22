<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id']; // Obtiene el ID del usuario desde el formulario

    // Obtener la configuración
    $configuration = ConfigurationData::getAll();

    // Obtener los datos del personal
    $personal = PersonalData::getById($userId);

    if (!$personal) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }

    // Configurar PHPMailer
    $mail = new PHPMailer();
    $mail->Host = $configuration['name']->value;
    $mail->From = $configuration['email']->value;
    $mail->FromName = $configuration['name']->value;
    $mail->Subject = "Tus credenciales de acceso";
    $mail->AddAddress($personal->correo); // Dirección del destinatario

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

    // Enviar correo y verificar errores
    if ($mail->Send()) {
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
