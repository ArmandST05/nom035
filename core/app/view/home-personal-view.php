<?php
// Obtén el usuario actual basado en la sesión
if (isset($_SESSION['typeUser']) && $_SESSION['typeUser'] === 'e') {
    // Si es un empleado
    if (isset($_SESSION['user_name'])) {
        $user_name = $_SESSION['user_name'];
    } else {
        $user_name = "Invitado";
    }
  } else {
    // Si es un usuario del sistema
    $user = UserData::getLoggedIn();
    if ($user) {
        $user_name = $user->name;
    } else {
        $user_name = "Invitado";
    }
  }
if ($_SESSION['typeUser'] === 'e') {
    $personal_id = $_SESSION['user_id'];
    $encuestas = EncuestaData::getAssignedSurveys($personal_id);
} else {
    die("Acceso denegado: Solo los empleados tienen encuestas asignadas.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuestas Asignadas</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($user_name); ?></h1>
    <h2>Encuestas Asignadas</h2>
    <ul>
        <?php if (!empty($encuestas)): ?>
            <?php foreach ($encuestas as $encuesta): ?>
                <li>
                    <strong><?php echo htmlspecialchars($encuesta->title); ?></strong><br>
                    <p><?php echo htmlspecialchars($encuesta->description); ?></p>
                    <a href="responder.php?survey_id=<?php echo $encuesta->id; ?>">Responder</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes encuestas asignadas.</p>
        <?php endif; ?>
    </ul>
</body>
</html>
