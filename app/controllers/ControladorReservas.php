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
        $fecha=$_GET['fecha'];
        $tramoDAO=new TramoDAO($conn);
        $tramos=$tramoDAO->obtenerTodosLosTramos($fecha);

        $tramosArray = []; 
        foreach ($tramos as $tramo) {
            $tramosArray[] = [
                'hora' => $tramo->getHora(),
                'disponible' => true 
            ];
        }
    

        echo json_encode($tramosArray);
    }
}