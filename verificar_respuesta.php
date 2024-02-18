<?php
session_start();

function mostrarPregunta($preguntaActual)
{
    echo '<div class="form-container form">';
    echo '<p class="white">' . $preguntaActual['enunciado'] . '</p>';
    echo '<form class="mini-form" method="post" action="verificar_respuesta.php">';
    echo '<div class="respuestas-container">';
    foreach ($preguntaActual['respuestas'] as $respuesta) {
        echo '<label>';
        echo '<input type="radio" name="respuesta" value="' . $respuesta . '">';
        echo $respuesta;
        echo '</label><br>';
    }
    echo '</div>';
    echo '<input type="hidden" name="correct_answer" value="' . end($preguntaActual['respuestas']) . '">';
    echo '<input type="hidden" name="continue" value="1">';
    echo '<button class="btn-c" type="submit">Verificar Respuesta</button>';
    echo '</form>';
    echo '</div>';
}

function mostrarError()
{
    echo '<div class="form-container form">';
    echo 'Error: No se encontraron respuestas para la pregunta actual.';
    echo '</div>';
}
// Verificar si hay preguntas en la respuesta de la API
if (isset($_SESSION['preguntasrespuestas'])) {
    $preguntasRespuestas = $_SESSION['preguntasrespuestas'];

    // Variable para el índice de la pregunta actual
    $currentQuestion = isset($_SESSION['currentQuestion']) ? $_SESSION['currentQuestion'] : 0;

    // Verificar si el índice de la pregunta actual está dentro del rango
    if ($currentQuestion >= 0 && $currentQuestion < count($preguntasRespuestas)) {
        $preguntaActual = $preguntasRespuestas[$currentQuestion];

        // Verificar si los datos esperados están presentes
        $typeMatch = (!isset($_POST['type']) || (isset($question['type']) && $question['type'] == $_POST['type']));
        $difficultyMatch = (!isset($_POST['difficulty']) || (isset($question['difficulty']) && $question['difficulty'] == $_POST['difficulty']));

        if ($typeMatch && $difficultyMatch && isset($preguntaActual['respuestas'])) {
            mostrarPregunta($preguntaActual);
        } else {
            mostrarError();
        }
    } else {
        mostrarError();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['respuesta'], $_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas'])) {
        $respuestaUsuario = $_POST['respuesta'];
        $respuestasPregunta = $_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas'];

        if (in_array($respuestaUsuario, $respuestasPregunta)) {
            $mensaje = "¡Respuesta correcta!";
        } else {
            $mensaje = "Respuesta incorrecta. La respuesta correcta es: " . end($_SESSION['preguntasrespuestas'][$_SESSION['currentQuestion']]['respuestas']);
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
    <div class="container mt-4 center">
        <h1>Resultado de la respuesta</h1>
        <p><?php echo $mensaje; ?></p>
        <form method="post" action="index.php">
            <button class="btn-c" type="submit">Continuar</button>
        </form>
    </div>
</body>

</html>