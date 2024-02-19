<?php
if (!isset($_SESSION)) {
    session_start();
}
/**
 * Controlador de la página de vuelos.
 * @class VuelosController
 * @brief Controlador de la página de vuelos.
 */
class AllController
{
    private $View; //objeto de la clase Login_formview
    private $Service; //objeto de la clase VuelosService


    /**
     * Constructor de la clase Controller.
     * 
     */
    public function __construct()
    {
        $this->View = new View();
        $this->Service = new Service();
        $this->Service = new Service();
    }

    /**
     * Muestra la página de inicio 
     */
    public function mostrarInicio()
    {


        $this->View->initView(); //muestra la página de inicio 

    }
    /**
     * Pide al servidor el GET de un random 
     */
    public function random()
    {

        $random = json_decode($this->Service->request(), true); //pido al servicio que me de un random, true para que me devuelva un array asociativo
        //var_dump($random);

        $this->View->mostrarCard($random);
    }
    /**
     * pedir al servidor que me mande una lista para cargar un select 
     * 
     */

    public function select()
    {
        //LO PRIMERO ES PEDIR AL SERVICIO LOS SELECT
        $res = $this->Service->requestListSelect(); //$res es un array con la info para cargar un select
        $this->View->showSelectMultiple($res); //muestra la vista con el select
        //RECUPERO VALORES POR EL NAME 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoria = $_POST['tipo'];
            //var_dump($categoria);
            //mando l acategoria para que el servicio monte la url
            $res = $this->Service->requestCategory($categoria);
            //var_dump($res);
            $this->View->mostrarCard($res);
        }
    }
    /**
     * Metodo que pide al servidor la info dede un tema seleccionado
     * 
     */
    public function search()
    {
        $this->View->showSearch(); //muestra la vista para que el usuario introduzca el tema

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $search = $_POST['search'];
            //mando l acategoria para que el servicio monte la url
            $res = $this->Service->requestSearch($search);
            //lo guardo en la sesion
            $_SESSION['search_result'] = $res;
            header('Location: index.php?controller=All&action=mostrarCards');
        }
    }
    /**
     * Metodo que pide al servidor la info dede un tema seleccionado DESDE UN SEARCH
     */

    public function mostrarCards($data = null, $dataIndex = 0, $totalData = 0)
    {
        // Obtener la respuesta de la sesión
        $data = $data ?? (isset($_SESSION['search_result']) ? $_SESSION['search_result'] : null);

        // Recuperar información de la sesión
        $dataIndex = isset($_SESSION['dataIndex']) ? (int)$_SESSION['dataIndex'] : 0;
        $totalData = isset($_SESSION['totalData']) ? (int)$_SESSION['totalData'] : 0;

        // Actualizar índice de datos si se hace clic en Anterior o Siguiente
        if (isset($_POST['accion']) && $_POST['accion'] == 'anterior' && $dataIndex > 0) {
            $dataIndex--;
        } elseif (isset($_POST['accion']) && $_POST['accion'] == 'siguiente' && $dataIndex < $totalData - 1) {
            $dataIndex++;
        }

        // Guardar información en la sesión
        $_SESSION['dataIndex'] = $dataIndex;
        $_SESSION['totalData'] = $totalData;
        $_SESSION['search_result'] = $data;



        // Redirigir a la vista
        $this->View->mostrarCards($data, $dataIndex, $totalData);
    }




    function mostrarError()
    {

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
    }

    /**
     * Metodo que muestra un formulario donde tenemso qeu cargar diversos select
     * 
     */
    public function formMultiSelect()
    {
        //LO PRIMERO ES PEDIR AL SERVICIO LOS SELECTS
        $category = $this->Service->requestListSelect("https://opentdb.com/api_category.php"); //$res es un array con la info para cargar un select
        $difficulty = $this->Service->requestListSelect("https://opentdb.com/api_category.php"); //$res es un array con la info para cargar un select
        $type = $this->Service->requestListSelect("https://opentdb.com/api_category.php"); //$res es un array con la info para cargar un select

    }
}
