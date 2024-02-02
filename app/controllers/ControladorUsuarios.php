<?php 

class ControladorUsuarios{
    public function inicio(){
                //Creamos la conexión utilizando la clase que hemos creado
                $connexionDB = new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
                $conn = $connexionDB->getConnection();
                require 'app/views/inicio.php';
    }

    public function registrar(){
        $error='';

        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Limpiamos los datos
            
            $nombre=htmlentities($_POST['nombre']);
            $email=htmlentities($_POST['email']);
            $password=htmlentities($_POST['password']);

            //Validación 

            //Conectamos con la BD
            $connexionDB = new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
            $conn = $connexionDB->getConnection();

            //Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuarioDAO($conn);
            if($usuariosDAO->getByEmail($email) != null){
                echo "Ya hay un usuario con ese email";
            }else    //Si no hay error
                {
                    //Insertamos en la BD

                    $usuario = new Usuario();
                    $usuario->setNombre($nombre);
                    $usuario->setEmail($email);
                    //encriptamos el password
                    $passwordCifrado = password_hash($password,PASSWORD_DEFAULT);
                    $usuario->setPassword($passwordCifrado);
                    $usuario->setSid(sha1(rand()+time()), true);

                    if($usuariosDAO->insert($usuario)){
                        header("location: index.php");
                        die();
                    }else{
                        $error = "No se ha podido insertar el usuario";
                    }
                }
            }
        }  


    public function login(){
        //Volvemos a crear la conexion
        $conexion=new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn=$conexion->getConnection();
        //Limpia los datos
        if($_SERVER['REQUEST_METHOD']=='POST'){
        $email=htmlentities($_POST['email']);
        $password=htmlentities($_POST['password']);

        //Valida el usuario 
        $usuarioDAO=new UsuarioDAO($conn);
        if($usuarioDAO->getByEmail($email)!=null){
            $usuario=$usuarioDAO->getByEmail($email);
            if(password_verify($password, $usuario->getPassword())){
                //email y password correctos, inicia sesión 
                Session::iniciarSesion($usuario);
                //Redirigimos a calendario
                header('location: index.php?accion=ver_fechas');
            }else{
                echo "La contraseña esta mal";
            }
        }else{
            echo "No hay user";
        }
    }else{
        echo "Entra";
    }
}
}