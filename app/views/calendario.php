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
<h1 class="titulo-calendario">Reserva tu pista</h1>
<input type="date" id="fechaSeleccionada" name="fecha">
    <div id="tablaHoras">

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFecha = document.getElementById('fechaSeleccionada');
    var fecha = new Date();
    var dia = fecha.getDate().toString().padStart(2, '0');
    var mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Enero es 0
    var ano = fecha.getFullYear();
    document.getElementById('fechaSeleccionada').min = `${ano}-${mes}-${dia}`;
    // Función para construir la tabla de tramos horarios
    function construirTabla(data) {
    const tabla = document.createElement('table');
    tabla.classList.add('tabla-horas'); // Añade una clase para estilizar la tabla

    // Añadimos un encabezado a la tabla como referencia
    const encabezado = tabla.createTHead();
    const filaEncabezado = encabezado.insertRow();
    const celdaHoraEncabezado = filaEncabezado.insertCell();
    celdaHoraEncabezado.textContent = 'Hora';
    const celdaEstadoEncabezado = filaEncabezado.insertCell();
    celdaEstadoEncabezado.textContent = 'Estado';

    // Iterar sobre cada tramo (hora) recibido en data
    data.forEach(tramo => {
        const fila = tabla.insertRow();
        const celdaHora = fila.insertCell();
        celdaHora.textContent = tramo.hora;

        const celdaEstado = fila.insertCell();
        celdaEstado.textContent = tramo.disponible ? 'Disponible' : 'Ocupado';
        celdaEstado.classList.add(tramo.disponible ? 'hora-disponible' : 'hora-reservada');
        celdaEstado.setAttribute('data-idTramo', tramo.id);
        celdaEstado.setAttribute('data-fecha', inputFecha.value);

        // Asignar colores según el estado del tramo
        if (!tramo.disponible && tramo.reservado_por_usuario) {
            celdaEstado.style.backgroundColor = 'skyblue';
            celdaEstado.style.fontWeight= 'bold';
            celdaEstado.style.color='white';
             // Azul si está reservado por el usuario actual
            celdaEstado.textContent = 'Reservado por ti';
            
        } else if (!tramo.disponible) {
            celdaEstado.style.backgroundColor = 'red'; // Rojo si está ocupado
            celdaEstado.style.color='white';
        } else {
            celdaEstado.style.backgroundColor = 'white'; // Blanco si está disponible

        }

        celdaEstado.style.cursor = 'pointer';
    });

    return tabla;
}

    // Actualizar la tabla de tramos horarios al cambiar la fecha
    inputFecha.addEventListener('change', function() {
        const fecha = this.value;
        fetch(`index.php?accion=horas_disponibles&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                const tablaHoras = document.getElementById('tablaHoras');
                tablaHoras.innerHTML = ''; // Limpiar tabla
                tablaHoras.appendChild(construirTabla(data));
            })
            .catch(error => console.error('Error:', error));
    });

    //Funciones del modal 
    const modal=document.getElementById('modalConfirmacion');
    const span=document.getElementById('close');
    const botonConfirmar=document.getElementById('confirmarAccion');
    const botonCancelar=document.getElementById('cancelarAccion');
    // Manejar clic en tramo horario para reservar o cancelar
    document.getElementById('tablaHoras').addEventListener('click', function(event) {
        if(event.target.classList.contains('hora-disponible') || event.target.classList.contains('hora-reservada')) {
            const idTramo = event.target.getAttribute('data-idTramo');
            const fecha = event.target.getAttribute('data-fecha');
            const accion = event.target.classList.contains('hora-disponible') ? 'reservar' : 'cancelar';
            let mensajeConfirmacion=document.getElementById('modalTexto');
            if(accion==="reservar"){
                modal.style.display='block';
                mensajeConfirmacion.textContent="¿Quieres reservar esta pista?";
            }else{
                modal.style.display='block';
                mensajeConfirmacion.textContent="Vas a cancelar la reserva. ¿Estás seguro?";
            }

            botonConfirmar.onclick=function(){
                fetch(`index.php?accion=${accion}`, { //Esto determina la accion que se seleccione dependiendo del estado
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idTramo, fecha })
            })
            .then(response => response.json())
            .then(data => {
                inputFecha.dispatchEvent(new Event('change')); // Recargar tramos
            })
            modal.style.display='none';
            }

            botonCancelar.onclick=function(){
                modal.style.display='none';
            }

            span.onclick=function(){
                modal.style.display='none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                modal.style.display = "none";
                }
            }


        }
    });
});
</script>

<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <span id='close' class="close">&times;</span>
        <p id="modalTexto"></p>
        <button id="confirmarAccion">Confirmar</button>
        <butto id="cancelarAccion">Cancelar</butto>
    </div>
</div>
</body>
</html>