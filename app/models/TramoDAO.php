<?php

class TramoDAO{
    private mysqli $conn;

    public function __construct($conn){
        $this->conn=$conn;
    }
    public function obtenerTodosLosTramos($fecha) {
        // Crear un array para almacenar los objetos Tramo
        $tramos = [];
        // Preparar la consulta SQL para seleccionar todos los tramos
        if (!$stmt = $this->conn->prepare("SELECT t.id, t.hora FROM tramos t 
        WHERE NOT EXISTS (
            SELECT 1 FROM reservas r 
            WHERE r.id_tramo = t.id 
            AND r.fecha = ?
        ) 
        ORDER BY t.hora ASC")) {
            echo "Error en la SQL: " . $this->conn->error;
            return $tramos; // Retorna el array vacío en caso de error
        }
    
        $stmt->bind_param("s", $fecha); // Añade esta línea
        $stmt->execute();
    
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
    
        // Iterar sobre los resultados y crear objetos Tramo
        while ($fila = $result->fetch_assoc()) {
            $tramo = new Tramo();
            $tramo->setId($fila['id']);
            $tramo->setHora($fila['hora']);
            $tramos[] = $tramo;
        }
        // Devolver el array de objetos Tramo
        return $tramos;
    }
    
}
