<?php
require_once 'modelos/DAO.php';

class M_Opciones {
    private $DAO;

    public function __construct() {
        $this->DAO = new DAO(); // Inicializamos la conexión a la base de datos
    }

    // Obtener todas las opciones del menú
    public function obtenerOpciones() {
        $query = "SELECT * FROM menu_opciones ORDER BY nivel, id_padre, posicion";
        $resultados = $this->DAO->consultaMultiple($query);
    
        // Registrar los resultados en el log
        error_log("Opciones extraídas de la base de datos: " . print_r($resultados, true));
    
        return $resultados;
    }
      

    // Guardar una opción (insertar o actualizar)
    public function guardarOpcion($datos) {
        if (isset($datos['id']) && !empty($datos['id'])) {
            // Actualizar opción existente
            $query = "UPDATE menu_opciones SET nombre = ?, url = ?, id_padre = ?, posicion = ?, nivel = ? WHERE id = ?";
            $parametros = [
                $datos['nombre'],
                $datos['url'],
                $datos['id_padre'] ?? null,
                $datos['posicion'],
                $datos['nivel'],
                $datos['id']
            ];
            return $this->DAO->ejecutarConsulta($query, $parametros); // Actualizar registro
        } else {
            // Insertar nueva opción
            $query = "INSERT INTO menu_opciones (nombre, url, id_padre, posicion, nivel) VALUES (?, ?, ?, ?, ?)";
            $parametros = [
                $datos['nombre'],
                $datos['url'],
                $datos['id_padre'] ?? null,
                $datos['posicion'],
                $datos['nivel']
            ];
            return $this->DAO->insertar($query, $parametros); // Insertar y retornar el ID generado
        }
    }

    // Eliminar una opción por su ID
    public function eliminarOpcion($id) {
        $query = "DELETE FROM menu_opciones WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->ejecutarConsulta($query, $parametros); // Eliminar registro
    }

    // Obtener una opción específica por su ID
    public function obtenerOpcionPorId($id) {
        $query = "SELECT * FROM menu_opciones WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->consultaFila($query, $parametros); // Obtener una fila específica
    }
}
