<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src='./functions.js'></script>
    <title>Document</title>
</head>
<body class="body-calendario">
<header>
	<div class="header">
		<div class="myaccount">
		<?php if(Session::getUsuario()): ?>
			<img src="web/fotosUsuarios/<?=Session::getUsuario()->getFoto()?>" class="fotoUsuario"><p>¡Hola, <span class="nombreUsuario"><?= Session::getUsuario()->getNombre() ?>!</p>
		</div>
		<div class="logout">
		</span> <a href="index.php?accion=logout">Salir <i class="fa-solid fa-right-from-bracket"></i></a>
		</div>
		<?php endif;?>
	</div>
</header>
<h1 class="titulo-calendario">Reserva tu pista</h1>
<div id="preloader" style="display: none; position: fixed; top: 56%; left: 50%; transform: translate(-50%, -50%);">
    <img src="web/images/preloader.gif" alt="Cargando..." class="preloader-gif">
</div>
<input type="date" id="fechaSeleccionada" name="fecha">
    <div id="tablaHoras">

    </div>
<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <span id='close' class="close">&times;</span>
        <p id="modalTexto"></p>
        <button id="confirmarAccion">Aceptar</button>
        <butto id="cancelarAccion">Cancelar</butto>
    </div>
</div>
</body>
</html>