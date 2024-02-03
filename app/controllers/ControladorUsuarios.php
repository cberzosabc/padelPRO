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
            
            $nombre=htmlentities($_POST['name']);
            $email=htmlentities($_POST['email']);
            $password=htmlentities($_POST['password']);
            $foto='';

                //Validación 

            //Conectamos con la BD
            $connexionDB = new ConnectionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
            $conn = $connexionDB->getConnection();

            //Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuarioDAO($conn);
            if($usuariosDAO->getByEmail($email) != null){
                mostrarMensaje("Ya hay un usuario con ese email");
            }elseif(empty($password)) {
                mostrarMensaje("Introduce una contraseña");
            } elseif(strlen($password) < 6) {
                mostrarMensaje("La contraseña debe tener, al menos, 6 dígitos");
            }else{
                            //Copiamos la foto al disco
        if($_FILES['foto']['type'] != 'image/jpeg' &&
        $_FILES['foto']['type'] != 'image/webp' &&
        $_FILES['foto']['type'] != 'image/png')
        {
            mostrarMensaje("la foto no tiene el formato admitido, debe ser jpeg o webp");
        }
        else{
            //Calculamos un hash para el nombre del archivo
            $foto = generarNombreArchivo($_FILES['foto']['name']);

            //Si existe un archivo con ese nombre volvemos a calcular el hash
            while(file_exists("fotosUsuarios/$foto")){
                $foto = generarNombreArchivo($_FILES['foto']['name']);
            }
            
            if(!move_uploaded_file($_FILES['foto']['tmp_name'], "web/fotosUsuarios/$foto")){
                die("Error al copiar la foto a la carpeta fotosUsuarios");
            }
        }
                    //Insertamos en la BD

                    $usuario = new Usuario();
                    $usuario->setNombre($nombre);
                    $usuario->setEmail($email);
                    //encriptamos el password
                    $passwordCifrado = password_hash($password,PASSWORD_DEFAULT);
                    $usuario->setPassword($passwordCifrado);
                    $usuario->setFoto($foto);
                    $usuario->setSid(sha1(rand()+time()), true);
    
                    if($usuariosDAO->insert($usuario)){
                        Session::iniciarSesion($usuario);
                        header("location: index.php?accion=ver_fechas");
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
        $usuario=$usuarioDAO->getByEmail($email);
        if($usuario != null){
            
            if(password_verify($password, $usuario->getPassword())){
                $sid = sha1(uniqid(rand(), true)); // Genera un nuevo SID aquí
                $usuarioDAO->actualizarSid($usuario->getId(), $sid); // Actualiza el SID en la base de datos
                
                // Establece la cookie 'sid' con el nuevo SID
                setcookie('sid', $sid, time() + (86400 * 30), "/"); // Expira en 30 días
                
                Session::iniciarSesion($usuario);
                header('location: index.php?accion=ver_fechas');
            }else{
                mostrarMensaje("La contraseña no es correcta");
            }
        }else{
            mostrarMensaje("Usuario no encontrado");
        }
    }
}

public function logout(){
    // Crear la conexión a la base de datos
    $conexion = new ConnectionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $conexion->getConnection();

    // Crear una instancia de UsuarioDAO con la conexión establecida
    $usuarioDAO = new UsuarioDAO($conn);

    // Obtener el usuario actual de la sesión
    $usuario = Session::getUsuario(); // Asumiendo que tienes un método para obtener el usuario actual

    if($usuario != null){
        // Llamar a actualizarSid para borrar el sid de la base de datos
        $usuarioDAO->actualizarSid($usuario->getId(), null);
    }

    // Cerrar la sesión y borrar la cookie 'sid'
    Session::cerrarSesion();
    setcookie('sid', '', time() - 3600, "/"); // Borra la cookie

    // Redirigir al usuario a la página principal
    header('location: index.php?accion=inicio');
}
}