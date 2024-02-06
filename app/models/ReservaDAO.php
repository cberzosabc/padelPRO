<?php

class ReservaDAO{
     private mysqli $conn;
     public function __construct($conn){
        $this->conn=$conn;
     }

     public function insertar(Reserva $reserva):int|bool{
      if(!$stmt=$this->conn->prepare("INSERT INTO reservas(id_usuario, id_tramo, fecha) VALUES (?,?,?)")){
         echo "Error en la SQL: ". $this->conn->error;
     }
     $idUsuario=$reserva->getIdUsuario();
     $idTramo=$reserva->getIdTramo();
     $fecha=$reserva->getFecha();
      $stmt->bind_param("iis", $idUsuario, $idTramo, $fecha);
      if($stmt->execute()){
         return $stmt->insert_id;
     }else{
         return false;
     }
     }

     public function eliminar(Reserva $reserva):int|bool{
        if(!$stmt=$this->conn->prepare("DELETE FROM reservas WHERE id_usuario=? AND id_tramo=? AND fecha=?")){
            echo "Error en la SQL: ". $this->conn->error;
        }
        $idUsuario=$reserva->getIdUsuario();
        $idTramo=$reserva->getIdTramo();
        $fecha=$reserva->getFecha();
         $stmt->bind_param("iis", $idUsuario, $idTramo, $fecha);
         if($stmt->execute()){
            return $stmt->insert_id;
        }else{
            return false;
        }
     }
}