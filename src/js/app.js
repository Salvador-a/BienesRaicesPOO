document.addEventListener('DOMContentLoaded', function () {
    // Llama a la función que establece los event listeners
    eventListener();

    // Aplica el modo oscuro si está configurado por el usuario o por la preferencia del sistema
    darkMode();

    // Llama a la función que contiene otros event listeners
    eventListeners();

    // Si la ventana es de un tamaño pequeño, agrega una clase temporal a la navegación
    if (window.innerWidth <= 768) {
        temporaryClass(document.querySelector('.navegacion'), 'visibilidadTemporal', 500);
    }

    // Elimina el texto de confirmación de operaciones CRUD en admin/index.php
    borraMensaje();
});

// Función para aplicar el modo oscuro
function darkMode() {
    // Verifica si el usuario prefiere el modo oscuro según su configuración o preferencia del sistema
    const prefiereDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

    // Aplica el modo oscuro o claro según la preferencia del usuario
    document.body.classList.toggle('dark-mode', prefiereDarkMode.matches);

    // Escucha cambios en la preferencia del usuario y ajusta el modo oscuro en consecuencia
    prefiereDarkMode.addEventListener('change', function () {
        document.body.classList.toggle('dark-mode', prefiereDarkMode.matches);
    });

    // Obtén el botón de modo oscuro y agrega un evento clic para cambiar entre modos
    const botonDarkMode = document.querySelector('.dark-mode-boton');
    botonDarkMode.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
    });
}

// Función para establecer event listener en el botón de navegación móvil
function eventListener() {
    const mobileMenu = document.querySelector('.mobile-menu');
    mobileMenu.addEventListener('click', navegacionResponsive);
}

// Función para mostrar/ocultar la navegación en dispositivos móviles
function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');

    // Alternar la clase 'mostrar' para mostrar u ocultar la navegación
    navegacion.classList.toggle('mostrar');
}

// Función para eliminar un mensaje después de cierto tiempo
function borraMensaje() {
    const mensajeConfirm = document.querySelector('.alerta');
    
    // Si hay un mensaje de confirmación
    if (mensajeConfirm !== null) {
        // Establece un temporizador para eliminar el mensaje después de 3500 milisegundos (3.5 segundos)
        setTimeout(function () {
            const padre = mensajeConfirm.parentElement;
            padre.removeChild(mensajeConfirm);
            console.log("Mensaje eliminado después de 3.5 segundos");
        }, 3500);
    } else {
        console.log("No hay mensaje de confirmación para eliminar");
    }
}

// Otras funciones (eventListeners y temporaryClass) deberían definirse en tu código para evitar errores.
