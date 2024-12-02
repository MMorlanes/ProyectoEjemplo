document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-opcion');
    const closeBtn = modal.querySelector('.close');
    const form = document.getElementById('form-opcion');

    // Abrir modal al crear nueva opción
    document.getElementById('nueva-opcion').addEventListener('click', () => {
        form.reset(); // Limpiar formulario
        document.getElementById('opcion-id').value = ''; // Asegurarse de que no tenga un ID
        modal.style.display = 'block';
    });

    // Abrir modal al editar una opción existente
    document.getElementById('opciones-listado').addEventListener('click', (e) => {
        if (e.target.classList.contains('edit')) {
            const id = e.target.dataset.id;

            // Hacer una solicitud AJAX para obtener los datos de la opción
            fetch(`index.php?controlador=opciones&accion=obtener&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    // Llenar el formulario con los datos recibidos
                    document.getElementById('opcion-id').value = data.id;
                    document.getElementById('opcion-nombre').value = data.nombre;
                    document.getElementById('opcion-url').value = data.url;
                    document.getElementById('opcion-padre').value = data.id_padre;
                    document.getElementById('opcion-posicion').value = data.posicion;
                    document.getElementById('opcion-nivel').value = data.nivel;

                    modal.style.display = 'block'; // Mostrar modal
                });
        }

        // Eliminar opción
        if (e.target.classList.contains('delete')) {
            const id = e.target.dataset.id;
            if (confirm('¿Estás seguro de que deseas eliminar esta opción?')) {
                fetch('index.php?controlador=opciones&accion=eliminar', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Opción eliminada con éxito');
                        location.reload();
                    } else {
                        alert('Error al eliminar la opción');
                    }
                });
            }
        }
    });

    // Cerrar modal
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Manejar el envío del formulario
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Evitar recargar la página

        const formData = new FormData(form);

        // Hacer una solicitud AJAX para guardar los datos
        fetch('index.php?controlador=opciones&accion=guardar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Opción guardada con éxito');
                location.reload(); // Recargar la página para actualizar la lista
            } else {
                alert('Error al guardar la opción');
            }
        });
    });
});
