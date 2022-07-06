let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
})

function iniciarApp(){
    mostrarSeccion(); //muestra y oculta las secciones
    tabs(); //cambia la sección cuando se presionen los tabs
    botonesPaginador(); //agrega o quita los botones inferiores del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();
    idCliente(); //asigando el id al objeto de cita
    nombreCliente(); //agrega el nombre del cliente al objeto de cita
    seleccionarFecha(); //valida y agrega una fecha para el objeto de cita
    seleccionarHora(); //valida y agrega la hora para el objeto de cita
    mostrarResumen(); //muestra la información resumida para la cita creada
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click',(e)=>{
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function mostrarSeccion(){
    //ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }

    //seleccionar la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //cambinado el color del tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function botonesPaginador(){
    const pagAnterior = document.querySelector('#anterior');
    const pagSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        pagAnterior.classList.add('ocultar');
        pagSiguiente.classList.remove('ocultar');
    }
    else if(paso === 3){
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.add('ocultar');
        if(paso === 3){
            mostrarResumen();
        }
    }
    else{
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.remove('ocultar');

    }
    mostrarSeccion();
}

function paginaAnterior(){
    const pagAnterior = document.querySelector('#anterior');
    pagAnterior.addEventListener('click',()=>{
        if(paso <= pasoInicial) return;
        paso --;
        botonesPaginador();
    });
}

function paginaSiguiente(){
    const pagSiguiente = document.querySelector('#siguiente');
    pagSiguiente.addEventListener('click',()=>{
        if(paso >= pasoFinal) return;
        paso ++;
        botonesPaginador();
    });
}

async function consultarAPI(){
    try {
        const server = window.location.origin;
        const url = `${server}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio

        // const nombreServicio = document.createElement('P');
        // nombreServicio.classList.add('nombre-servicio');
        // nombreServicio.textContent = nombre;

        // const precioServicio = document.createElement('P');
        // precioServicio.classList.add('precio-servicio');
        // precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.innerHTML= `<p>Servicio</p>`;
        // servicioDiv.classList.add('servicio');
        // servicioDiv.dataset.idServicio = id;
        // servicioDiv.onclick = function () {
        //     agregarServicio(servicio);
        // }

        // servicioDiv.appendChild(nombreServicio);
        // servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}


function agregarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita;
    //identificando al elemento que recibe el click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //comprobar si un servicio ya fue agregado
    if(servicios.some(agregado => agregado.id === id)){
        //eliminar
        cita.servicios = servicios.filter(agregado => agregado.id != id);
        divServicio.classList.remove('seleccionado');
    }
    //si no esta, lo agrego
    else{
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

    // console.log(cita);
}

function nombreCliente(){
    //asiganado el atributo nombre del objeto cita, el valor del input nombre
    cita.nombre = document.querySelector('#nombre').value;
}

function idCliente(){
    //asiganado el atributo id del objeto cita, el valor del input id
    cita.id = document.querySelector('#id').value;
}

function seleccionarFecha(){
    //seleccionando el input de fecha
    const inputFecha = document.querySelector('#fecha');

    //agregando un evento de escucha de tipo input
    inputFecha.addEventListener('input',(e)=>{

        //creando un objeto de tipo date
        const dia = new Date(e.target.value).getUTCDay();
        // console.log(dia);

        if([0,6].includes(dia)){
            e.target.value = "";
            // console.log("fin de semana no valido");
            mostrarAlerta('Fines de semana no permitidos','error','.formulario');
        }
        else{
            // console.log("dia si permitido");
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input',function(e){
        const horaCita = e.target.value;
        //el 0 indica que tomara la primera parte del arreglo
        // hora = horacita[0]=hora horacita[1]=minutos
        const hora = horaCita.split(":")[0];
        //indicando que solo habra servicio de 9 a 6
        if(hora<9 || hora>18){
            e.target.value="";
            mostrarAlerta("Hora fuera de servicio",'error','.formulario');
        }
        else{
            cita.hora = e.target.value;
            // console.log(cita);
        }
    });
}

function mostrarAlerta(mensaje, tipo, ubicacion, desaparece = true){

    //si ya hay una alerta previa, no crear más
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    };

    //creando la alerta y agregando el mensaje y el tipo
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //agregando la alaerta a la referencia
    const referencia = document.querySelector(ubicacion);
    referencia.appendChild(alerta);

    //eliminando la alerta luego de 2.5 segundos
    if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 2500);
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //limpiando el div de resumen para evitar tener la alerta cunado no debe estar
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    //comprobando que exista por lo menos un servicio o algun dato del cliente
    if(Object.values(cita).includes('') || cita.servicios.length ===0){
        mostrarAlerta('Faltan datos o seleccionar servicios','error','.contenido-resumen',false);
        return;
    }

    //creando el heading y parrafos para la información personal del cliente
    const headingDatos = document.createElement('h3');
    headingDatos.innerHTML = `Datos personales`;

    //formatear el div de resumen
    const {nombre, fecha, hora , servicios} = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //dandole formato a la fecha
    //al dia se les suma 2 porque existe un desfase de 1 unidad pero como creamos dos veces el objeto Date es necesario cubrir este desfase 2 veces
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate()+2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year,mes,dia));

    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX',opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora} hrs`;

    //agregando los parrafos al div de resumen
    resumen.appendChild(headingDatos);
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    //creando un titulo para los servicios
    const headingServicios = document.createElement('h3');
    headingServicios.innerHTML = `Resumen de servicios`;

    //agregando el titulo al div de servicios
    resumen.appendChild(headingServicios);

    //iterando cada servicio
    servicios.forEach(servicio => {

        //creando los parrafos para cada servicios
        const {id, nombre, precio} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.innerHTML = `<span>Servicio: </span> ${nombre}`;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: $</span> ${precio}`;

        //agregando los parrafos a un div de servicio
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        //agregando el div de servicio al div de resumen
        resumen.appendChild(contenedorServicio);

    });

    //boton para crear cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent='Reservar cita';
    botonReservar.onclick=reservarCita;

    resumen.appendChild(botonReservar);

}

async function reservarCita(){
    const {id,nombre,fecha, hora,servicios} = cita;

    const idServicios = servicios.map(servicio=>servicio.id);
    console.log(idServicios);

    const datos = new FormData();
    datos.append('fecha',fecha);
    datos.append('hora',hora);
    datos.append('usuarioId',id);
    datos.append('servicios',idServicios);

    console.log([...datos]);
   
    try {
        const url = 'https://serene-river-49406.herokuapp.com//api/citas'
        const respuesta = await fetch(url,{
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json()
        console.log(resultado.resultado);
        if(resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita creada',
                text: 'Tu cita fue creada correctamente!',
                button: 'OK'
              }).then(()=>{
                  window.location.reload();
              });
        }
        
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita'
          })
    }
}