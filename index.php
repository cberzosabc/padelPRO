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
require_once 'app/config/functions.php';
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
    'logout'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'logout', 
                    'privada'=>true),
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

if(!Session::existeSesion() && isset($_COOKIE['sid'])){
    $sid = $_COOKIE['sid'];
    $usuario = $usuarioDAO->getBySid($sid);
    if($usuario != null){
        Session::iniciarSesion($usuario);
        // Opcionalmente redirigir al usuario a una página interna
    } else {
        // La cookie 'sid' no coincide con ningún usuario, posiblemente porque expiró en la base de datos o fue manipulada.
        setcookie('sid', '', time() - 3600, "/"); // Borra la cookie
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


