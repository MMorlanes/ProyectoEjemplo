// Función para obtener vistas de creación/edición
function obtenerVista_EditarCrear(controlador, metodo, destinoId, id = '', params = {}) {
    const parametros = new URLSearchParams({
        controlador: controlador,
        metodo: metodo,
        id: id,
        ...params
    });

    fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
        .then(res => res.text())
        .then(html => {
            document.getElementById(destinoId).innerHTML = html;
        })
        .catch(err => console.error('Error al obtener la vista:', err));
}

// Función para manejar la creación de una nueva opción
function nuevaOpcion(padreId = null, nivel = 1) {
    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', 'capaEditarCrear', '', { id_padre: padreId, nivel: nivel });
}

function nuevaOpcionArriba(opcionId) {
    const filaActual = document.querySelector(`[data-id="${opcionId}"]`);
    if (!filaActual) {
        console.error('No se encontró la fila para la opción con ID:', opcionId);
        return;
    }

    const posicionActual = parseInt(filaActual.getAttribute('data-posicion')) || 1; // Fallback a 1 si no se encuentra
    const nuevaPosicion = Math.max(1, posicionActual); // Evita posiciones menores a 1

    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', `new-option-form-${opcionId}`, '', {
        id_referencia: opcionId,
        posicion: nuevaPosicion,
        nivel: filaActual.getAttribute('data-nivel') || 1,
        ubicacion: 'arriba',
    });
    document.getElementById(`new-option-form-${opcionId}`).style.display = 'flex';
}

function nuevaOpcionDebajo(opcionId) {
    const filaActual = document.querySelector(`[data-id="${opcionId}"]`);
    if (!filaActual) {
        console.error('No se encontró la fila para la opción con ID:', opcionId);
        return;
    }

    const posicionActual = parseInt(filaActual.getAttribute('data-posicion')) || 1; // Fallback a 1 si no se encuentra
    const nuevaPosicion = posicionActual + 1;

    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', `new-option-form-${opcionId}`, '', {
        id_referencia: opcionId,
        posicion: nuevaPosicion,
        nivel: filaActual.getAttribute('data-nivel') || 1,
        ubicacion: 'debajo',
    });
    document.getElementById(`new-option-form-${opcionId}`).style.display = 'flex';
}

// Función para manejar la edición de una opción
function editarOpcion(opcionId) {
    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', 'capaEditarCrear', opcionId);
}



// Función para manejar la eliminación de una opción
function eliminarOpcion(opcionId) {
    if (confirm('¿Estás seguro de eliminar esta opción?')) {
        const parametros = new URLSearchParams({
            controlador: 'Opciones',
            metodo: 'eliminarOpcion',
            id: opcionId
        });

        fetch(`C_Frontal.php?${parametros.toString()}`, { method: 'GET' })
            .then(res => res.json())
            .then(data => {
                if (data.correcto === 'S') {
                    alert('Opción eliminada correctamente');
                    location.reload();
                } else {
                    alert('Error al eliminar la opción: ' + data.msj);
                }
            })
            .catch(err => console.error('Error al eliminar la opción:', err.message));
    }
}

// Función para guardar una opción
function guardarOpcion() {
    const formulario = document.getElementById('formularioOpcion');
    const formData = new FormData(formulario);

    const parametros = new URLSearchParams({
        controlador: 'Opciones',
        metodo: 'guardarOpcion'
    });

    fetch(`C_Frontal.php?${parametros.toString()}`, {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(resultado => {
            if (resultado.correcto === 'S') {
                alert('Opción guardada correctamente');
                location.reload();
            } else {
                alert('Error al guardar la opción: ' + resultado.msj);
            }
        })
        .catch(err => console.error('Error al guardar la opción:', err.message));
}


function cancelarEdicion() {
    const capaEditarCrear = document.getElementById('capaEditarCrear');
    if (capaEditarCrear) {
        capaEditarCrear.innerHTML = '';
    }
}

// Función para añadir un nuevo permiso
function nuevaPermiso(opcionId) {
    if (!opcionId) {
        console.error('No se proporcionó un ID de opción para añadir un permiso.');
        return;
    }
    obtenerVista_EditarCrear('Permisos', 'getVistaNuevo', 'capaEditarCrear', '', { id_Menu: opcionId });
}

// Función para editar un permiso
function editarPermiso(permisoId, botonEditar) {
    if (!permisoId) {
        console.error('No se proporcionó un ID de permiso para editar.');
        return;
    }

    // Verificar si el botón existe
    if (!botonEditar) {
        console.error('No se proporcionó un botón válido.');
        return;
    }

    // Obtener la posición del botón "Editar"
    const rect = botonEditar.getBoundingClientRect();
    const capaEditarCrear = document.getElementById('capaEditarCrear');

    // Llamar a la función que obtiene la vista de edición
    obtenerVista_EditarCrear('Permisos', 'getVistaEditar', 'capaEditarCrear', permisoId);

    // Posicionar la capa de edición a la derecha del botón
    capaEditarCrear.style.top = `${window.scrollY + rect.top}px`; // Posición vertical
    capaEditarCrear.style.left = `${window.scrollX + rect.right + 10}px`; // Posición horizontal (con margen de 10px)
    capaEditarCrear.style.display = 'block'; // Mostrar el formulario
}



// Función para guardar un permiso
function guardarPermiso() {
    const formulario = document.getElementById('formularioPermiso');
    const formData = new FormData(formulario);

    fetch('C_Frontal.php?controlador=Permisos&metodo=guardarPermiso', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'S') {
                alert('Permiso guardado correctamente.');
                location.reload();
            } else {
                alert('Error al guardar el permiso: ' + data.msj);
            }
        })
        .catch(err => console.error('Error al guardar el permiso:', err));
}


