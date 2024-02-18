<?php
session_start();

// Función para mostrar las opciones de selección
function mostrarOpciones($data, $valueField, $textField, $default = "")
{
    echo '<option value="" disabled selected>' . $default . '</option>';
    foreach ($data as $item) {
        echo '<option value="' . $item[$valueField] . '">' . $item[$textField] . '</option>';
    }
}

// Verificamos si ya hay preguntas en la sesión
$preguntasRespuestas = isset($_SESSION['preguntasrespuestas']) ? $_SESSION['preguntasrespuestas'] : array();

// Variable para el índice de la pregunta actual
$currentQuestion = isset($_SESSION['currentQuestion']) ? $_SESSION['currentQuestion'] : 0;

// Datos de las categorías
$categoriasData = file_get_contents("https://opentdb.com/api_category.php");
$categorias = json_decode($categoriasData, true);

// Datos del formulario
$numpreguntas = isset($_POST['numpreguntas']) ? $_POST['numpreguntas'] : 10;
$type = isset($_POST['type']) ? $_POST['type'] : 'any';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'any';

// Verificar si se reinicia el juego
$reiniciar = isset($_POST['reiniciar']) ? $_POST['reiniciar'] : false;

// Construimos la URL de la API
$url = "https://opentdb.com/api.php";
$params = array(
    'amount' => $numpreguntas,
    'type' => $type,
    'category' => $category,
    'difficulty' => $difficulty
);
$url .= '?' . http_build_query($params); // Agregar los parámetros a la URL con formato de consulta

// Obtenemos los datos de la API solo si no estamos reiniciando el juego
if (!$reiniciar) {
    $trivial = json_decode(file_get_contents($url), true);
    //verificamos si hay preguntas
    if (isset($trivial['results'])) {
        $preguntasRespuestas = array();
        foreach ($trivial['results'] as $question) {
            $pregunta = array(
                'enunciado' => $question['question'],
                'respuestas' => array_merge($question['incorrect_answers'], array($question['correct_answer']))
            );
            $preguntasRespuestas[] = $pregunta;
        }

        $_SESSION['preguntasrespuestas'] = $preguntasRespuestas;

        $_SESSION['currentQuestion'] = 0;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferron Trivial</title>
    <link rel="stylesheet" href="./main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="main_container">
        <div class="form-container form">
            <form method="post" action="">
                <input type="hidden" name="reiniciar" value="1">
                <button class="btn-c" type="submit">Reiniciar</button>
            </form>
            <h1 class="mt-4 center">Trivial</h1>
            <p class="text center">Selecciona el modo de juego</p>
            <form class="form" method="post" action="">
                <div class="form-group">
                    <label class="label" for="numpreguntas">Indica el número de preguntas</label>
                    <input type="number" required name="numpreguntas" class="form-control" id="numpreguntas" value="">
                </div>
                <div class="form-group">
                    <select name="type" class="form-select">
                        <?php mostrarOpciones($categorias['trivia_categories'], 'id', 'name', 'Selecciona el tipo de pregunta'); ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="category" class="form-select">
                        <?php mostrarOpciones($categorias['trivia_categories'], 'id', 'name', 'Selecciona la categoría'); ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="difficulty" class="form-select">
                        <?php mostrarOpciones(array('easy', 'medium', 'hard'), null, null, 'Selecciona la dificultad'); ?>
                    </select>
                </div>
                <input type="hidden" name="continue" value="1">
                <button class="btn-custom center" type="submit" name="submit">JUGAR</button>
            </form>
            <?php
            if (isset($trivial['results']) && !$reiniciar) {
                foreach ($trivial['results'] as $question) {
                    $pregunta = array(
                        'enunciado' => $question['question'],
                        'respuestas' => array_merge($question['incorrect_answers'], array($question['correct_answer']))
                    );
                    $preguntasRespuestas[] = $pregunta;
                }

                $_SESSION['preguntasrespuestas'] = $preguntasRespuestas;

                // Verificar si los datos esperados están presentes
                $typeMatch = (!isset($_POST['type']) || (isset($question['type']) && $question['type'] == $_POST['type']));
                $difficultyMatch = (!isset($_POST['difficulty']) || (isset($question['difficulty']) && $question['difficulty'] == $_POST['difficulty']));

                if ($typeMatch && $difficultyMatch && isset($preguntasRespuestas[$currentQuestion]['respuestas'])) {
            ?>
                    <div class="form-container form">
                        <?php $preguntaActual = $preguntasRespuestas[$currentQuestion]; ?>
                        <p class="white"><?php echo $preguntaActual['enunciado']; ?></p>
                        <form class="mini-form" method="post" action="verificar_respuesta.php">
                            <div class="respuestas-container">
                                <?php foreach ($preguntaActual['respuestas'] as $respuesta) { ?>
                                    <label>
                                        <input type="radio" name="respuesta" value="<?php echo $respuesta; ?>">
                                        <?php echo $respuesta; ?>
                                    </label><br>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="correct_answer" value="<?php echo end($preguntaActual['respuestas']); ?>">
                            <input type="hidden" name="continue" value="1">
                            <button class="btn-c" type="submit">Verificar Respuesta</button>
                        </form>
                    </div>
                <?php
                } else {
                    echo "Error: No se encontraron respuestas para la pregunta actual.";
                }
                ?>
        </div>
    </div>
<?php
            }

?>
</body>

</html>