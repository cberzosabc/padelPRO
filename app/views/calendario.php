<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
<h1 class="titulo-calendario">Febrero 2024</h1>
<p>Reserva tu clase de padel</a>
<ul>
	<li><time datetime="2022-02-01">1</time></li>
	<li><time datetime="2022-02-02">2</time></li>
	<li><time datetime="2022-02-03">3</time></li>
	<li><time datetime="2022-02-04">4</time></li>
	<li><time datetime="2022-02-05">5</time></li>
	<li><time datetime="2022-02-06">6</time></li>
	<li><time datetime="2022-02-07">7</time></li>
	<li><time datetime="2022-02-08">8</time></li>
	<li><time datetime="2022-02-09">9</time></li>
	<li><time datetime="2022-02-10">10</time></li>
	<li><time datetime="2022-02-11">11</time></li>
	<li><time datetime="2022-02-12">12</time></li>
	<li><time datetime="2022-02-13">13</time></li>
	<li><time datetime="2022-02-14">14</time></li>
	<li><time datetime="2022-02-15">15</time></li>
	<li><time datetime="2022-02-16">16</time></li>
	<li><time datetime="2022-02-17">17</time></li>
	<li><time datetime="2022-02-18">18</time></li>
	<li><time datetime="2022-02-19">19</time></li>
	<li><time datetime="2022-02-20">20</time></li>
	<li><time datetime="2022-02-21">21</time></li>
	<li><time datetime="2022-02-22">22</time></li>
	<li><time datetime="2022-02-23">23</time></li>
	<li><time datetime="2022-02-24">24</time></li>
	<li><time datetime="2022-02-25">25</time></li>
	<li><time datetime="2022-02-26">26</time></li>
	<li><time datetime="2022-02-27">27</time></li>
	<li><time datetime="2022-02-28">28</time></li>
	<li><time datetime="2022-02-29">29</time></li>
</ul>
</body>
</html>