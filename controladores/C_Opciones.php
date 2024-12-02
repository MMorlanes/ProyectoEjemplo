<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Opciones.php';
require_once 'vistas/Vista.php';

class C_Opciones extends Controlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new M_Opciones();
    }

    public function index() {
        // Renderiza la vista principal
        $opcionesMenu = $this->modelo->obtenerOpciones();
        Vista::render('vistas/Opciones/V_Opciones.php', array('opcionesMenu' => $opcionesMenu));
    }

    public function guardar() {
        $datos = $_POST;
        $this->modelo->guardarOpcion($datos);
        echo json_encode(['success' => true]);
    }

    public function eliminar($id) {
        $this->modelo->eliminarOpcion($id);
        echo json_encode(['success' => true]);
    }

    public function mostrarOpciones() {
        // Este método solo renderiza la vista
        $opcionesMenu = $this->modelo->obtenerOpciones();
        Vista::render('vistas/Opciones/V_Opciones.php', array('opcionesMenu' => $opcionesMenu));
    }

    public function obtenerOpcionesJSON() {
        // Devuelve los datos en formato JSON
        $opcionesMenu = $this->modelo->obtenerOpciones();
        echo json_encode($opcionesMenu);
    }
}
