<?php
require_once 'vistas/Vista.php';

if (!function_exists('renderOpcionesTree')) {
    function renderOpcionesTree($opciones, $padre_id = null) {
        $html = '';

        // Filtrar las opciones que corresponden al padre actual
        $hijos = array_filter($opciones, function ($opcion) use ($padre_id) {
            return $opcion['id_padre'] == $padre_id;
        });

        if (empty($hijos)) {
            return '';
        }

        $html .= '<ul class="menu-vertical">';
        foreach ($hijos as $opcion) {
            $html .= '<li>';
            $html .= '<span>' . htmlspecialchars($opcion['nombre']) . '</span>';
            $html .= ' <button class="edit" data-id="' . $opcion['id'] . '">✏️</button>';
            $html .= ' <button class="delete" data-id="' . $opcion['id'] . '">❌</button>';
            // Llamada recursiva para renderizar hijos
            $html .= renderOpcionesTree($opciones, $opcion['id']);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}
?>

<!-- Resto de tu código HTML -->

<div class="container">
    <!-- Menú Vertical -->
    <div class="sidebar">
        <h2>Opciones del Menú</h2>
        <button id="nueva-opcion" class="btn-crear">➕ Nueva Opción</button>
        <div id="opciones-listado">
            <?php if (!empty($opcionesMenu)): ?>
                <?= renderOpcionesTree($opcionesMenu); ?>
            <?php else: ?>
                <p>No hay opciones disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="content">
        <h1>Gestión de Opciones</h1>
        <p>Selecciona una opción para editarla o elimínala del menú.</p>
    </div>
</div>

<!-- Modal para Crear/Editar Opciones -->
<div id="modal-opcion" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="form-opcion">
            <input type="hidden" name="id" id="opcion-id">
            
            <label for="opcion-nombre">Nombre:</label>
            <input type="text" name="nombre" id="opcion-nombre" required>

            <label for="opcion-url">URL:</label>
            <input type="text" name="url" id="opcion-url">

            <label for="opcion-padre">Padre:</label>
            <select name="id_padre" id="opcion-padre">
                <option value="">Ninguno</option>
                <?php if (!empty($opcionesMenu)): ?>
                    <?php foreach ($opcionesMenu as $opcion): ?>
                        <option value="<?= $opcion['id'] ?>"><?= htmlspecialchars($opcion['nombre']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label for="opcion-posicion">Posición:</label>
            <input type="number" name="posicion" id="opcion-posicion" required>

            <label for="opcion-nivel">Nivel:</label>
            <input type="number" name="nivel" id="opcion-nivel" required>

            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<!-- Estilos -->
<style>
.container {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 20%;
    background-color: #f8f9fa;
    padding: 20px;
    border-right: 1px solid #ddd;
}

.menu-vertical {
    list-style: none;
    padding: 0;
}

.menu-vertical li {
    margin: 10px 0;
    display: flex;
    align-items: center;
}

.menu-vertical span {
    flex: 1;
}

.menu-vertical button {
    margin-left: 10px;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.menu-vertical .edit {
    background-color: #ffc107;
    color: #fff;
}

.menu-vertical .delete {
    background-color: #dc3545;
    color: #fff;
}

.btn-crear {
    width: 100%;
    padding: 10px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 5px;
    margin-bottom: 20px;
    cursor: pointer;
}

.content {
    flex: 1;
    padding: 20px;
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding: 20px;
    display: none;
}

.modal-content {
    padding: 20px;
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: red;
    font-size: 18px;
}
</style>
