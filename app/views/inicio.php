
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="web/style/style.css">
    <title>Padel pro</title>
  </head>
  <body class="login-body">
  <?php if(isset($_SESSION['mensaje_error'])): ?>
			<p class="error"><?= $_SESSION['mensaje_error']; ?></p>
			<?php unset($_SESSION['mensaje_error']); ?>
			<?php endif; ?>
<div class="container" id="container">

	<div class="form-container sign-up-container">
	<form action="index.php?accion=registrar" method="POST" enctype="multipart/form-data">
			<h1>Crea tu cuenta</h1>
			<input type="text" placeholder="Nombre" name="name"/>
			<input type="email" placeholder="Email" name="email"/>
			<input type="password" placeholder="Password" name="password"/>
			<p>Selecciona una foto de perfil:</p>
            <input type="file" name="foto" accept="image/jpeg, image/webp, image/png"><br>

			<button>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="index.php?accion=login" method="POST">
			<h1>Inicia sesión</h1>
			<input type="email" placeholder="Email" name="email" />
			<input type="password" placeholder="Password" name="password" />
			<button>Entrar</button>
		</form>
	</div>
	
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>¿Ya te has registrado?</h1>
				<p>Mantente conectado para encontrar los mejores horarios</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>¡Hola!</h1>
				<p>Regístrate y reserva tu pista de pádel hoy mismo</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});
</script>
  </body>
</html>