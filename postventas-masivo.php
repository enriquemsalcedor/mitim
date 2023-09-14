<div id="dialog-form-incidentes-masivo" class="swal" style="display: none; width: 650px; padding: 20px; background: rgb(255, 255, 255); min-height: 171px; z-index:900" tabindex="-1">
	<form id="form_incidentes_mas" method="POST" autocomplete="off">
		<div class="row" style="height: 500px; overflow: auto;">									
			<div class="col-xs-12 content-incidente">
				<div class="form-group label-floating">
					<label class="control-label" style="font-weight: bold">Incidentes</label>
					<input type="text" id="incidentemas" name="incidentemas" disabled="disabled" class="form-control" style="font-size: 18px;font-weight: bold;color: #000000;">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3" style="display:none;">
				<div class="form-group label-floating select2">
					<label class="control-label">Empresas</label>
					<select name="idempresasmas" id="idempresasmas" class="form-control"></select>
				</div>
			</div> 
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Clientes</label>
					<select name="idclientesmas" id="idclientesmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Proyectos</label>
					<select name="idproyectosmas" id="idproyectosmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Categoría</label>
					<select name="idcategoriasmas" id="idcategoriasmas" class="form-control"></select>
				</div>
			</div>									
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Subcategoría</label>											
					<select name="idsubcategoriasmas" id="idsubcategoriasmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Prioridad</label>											
					<select name="idprioridadesmas" id="idprioridadesmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Estado</label>
					<select name="idestadosmas" id="idestadosmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Departamentos / Grupos</label>
					<select name="iddepartamentosmas" id="iddepartamentosmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Asignado a</label>
					<select name="asignadoamas" id="asignadoamas" class="form-control"></select>
				</div>
			</div> 
			<div class="col-xs-12 col-sm-4">
				<div class="form-group label-floating select2">
					<label class="control-label">Ubicación</label>
					<select name="idambientesmas" id="idambientesmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4" style="display:none;">
				<div class="form-group label-floating select2">
					<label class="control-label">Activo</label>
					<select name="idactivosmas" id="idactivosmas" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4" style="display:none;">
				<div class="form-group label-floating">
					<label class="control-label">Marca</label>
					<input type="text" name="idmarcasmas" id="idmarcasmas" disabled class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4" style="display:none;">
				<div class="form-group label-floating">
					<label class="control-label">Modelo</label>
					<input type="text" id="idmodelosmas" name="idmodelosmas" disabled class="form-control">
				</div>
			</div>							
			
		</div>
		<div class="content">
			<a href="#" id="btnguardarincidentemas" class="button regularbold button-green color-white" onclick="guardarFormIncidenteMasivo();"> Grabar </a>
			<a href="#" id="btncancelarincidentemas" class="button regularbold facebook-bg color-white" onclick="cerrarDialogIncidenteMasivo();"> Cancelar </a>
		</div>
	</form>
</div>