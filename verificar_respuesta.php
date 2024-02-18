<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['respuesta'], $_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas'])) {
        $respuestaUsuario = $_POST['respuesta'];
        $respuestasPregunta = $_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas'];

        if (in_array($respuestaUsuario, $respuestasPregunta)) {
            $mensaje = "¡Respuesta correcta!";
        } else {
            $mensaje = "Respuesta incorrecta. La respuesta correcta es: " . $_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas'][count($respuestasPregunta) - 1];
        }
    } else {
        $mensaje = "Error al procesar la respuesta";
    }
} else {
    $mensaje = "Acceso no permitido";
}

// Incrementa la pregunta actual en la sesión
$_SESSION['currentQuestion']++;


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Respuesta</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h1>Resultado de la respuesta</h1>
        <p><?php echo $mensaje; ?></p>
        <form method="post" action="index.php">
            <button class="btn-c" type="submit">Continuar</button>
        </form>
    </div>
</body>

</html>