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
<input type="date" id="fechaSeleccionada" name="fecha">
    <div id="tablaHoras"></div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const inputFecha = document.getElementById('fechaSeleccionada');

    inputFecha.addEventListener('change', function() {
        const fecha = this.value;
        fetch(`index.php?accion=horas_disponibles&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Agrega esto para ver qué contiene data
            const tablaHoras = document.getElementById('tablaHoras');
            tablaHoras.innerHTML = ''; // Limpiar tabla actual
            if(Array.isArray(data)){ // Verifica si data es un array
                tablaHoras.appendChild(construirTabla(data));
            } else {
                console.error('La respuesta no es un array');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

function construirTabla(data) {
    const tabla = document.createElement('table');
    data.forEach(tramo => {
        const fila = tabla.insertRow();
        const celdaHora = fila.insertCell();
        celdaHora.textContent = tramo.hora;
        const celdaEstado = fila.insertCell();
        celdaEstado.textContent = tramo.disponible ? 'Disponible' : 'Ocupado';
        celdaEstado.style.backgroundColor = tramo.disponible ? 'green' : 'red';
    });
    return tabla;
}

</script>
</body>
</html>