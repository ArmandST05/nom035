<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 📌 Leer datos JSON desde el frontend
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!$data || !isset($data['users']) || empty($data['users'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se enviaron datos de usuarios.']);
        exit;
    }

    $users = $data['users']; // Lista de usuarios a enviar correo
    $errors = []; // Para almacenar errores

    // Obtener configuración de la base de datos
    $configuration = ConfigurationData::getAll();
    $fromEmail = $configuration['email']->value;
    $fromName = $configuration['name']->value;

    // Validar que la configuración del remitente sea correcta
    if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Dirección de correo inválida en la configuración.']);
        exit;
    }

    foreach ($users as $userId) {
        try {
            // 📌 Obtener datos del usuario desde la base de datos
            $personal = PersonalData::getById($userId);

            if (!$personal || empty($personal->correo) || empty($personal->usuario) || empty($personal->clave)) {
                throw new Exception("Datos incompletos para el usuario ID: {$userId}");
            }

            // 📌 Configurar PHPMailer para usar SMTP
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'mail.v2technoconsulting.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'armando_suarez@v2technoconsulting.com';
            $mail->Password = '=oetE(u5{%-?';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // 📌 Configurar correo
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($personal->correo);
            $mail->Subject = "Tus credenciales de acceso";
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = "
                <p>Hola <b>{$personal->nombre}</b>,</p>
                <p>Estas son tus credenciales de acceso:</p>
                <ul>
                    <li><b>Usuario:</b> {$personal->usuario}</li>
                    <li><b>Clave:</b> {$personal->clave}</li>
                </ul>
                <p>Por favor, guarda esta información de manera segura.</p>
                <p>Saludos cordiales,<br>Equipo de {$fromName}</p>
            ";

            // 📌 Enviar correo y registrar notificación
            if ($mail->send()) {
                $notification = new NotificationData();
                $notification->personal_id = $personal->id;
                $notification->type_id = 1; // Correo
                $notification->status_id = 1; // Enviado
                $notification->message = $mail->Body;
                $notification->receptor = $personal->correo;
                $notification->add();
            } else {
                throw new Exception("No se pudo enviar a {$personal->correo}");
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }

    // 📌 Respuesta final
    if (empty($errors)) {
        echo json_encode(['success' => true, 'message' => 'Correos enviados exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Algunos correos no se pudieron enviar.', 'errors' => $errors]);
        error_log($errors);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
