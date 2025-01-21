<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = $_POST['users']; // Recibimos un array con la lista de usuarios

    if (!isset($users) || count($users) === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se enviaron datos de usuarios.']);
        exit;
    }

    $errors = []; // Para almacenar errores
    foreach ($users as $user) {
        try {
            $mailReservation = new PHPMailer();
            $mailReservation->Host = $configuration['name']->value;
            $mailReservation->From = $configuration['email']->value;
            $mailReservation->FromName = $configuration['name']->value;
            $mailReservation->Subject = "Tus credenciales de acceso";
            $mailReservation->AddAddress($user['email']); // Destinatario

            // Contenido del correo
            $body = "
                <p>Hola <b>{$user['name']}</b>,</p>
                <p>Estas son tus credenciales de acceso:</p>
                <ul>
                    <li><b>Usuario:</b> {$user['username']}</li>
                    <li><b>Clave:</b> {$user['password']}</li>
                </ul>
                <p>Por favor, guarda esta información de manera segura.</p>
                <p>Saludos cordiales,<br>Equipo de {$configuration['name']->value}</p>
            ";
            $mailReservation->Body = $body;
            $mailReservation->IsHTML(true);

            // Enviar el correo
            if (!$mailReservation->send()) {
                $errors[] = "Error al enviar a {$user['email']}: {$mailReservation->ErrorInfo}";
            }
        } catch (Exception $e) {
            $errors[] = "Error al procesar el usuario {$user['email']}: {$e->getMessage()}";
        }
    }

    // Respuesta al cliente
    if (empty($errors)) {
        echo json_encode(['success' => true, 'message' => 'Correos enviados exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Algunos correos no se pudieron enviar.', 'errors' => $errors]);
    }
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
