<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class View
{

    /**
     * Muestra la página de inicio.
     * @param string $mensajeError Mensaje de error a mostrar (opcional).
     */
    public function initView()
    {
?>
        <div class="main-container__content">

            <div class="main-container__content__title">
                <h1 class="animate-character">APP Trivial</h1>
            </div>
            <div class="main-container__content__subtitle">
                <h2 class="text txt-white">Next question, Please!</h2>
            </div>
        </div>

    <?php
        // Llamada al segundo método
        $this->showInfo();
    }

    /**
     * Muestra la información principal de la aplicación mediante botones.
     */
    public function showInfo()
    {
    ?>
        <div class="main-container__flight">
            <div class="main-container__flight-title">
                <h1 class="black-text">Trivial Options</h1>
            </div>
            <div class="main-container__content__btn">
                <a href="index.php?controller=All&action=random" class="btn-flight">Random Question</a>
                <a href="index.php?controller=All&action=select" class="btn-flight">Select Questions</a>
                <a href="index.php?controller=All&action=mostrarInicio" class="btn-flight">Search Questions</a>
            </div>
        </div>
    <?php
    }


    /**
     * Método para mostrar VALORES DE UN ARRAY ASOCIATIVO
     * @param array $cards Array con la información de las cards.
     */

    public function mostrarCard($card)
    {

    ?>
        <div class="fluid-container">
            <p class="black-text center">Random </p>
            <p class='whitexl'>
                <?= $card['value'] ?>
            </p>
        </div>
        <a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a>
    <?php
    }

    /**
     * Metodo que muestra el formulario para  CARGAR SELECT
     * @param array $res array con la info para cargar el select
     */
    public function showSelect($res)
    {
        /**
         * Metodo que muestra el formulario para seleccionar una categoria
         * @param $res array con la info para cargar el select
         * @return post con la categoria seleccionada
         */
    ?>
        <h5 class="animate-character mt-5">select</h5>
        <div class="form-container">
            <form class="form center" action="index.php?controller=all&action=select" method="post">
                <!-- <div class="form-group">
                    <label for=" identificador">Identificador</label>
                    <input type="text" name="identificador" class="form-control" id="identificador" placeholder="" value="">
                </div> -->
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" class="form-select" id="tipo">
                        //hago un foreach para recorrer el array y mostrar los valores en el select
                        <?php
                        // foreach ($res as $key => $value) { si quiero que el value sea el indice
                        foreach ($res as $value) {  //si quiero que el value sea el valor
                            echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
            <!-- <a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a> -->
        </div>
    <?php
    }

    /**
     * Metodo que muestra el formulario para  CARGAR SELECT
     * @param array $data array ASOCIATIVO con la info para cargar el select   
     */
    function mostrarOpciones($data)
    {
    ?>
        <div class="form-container">
            <form class="form center" action="index.php?controller=all&action=select" method="post">div class="form-group">
                <label for="tipo">Tipo</label>
                <select name="tipo" class="form-select" id="tipo">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="" disabled selected>' . $default . '</option>
                    foreach ($data as $item) {
                    <option value="' . $item[$valueField] . '">' . $item[$textField] . '</option>
                    }
                </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
        <a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a>
        </div>
    <?php
    }



    /**
     * Metodo que muestra el formulario para que pueda BUSCAR por tema
     * 
     */
    public function showSearch()
    {
    ?>
        <h5 class="animate-character mt-5">Search</h5>
        <div class="form-container">
            <form class="form" action="index.php?controller=all&action=search" method="post">
                <div class="form-group">
                    <label for="tipo">Busqueda</label>
                    <input required type="search" name="search" class="">
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
            <a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a>
        </div>
    <?php
    }
    /**
     * Metodo que muestra las cards
     * @param array $data array con dos props, total y results, dentro de results hay un array con la info de cada card
     * 
     */
    public function mostrarCards($data, $dataIndex, $totalData)
    {
    ?>
        <div class="container mt-4 ml-12">
            <div class="rowleft row">

                <?php if (isset($data['total']) && isset($data['result']) && is_array($data['result'])) :
                    $results = $data['result'];
                    $totalData = $data['total'];

                    if (!empty($totalData) && !empty($results)) :
                        foreach ($results as $index => $result) :
                ?>
                            <div class="col-md-6 mb-6 center" style="display: <?= ($dataIndex == $index ? 'block' : 'none') ?>">
                                <div class="card cardm">
                                    <div class="card-body">
                                        <h5 class="card-title whitexl"><?= $result['value'] ?></h5>
                                    </div> <!-- Fin card-body -->
                                </div> <!-- Fin card -->
                            </div> <!-- Fin col-md-6 -->
                        <?php endforeach; ?>

                        <!-- Actualizo las variables de sesión -->
                        <?php $_SESSION['dataIndex'] = $dataIndex; ?>
                        <?php $_SESSION['totalData'] = $totalData; ?>

                        <!-- Botones para paginar -->
                        <div class="buttons-container mt-3 d-flex justify-content-around">
                            <?php if ($dataIndex > 0) : ?>
                                <form method="post" action="index.php?controller=All&action=mostrarCards">
                                    <input type="hidden" name="dataIndex" value="<?= ($dataIndex - 1) ?>">
                                    <input type="hidden" name="totalData" value="<?= $totalData ?>">
                                    <button type="submit" name="accion" value="anterior" class="btn btn-primary">Anterior</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($dataIndex < $totalData - 1) : ?>
                                <form method="post" action="index.php?controller=All&action=mostrarCards">
                                    <input type="hidden" name="dataIndex" value="<?= ($dataIndex + 1) ?>">
                                    <input type="hidden" name="totalData" value="<?= $totalData ?>">
                                    <button type="submit" name="accion" value="siguiente" class="btn btn-primary">Siguiente</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div class="col-md-12">
                            <div class="alert alert-warning" role="alert">
                                No se han encontrado resultados
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            Ha ocurrido un error al cargar los datos
                        </div>
                    </div>
                <?php endif; ?>
            </div> <!-- Fin row -->
            <a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a>
        </div> <!-- Fin container -->
    <?php
    }
    /**
     * Metodo que muestra la pregunta, las posibles respuestas y el boton para verificar la respuesta
     * @param array $preguntaActual array con la info de la pregunta actual
     */
    function mostrarPregunta($preguntaActual)
    {
    ?>
        <div class="form-container form">
            <p class="white"> <?= $preguntaActual['enunciado'] ?></p>
            <form class="mini-form" method="post" action="verificar_respuesta.php">
                <div class="respuestas-container">
                    <?php
                    foreach ($preguntaActual['respuestas'] as $respuesta) {
                        echo '<label>';
                        echo '<input type="radio" name="respuesta" value="' . $respuesta . '">';
                        echo $respuesta;
                        echo '</label><br>';
                    }
                    ?>
                </div>
                <input type="hidden" name="correct_answer" value="' <?= end($preguntaActual['respuestas']) ?>">
                <input type="hidden" name="continue" value="1">
                <button class="btn-c" type="submit">Verificar Respuesta</button>
            </form>
        </div>

    <?php
    }
    /**
     * Metodo que muestra un mensaje de error
     */
    function mostrarError()
    {
    ?>
        <div class="form-container form" <p>Error: No se encontraron soluciones.</p>
        </div>
    <?php
    }
    /**
     * Metodo que muestra el resultado de la respuesta
     * @param string $mensaje mensaje a mostrar
     */
    function mostrarResultado($mensaje)
    {
    ?>
        <div class="container mt-4 center">
            <h1>Resultado de la respuesta</h1>
            <p><?php echo $mensaje; ?></p>
            <form method="post" action="index.php">
                <button class="btn-c" type="submit">Continuar</button>
            </form>
        </div>
    <?php
    }
    /**
     * Metodo que muestra el formulario para SELECCIONAR MULTIPLES PARAMETROS
     * @param array $categorias array con la info para cargar el select
     */
    public function showSelectMultiple($category)
    {
        //var_dump($category);
    ?>
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
                            <option value="any">Any Type</option>
                            <option value="multiple">Multiple Choice</option>
                            <option value="boolean">True / False</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="categorySelect" class="form-select">
                            <?php
                            foreach ($category['trivia_categories'] as $cat) {
                                echo '<option value="' . $cat['name'] . '">' . $cat['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="difficulty" class="form-select">
                            <option value="any">Any Difficulty</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    <input type="hidden" name="continue" value="1">
                    <button class="btn-custom center" type="submit" name="submit">JUGAR</button>
                </form>
                <?php
                if (isset($_POST['submit'])) {
                    echo '<a href="index.php?controller=All&action=mostrarInicio" class="btn btn-primary">Back</a>';
                }
                ?>
            </div>
        </div>
    <?php
    }



    /**
     * Muestra la información de TODOS LOS
     * @param Array $vuelos Array con la información de los vuelos.
     */
    public function AllFlights($vuelos)
    {


    ?>
        <div class="main-container__content__table">
            <h1 class="black-text center">All Flights</h1>
            <a href="index.php?controller=All&action=initFlight" class="btn btn-primary mb-3 ">Back</a>
            <table class="table--bs-table-bg table-striped table-hover table-custom">

                <thead class="table border">
                    <tr>
                        <th scope=" col center">IDENTIFIER</th>
                        <th scope="col center">SOURCE AIRPORT</th>
                        <th scope="col center">DEPARTURE AIRPORT</th>
                        <th scope="col center">DEPARTURE COUNTRY</th>
                        <th scope="col center">DESTINATION AIRPORT</th>
                        <th scope="col center">ARRIVAL AIRPORT</th>
                        <th scope="col center">ARRIVAL COUNTRY</th>
                        <th scope="col center">FLIGHT TYPE</th>
                        <th scope="col center">NUMBER OF PASSENGERS</th>
                        <th scope="col center">Edit</th>
                        <th scope="col center">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($vuelos as $vuelo) {
                    ?>
                        <tr class="--bs-table-active-bg">
                            <td class="center"><?= $vuelo['Identificador del vuelo'] ?></td>
                            <td class="center"><?= $vuelo['Aeropuerto de origen'] ?></td>
                            <td class="center"><?= $vuelo['Nombre aeropuerto de origen'] ?></td>
                            <td class="center"><?= $vuelo['País de origen'] ?></td>
                            <td class="center"><?= $vuelo['Aeropuerto de destino'] ?></td>
                            <td class="center"><?= $vuelo['Nombre aeropuerto destino'] ?></td>
                            <td class="center"><?= $vuelo['País de destino'] ?></td>
                            <td class="center"><?= $vuelo['Tipo de vuelo'] ?></td>
                            <td class="center"><?= $vuelo['Número de pasajeros del vuelo'] ?></td>
                            <td class="center"><a href="index.php?controller=Flight&action=editFlight&id=<?= $vuelo['Identificador del vuelo'] ?>" class="btn btn-primary"><i class="bi bi-pencil"></i></a></td>
                            <td class="center"><a href="index.php?controller=Flight&action=deleteFlight&id=<?= $vuelo['Identificador del vuelo'] ?>" class="btn btn-outline-danger"><i class="bi bi-trash"></a></td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    <?php
    }

    /**
     * Metodo que muestra la informacion de uun  
     * @param Recibe un array bidemensional, con la info en el indice 0
     * 
     */
    public function showFlightId($vuelo)
    {
        //var_dump($vuelo);
    ?>
        <div class="main-container__content__table">
            <h1 class="black-text center">All Flights</h1>
            <a href="index.php?controller=Flight&action=initFlight" class="btn btn-primary mb-3 ">Back</a>

            <table class="table--bs-table-bg table-striped table-hover table-custom">

                <thead class="table border">
                    <tr>
                        <th scope="col center">IDENTIFIER</th>
                        <th scope="col center">SOURCE AIRPORT</th>
                        <th scope="col center">DEPARTURE AIRPORT</th>
                        <th scope="col center">DEPARTURE COUNTRY</th>
                        <th scope="col center">DESTINATION AIRPORT</th>
                        <th scope="col center">ARRIVAL AIRPORT</th>
                        <th scope="col center">ARRIVAL COUNTRY</th>
                        <th scope="col center">FLIGHT TYPE</th>
                        <th scope="col center">NUMBER OF PASSENGERS</th>
                        <th scope="col center">Edit</th>
                        <th scope="col center">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="--bs-table-active-bg">
                        <td class="center"><?= $vuelo[0]['Identificador del vuelo'] ?></td>
                        <td class="center"><?= $vuelo[0]['Aeropuerto de origen'] ?></td>
                        <td class="center"><?= $vuelo[0]['Nombre aeropuerto de origen'] ?></td>
                        <td class="center"><?= $vuelo[0]['País de origen'] ?></td>
                        <td class="center"><?= $vuelo[0]['Aeropuerto de destino'] ?></td>
                        <td class="center"><?= $vuelo[0]['Nombre aeropuerto destino'] ?></td>
                        <td class="center"><?= $vuelo[0]['País de destino'] ?></td>
                        <td class="center"><?= $vuelo[0]['Tipo de vuelo'] ?></td>
                        <td class="center"><?= $vuelo[0]['Número de pasajeros del vuelo'] ?></td>
                        <td><a href="index.php?controller=Flight&action=mostrarInicio" class="btn btn-primary">Edit</a></td>
                        <td><a href="index.php?controller=Flight&action=mostrarInicio" class="btn btn-outline-danger"><i class="bi bi-trash"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
<?php
    }
}
