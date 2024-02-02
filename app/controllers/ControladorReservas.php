<?php 

class ControladorReservas{
    public function ver_fechas(){
        $connection=new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn=$connection->getConnection();

        require 'app/views/calendario.php';
    }
}