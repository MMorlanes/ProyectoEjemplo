<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Usuarios.php';
require_once 'vistas/Vista.php';

class C_Usuarios extends Controlador{
    private $modelo;

    public function __construct(){
        parent::__construct(); // Ejecutar constructor padre
        $this->modelo = new M_Usuarios();
    }

    public function validarUsuario($datos=array()){
        $id_Usuario=$this->modelo->login($datos);
        return $id_Usuario;
    }

    public function getVistaFiltros($datos=array()){
        Vista::render('vistas/Usuarios/V_Usuarios_Filtros.php');
    }
    public function getVistaNuevoEditar($datos=array()){
        if(!isset($datos['id']) || $datos['id']==''){
            //nuevo
            Vista::render('vistas/Usuarios/V_Usuarios_NuevoEditar.php');
        }else{
            //editando
            $filtros['id_Usuario']=$datos['id'];
            $usuarios=$this->modelo->buscarUsuarios($filtros);
            Vista::render('vistas/Usuarios/V_Usuarios_NuevoEditar.php', 
                                    array('usuario'=>$usuarios[0]));
        }
  
    }

    public function getVistaListadoUsuarios($filtros=array()){
        // var_dump($filtros);
        $usuarios=$this->modelo->buscarUsuarios($filtros);
        Vista::render('vistas/Usuarios/V_Usuarios_Listado.php',
                        array('usuarios'=>$usuarios));
    }
    

    function guardarUsuario($datos=array()){
        $respuesta['correcto']='S';
        $respuesta['msj']='Usuario creado correctamente.';

        $id=$this->modelo->insertarUsuario($datos);
        if($id>0){
            //nada, está ok    
        }else{
            $respuesta['correcto']='N';
            $respuesta['msj']='Error al crear el usuario.';
        }
        echo json_encode($respuesta);
    }

    public function cambiarEstado() {
        $idUsuario = $_GET['id'] ?? null;

        if (!$idUsuario) {
            echo json_encode(['correcto' => 'N', 'msj' => 'ID de usuario no proporcionado']);
            return;
        }

        // Obtener el usuario actual y cambiar el estado
        $usuario = M_Usuarios::obtenerPorId($idUsuario); // Método que puedes implementar
        if (!$usuario) {
            echo json_encode(['correcto' => 'N', 'msj' => 'Usuario no encontrado']);
            return;
        }

        $nuevoEstado = ($usuario['activo'] === 'S') ? 'N' : 'S';

        $resultado = M_Usuarios::actualizarEstado($idUsuario, $nuevoEstado);

        if ($resultado) {
            echo json_encode(['correcto' => 'S', 'estado' => $nuevoEstado]);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'Error al cambiar el estado']);
        }
    }
    

} //Fin clase Usuarios



?>