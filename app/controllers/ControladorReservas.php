<?php 

class ControladorReservas{
    public function ver_fechas() {
        $connection = new ConnectionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connection->getConnection();

        // Pasar $tramos a la vista para que pueda utilizarlos
        require 'app/views/calendario.php';
    }


    public function horas_disponibles(){
        $connection = new ConnectionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connection->getConnection();
        header('Content-Type: application/json');

        $idUsuario=Session::getUsuario()->getId();
        
        $fecha=$_GET['fecha'];
        $tramoDAO=new TramoDAO($conn);
        $tramos=$tramoDAO->obtenerTramosConDisponibilidad($fecha, Session::getUsuario()->getId());

        echo json_encode($tramos);
    }

    public function reservar(){
        $connection = new ConnectionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connection->getConnection();

        //Como recibe los datos como json:
        $data=json_decode(file_get_contents('php://input'),true);

        //Ahora recogemos los valores
        $idUsuario=Session::getUsuario()->getId();
        $idTramo=$data['idTramo'];
        $fecha=$data['fecha'];

        //Cremos la reserva que le vamos a pasar
        $reserva=new Reserva();
        $reserva->setIdUsuario($idUsuario);
        $reserva->setIdTramo($idTramo);
        $reserva->setFecha($fecha);

        //Llamamos al método insertar en ReservaDAO, pasándole el objeto que acabamos de crear
        $reservaDAO=new ReservaDAO($conn);
        if($reservaDAO->insertar($reserva)){
            echo json_encode(['success'=>true]);
        }else{
            echo json_encode(['success'=>false]);
        }
    }

    public function cancelar(){
        $connection = new ConnectionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connection->getConnection();

        //Como recibe los datos por json 
        $data=json_decode(file_get_contents('php://input'),true);

        //Ahora recogemos los valores
        $idUsuario=Session::getUsuario()->getId();
        $idTramo=$data['idTramo'];
        $fecha=$data['fecha'];

        $reserva=new Reserva();
        $reserva->setIdUsuario($idUsuario);
        $reserva->setIdTramo($idTramo);
        $reserva->setFecha($fecha);

        //Preparamos los datos para la cancelación 
        $reservaDAO=new ReservaDAO($conn);
         if($reservaDAO->eliminar($reserva)){
            echo json_encode(['success'=>true]);
        }else{
            echo json_encode(['success'=>false]);
        }
    }
}