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

    // Obtener opciones con permisos
    public function obtenerOpcionesConPermisos() {
        $query = "SELECT mo.*, p.id as permiso_id, p.permiso, p.codigo_Permiso
                  FROM menu_opciones mo
                  LEFT JOIN permisos p ON mo.id = p.id_Menu
                  ORDER BY mo.nivel, mo.id_padre, mo.posicion";
        $resultados = $this->DAO->consultaMultiple($query);

        $opciones = [];
        foreach ($resultados as $row) {
            $id = $row['id'];
            if (!isset($opciones[$id])) {
                $opciones[$id] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'url' => $row['url'],
                    'id_padre' => $row['id_padre'],
                    'posicion' => $row['posicion'],
                    'nivel' => $row['nivel'],
                    'permisos' => []
                ];
            }

            if (!empty($row['permiso_id'])) {
                $opciones[$id]['permisos'][] = [
                    'id' => $row['permiso_id'],
                    'nombre' => $row['permiso'],
                    'codigo' => $row['codigo_Permiso']
                ];
            }
        }

        return array_values($opciones);
    }

    // Guardar una opción (actualizar/insertar)
    public function guardarOpcion($datos) {
        $nombre = $datos['nombre'] ?? null;
        $url = $datos['url'] ?? null;
        $id_padre = isset($datos['id_padre']) && $datos['id_padre'] !== '' ? $datos['id_padre'] : null;
        $posicion = $datos['posicion'] ?? null;
        $nivel = $datos['nivel'] ?? null;

        if (!$nombre || !$posicion || !$nivel) {
            error_log("Error: Datos incompletos para guardar la opción. Datos recibidos: " . print_r($datos, true));
            return false;
        }

        if (!empty($datos['id'])) {
            // Actualizar una opción existente
            $query = "UPDATE menu_opciones SET nombre = ?, url = ?, id_padre = ?, posicion = ?, nivel = ? WHERE id = ?";
            $parametros = [$nombre, $url, $id_padre, $posicion, $nivel, $datos['id']];
            return $this->DAO->ejecutarConsulta($query, $parametros);
        } else {
            // Incrementar las posiciones de las opciones existentes en la misma ubicación
            $incrementQuery = "UPDATE menu_opciones SET posicion = posicion + 1 WHERE (id_padre IS NULL OR id_padre = ?) AND nivel = ? AND posicion >= ?";
            $incrementParams = [$id_padre, $nivel, $posicion];
            $this->DAO->ejecutarConsulta($incrementQuery, $incrementParams);

            // Insertar nueva opción
            $query = "INSERT INTO menu_opciones (nombre, url, id_padre, posicion, nivel) VALUES (?, ?, ?, ?, ?)";
            $parametros = [$nombre, $url, $id_padre, $posicion, $nivel];
            return $this->DAO->insertar($query, $parametros);
        }
    }

    // Eliminar una opción por su ID
    public function eliminarOpcion($id) {
        $query = "DELETE FROM menu_opciones WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->ejecutarConsulta($query, $parametros); // Eliminar un registro
    }

    // Obtener una opción específica por su ID
    public function obtenerOpcionPorId($id) {
        $query = "SELECT * FROM menu_opciones WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->consultaFila($query, $parametros);
    }

    public function incrementarPosiciones($posicionInicio, $nivel, $idPadre = null) {
        $query = "UPDATE menu_opciones 
                  SET posicion = posicion + 1 
                  WHERE posicion >= ? AND nivel = ? AND (id_padre IS NULL OR id_padre = ?)";

        $parametros = [$posicionInicio, $nivel, $idPadre];
        return $this->DAO->ejecutarConsulta($query, $parametros);
    }
}
