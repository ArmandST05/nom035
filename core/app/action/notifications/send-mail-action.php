<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(['message' => 'ID de usuario no proporcionado']);
        exit;
    }
    if (!$id || !is_numeric($id)) {
        echo json_encode(['message' => 'ID de usuario no válida.']);
        exit;
    }

    // Obtener las credenciales del usuario desde la base de datos
    try {
        // Asegúrate de que NotificationData::getCredentials esté definido correctamente
        $credentials = NotificationData::getCredentials($id);

        if ($credentials) {
            $correo = $credentials->correo; // Correo del usuario
            $usuario = $credentials->usuario; // Usuario
            $clave = $credentials->clave; // Contraseña

            // Enviar correo con PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'https://6680500.v2powerdr.com:2096/'; // Cambia según tu proveedor
                $mail->SMTPAuth = true;
                $mail->Username = 'armandst05@outlook.com'; // Tu correo electrónico
                $mail->Password = 'TtraXx64?'; // Contraseña o App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('armandst05@outlook.com', 'Sistema de Notificaciones');
                $mail->addAddress($correo); // Correo del destinatario
                $mail->isHTML(true);
                $mail->Subject = 'Tus credenciales';
                $mail->Body = "Usuario: <b>$usuario</b><br>Contraseña: <b>$clave</b>";

                // Enviar correo
                $mail->send();

                // Responder al frontend con un mensaje de éxito
                echo json_encode(['message' => "Correo enviado exitosamente a $correo."]);
            } catch (Exception $e) {
                echo json_encode(['message' => "Error al enviar el correo: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(['message' => 'No se encontraron credenciales para este usuario.']);
        }
    } catch (Exception $e) {
        echo json_encode(['message' => "Error al procesar la solicitud: {$e->getMessage()}"]);
    }
}
