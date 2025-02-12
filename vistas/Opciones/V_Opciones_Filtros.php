<h2>Gestión de Opciones del Menú</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarOpciones" name="formularioBuscarOpciones">    
        <div class="row">
            <div class="form-group col-md-6">
                <label for="ftexto">Texto a buscar:</label>
                <input type="text" id="ftexto" name="ftexto" class="form-control" placeholder="Nombre de la opción" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" 
                        onclick="buscar('Opciones', 'getVistaListadoOpciones', 'formularioBuscarOpciones', 'capaResultadoBusqueda')">Buscar</button>
                <button type="button" class="btn btn-secondary"
                        onclick="obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', 'capaEditarCrear', '')">Nueva Opción</button>
            </div>
        </div>
    </form>
    <div class="container-fluid" id="capaResultadoBusqueda"></div>
    <div class="container-fluid" id="capaEditarCrear"></div>
</div>
