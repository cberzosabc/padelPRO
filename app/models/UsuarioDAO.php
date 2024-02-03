<?php 

class UsuarioDAO{
    private mysqli $conn;

    public function __construct($conn){
        $this->conn=$conn;
    }
    public function getByEmail($email):Usuario|null{
        if(!$stmt=$this->conn->prepare("SELECT  * FROM usuarios WHERE email = ?")){
            echo "Error en la SQL: ". $this->conn->error;
        }
        $stmt->bind_param('s',$email);

        //Ejecutamos sql 
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result=$stmt->get_result();

        //Si ha encontrado algún resultado devolvemos un objeto de la clase Usuario, sino null
        if($result->num_rows >= 1){
            $usuario=$result->fetch_object(Usuario::class);
            return $usuario;
        }else{
            return null;
        }
    }

    /**
     * Inserta en la base de datos el usuario que recibe como parámetro
     * @return idUsuario Devuelve el id autonumérico que se le ha asignado al usuario o false en caso de error
     */
    function insert(Usuario $usuario): int|bool{
        if(!$stmt=$this->conn->prepare("INSERT INTO usuarios (nombre, email, password, foto, sid) VALUES (?,?,?,?,?)")){
            die("Error al preparar la consulta insert: " .$this->conn->error);
        }
        $nombre=$usuario->getNombre();
        $email=$usuario->getEmail();
        $password=$usuario->getPassword();
        $foto=$usuario->getFoto();
        $sid=$usuario->getSid();
        $stmt->bind_param('sssss',$nombre,$email,$password,$foto,$sid);
        if($stmt->execute()){
            return $stmt->insert_id;
        }else{
            return false;
        }
    }
    public function getBySid($sid){
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE sid = ?");
        $stmt->bind_param('s', $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return $result->fetch_object(Usuario::class);
        } else {
            return null;
        }
    }
    
    public function actualizarSid($idUsuario, $sid){
        $stmt = $this->conn->prepare("UPDATE usuarios SET sid = ? WHERE id = ?");
        $stmt->bind_param('si', $sid, $idUsuario);
        $stmt->execute();
    }
}