<?php 

require_once 'app/config/config.php';
require_once 'app/models/ConnectionDB.php';
require_once 'app/models/Usuario.php';
require_once 'app/models/UsuarioDAO.php';
require_once 'app/models/Reserva.php';
require_once 'app/models/ReservaDAO.php';
require_once 'app/models/Tramo.php';
require_once 'app/models/TramoDAO.php';
require_once 'app/models/Session.php';
require_once 'app/controllers/ControladorUsuarios.php';
require_once 'app/controllers/ControladorReservas.php';

session_start();

$mapa = array(
    'inicio'=>array('controlador'=>'ControladorUsuarios',
                    'metodo'=>'inicio',
                    'privada'=>false),
    'login'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'login', 
                    'privada'=>false),
    'registrar'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'registrar', 
                    'privada'=>false),
    'ver_fechas'=>array('controlador'=>'ControladorReservas',
                    'metodo'=>'ver_fechas',
                    'privada'=>false),
);

//Parseo de la ruta
if(isset($_GET['accion'])){ //Compruebo si me han pasado una acción concreta, sino pongo la accción por defecto inicio
    if(isset($mapa[$_GET['accion']])){  //Compruebo si la accción existe en el mapa, sino muestro error 404
        $accion = $_GET['accion']; 
    }
    else{
        //La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
}else{
    $accion='inicio';   //Acción por defecto
}

//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
//if( !isset($_SESSION['email']) && isset($_COOKIE['sid'])){
if( !Session::existeSesion() && isset($_COOKIE['sid'])){
    //Conectamos con la bD
    $connexionDB = new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
    $conn = $connexionDB->getConnection();
    
    //Nos conectamos para obtener el id y la foto del usuario
    $usuariosDAO = new UsuarioDAO($conn);
    if($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])){
        //$_SESSION['email']=$usuario->getEmail();
        //$_SESSION['id']=$usuario->getId();
        //$_SESSION['foto']=$usuario->getFoto();
        Sesion::iniciarSesion($usuario);
    }
    
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){
if(!Session::existeSesion() && $mapa[$accion]['privada']){
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}


//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();