<?php

class TramoDAO{
    private mysqli $conn;

    public function __construct($conn){
        $this->conn=$conn;
    }

    public function obtenerTramosConDisponibilidad($fecha,$idUsuario) {
        $tramos = [];
        $query = "SELECT t.id, t.hora,
                         (SELECT COUNT(*) FROM reservas r WHERE r.id_tramo = t.id AND r.fecha = ?) as reservado,
                         (SELECT COUNT(*) FROM reservas r WHERE r.id_tramo = t.id AND r.fecha = ? AND r.id_usuario = ?) as reservado_por_usuario
                  FROM tramos t
                  ORDER BY t.hora ASC";
                  
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("ssi", $fecha, $fecha, $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($fila = $result->fetch_assoc()) {
                $tramo = [
                    'id' => $fila['id'],
                    'hora' => $fila['hora'],
                    'disponible' => $fila['reservado'] == 0,
                    'reservado_por_usuario' => $fila['reservado_por_usuario'] > 0
                ];
                $tramos[] = $tramo;
            }
        }
        return $tramos;
    }
    
    
}
