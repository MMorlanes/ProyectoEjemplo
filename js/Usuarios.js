function guardarUsuario() {
    console.log('guardando');

    let opciones = { method: "GET" };
    let parametros = "controlador=Usuarios&metodo=guardarUsuario";
    parametros += '&' + new URLSearchParams(
        new FormData(document.getElementById('formularioAñadirUsuario'))
    ).toString();
    
    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => {
            if (res.ok) {
                return res.json();
            }
            throw new Error(res.status);
        })
        .then(resultado => {
            if (resultado.correcto === 'S') {
                document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
            } else {
                document.getElementById('msjError').innerHTML = resultado.msj;
            }
        })
        .catch(err => {
            console.error("Error al guardar el usuario", err.message);
        });
}

function toggleUserStatus(userId, currentStatus) {
    console.log(`Cambiando estado para usuario ID: ${userId}, Estado actual: ${currentStatus}`);

    const params = new URLSearchParams({
        controlador: "Usuarios",
        metodo: "cambiarEstado",
        id: userId
    });

    fetch("C_Frontal.php?" + params.toString(), { method: "GET" })
        .then(res => {
            if (!res.ok) {
                throw new Error(`Error en la solicitud: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.correcto === "S") {
                console.log(`Estado actualizado en el backend: ${data.estado}`);

                // Seleccionar elementos del DOM
                const estadoElement = document.getElementById(`estado-${userId}`);
                const filaElement = document.getElementById(`fila-${userId}`);
                const botonElement = document.getElementById(`btn-toggle-${userId}`);

                if (!estadoElement || !filaElement || !botonElement) {
                    console.error("No se pudieron encontrar uno o más elementos del DOM:", { estadoElement, filaElement, botonElement });
                    return;
                }

                // Actualizar el estado en el DOM
                const updatedStatus = data.estado === "S" ? "Activo" : "Inactivo";
                estadoElement.innerText = updatedStatus;

                // Cambiar texto del botón
                botonElement.innerText = updatedStatus === "Activo" ? "Desactivar" : "Activar";

                // Cambiar las clases CSS para el color
                filaElement.classList.remove("usuario-activo", "usuario-inactivo");
                filaElement.classList.add(data.estado === "S" ? "usuario-activo" : "usuario-inactivo");

                console.log(`Fila ${userId} actualizada: Estado: ${updatedStatus}, Clase aplicada: ${filaElement.className}`);
            } else {
                console.error("Error desde el servidor:", data.msj);
                alert("Error al cambiar el estado. Intente de nuevo.");
            }
        })
        .catch(err => {
            console.error("Error en la solicitud AJAX:", err);
            alert("Error al intentar cambiar el estado del usuario.");
        });
}




