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
        celdaEstado.setAttribute('data-reservado-por-usuario', tramo.reservado_por_usuario ? 'true' : 'false');

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
        document.getElementById('preloader').style.display='block';
        fetch(`index.php?accion=horas_disponibles&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                setTimeout(() => {
                const tablaHoras = document.getElementById('tablaHoras');
                tablaHoras.innerHTML = ''; // Limpiar tabla
                tablaHoras.appendChild(construirTabla(data));
                document.getElementById('preloader').style.display='none';
            }, 1000);
                
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
            const reservadoPorUsuario = event.target.getAttribute('data-reservado-por-usuario') === 'true';
            const accion = event.target.classList.contains('hora-disponible') ? 'reservar' : 'cancelar';
            const modalTexto = document.getElementById('modalTexto');

            // Cambiar el texto del modal según la acción y si el usuario hizo la reserva
            if (accion === "reservar") {
                modalTexto.textContent = "¿Quieres reservar esta pista?";
            } else if (accion==='cancelar' && !reservadoPorUsuario) {
                modalTexto.textContent = "No puedes cancelar esta reserva porque no fue hecha por ti.";
                botonCancelar.style.display='none';
                botonConfirmar.style.marginLeft='150px';
            } else {
                modalTexto.textContent = "Vas a cancelar esta reserva. ¿Estás seguro?";
                botonCancelar.style.display='inline';
                botonConfirmar.style.margin='0 60px';
            }

            // Mostrar modal
            modal.style.display = 'block';


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
