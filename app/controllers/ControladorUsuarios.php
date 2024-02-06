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
        $fotoPredeterminada = 'imagen-por-defecto.png'; //cremos una foto predeterminada por si el usuario no selecciona una
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
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && 
                in_array($_FILES['foto']['type'], ['image/jpeg', 'image/webp', 'image/png'])) {
                
                // Procesamiento de la foto subida
                $foto = generarNombreArchivo($_FILES['foto']['name']);
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], "web/fotosUsuarios/$foto")) {
                    mostrarMensaje("Error al copiar la foto a la carpeta fotosUsuarios");
                    $foto = $fotoPredeterminada; // Usa la imagen predeterminada si falla
                }
            } else {
                // Si no se sube una foto, asigna la predeterminada
                $foto = $fotoPredeterminada;
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
                        $idUsuario = $conn->insert_id; //Forzamos que inserte un id al usuario y no salga a null
                        $usuario->setId($idUsuario); 
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