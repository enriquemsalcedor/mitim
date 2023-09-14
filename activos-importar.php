<div class="modal fade" id="modalImportar" tabindex="-1" role="dialog" aria-hidden="true">
	 <div class="modal-dialog modal-lg">
		 <div class="modal-content">
			<div class="modal-header bg-success-light">
				<h5 class="modal-title">Importar activos</h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<form id="form_importar_activos" name="form_importar_activos" class="form form-horizontal" >
				<div class="col-xs-12">
					<div class="col-xs-12 col-sm-4 col-md-12 box-clientes">
						<div class="form-group label-floating">
							<label class="control-label">Clientes <span class="text-red">*</span></label>
							<select name="idclientesimp" id="idclientesimp" class="form-control inc-edit"></select>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-12 box-proyectos">
						<div class="form-group label-floating">
							<label class="control-label">Proyectos <span class="text-red">*</span></label>
							<select name="idproyectosimp" id="idproyectosimp" class="form-control inc-edit"></select>
						</div>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-12 col-sm-4 col-md-12">
						Descargue la plantilla, agregue los activos a ser creados y luego suba el archivo.
						<div class="fileinput fileinput-new input-group" data-provides="fileinput">
							<div class="form-control" data-trigger="fileinput">
								<i class="glyphicon glyphicon-file fileinput-exists"></i>
								<span class="fileinput-filename"></span>
							</div>
							<span class="input-group-addon btn btn-default btn-file">
							<span class="fileinput-new btn-xs p-0">Seleccionar archivo</span>
							<span class="fileinput-exists btn-xs p-0">Cambiar</span>
							<input type="file" name="archivo" id="archivo" /></span>
							<a href="#" class="input-group-addon btn btn-default fileinput-exists file-input-delete" data-dismiss="fileinput">Eliminar</a>									
						</div>
					</div> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="subir-archivo" class="btn btn-primary btn-xs m-2">Importar</button>
				<button type="button" id="descargarplantilla" class="btn btn-info btn-xs m-2">Descargar plantilla</button>
				<button type="button" class="btn btn-danger btn-xs m-2" data-dismiss="modal">Cerrar</button>
			</div>
			</form>
			<div id="resultado"></div>
		 </div>
	 </div>
</div>
<!--
<div class="modal fade" id="modalImportar" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Importar Activos</h4> 
				</div>
				<form id="form_importar_activos" name="form_importar_activos" class="form form-horizontal" >
				<div class="card-content">
					<div class="col-xs-12">
						<div class="col-xs-12 col-sm-4 col-md-12 box-clientes">
							<div class="form-group label-floating">
								<label class="control-label">Clientes <span class="ast_red">*</span></label>
								<select name="idclientesimp" id="idclientesimp" class="form-control inc-edit"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-12 box-proyectos">
							<div class="form-group label-floating">
								<label class="control-label">Proyectos <span class="ast_red">*</span></label>
								<select name="idproyectosimp" id="idproyectosimp" class="form-control inc-edit"></select>
							</div>
						</div>
					</div>
					<div class="col-xs-12">
						Descargue la plantilla, agregue los activos a ser creados y luego suba el archivo.
						<div class="fileinput fileinput-new input-group" data-provides="fileinput">
							<div class="form-control" data-trigger="fileinput">
								<i class="glyphicon glyphicon-file fileinput-exists"></i>
								<span class="fileinput-filename"></span>
							</div>
							<span class="input-group-addon btn btn-default btn-file">
							<span class="fileinput-new">Seleccionar archivo</span>
							<span class="fileinput-exists">Cambiar</span>
							<input type="file" name="archivo" id="archivo" /></span>
							<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>									
						</div>
					</div>
				</div> 
				<div class="modal-footer">
					</br>
					<button type="button" id="subir-archivo" class="button regularbold button-green color-white">Importar</button>
					<button type="button" id="descargarplantilla" class="button regularbold twitter-bg color-white">Descargar plantilla</button>
					<button type="button" class="button regularbold facebook-bg color-white" data-dismiss="modal">Cerrar</button>
					</br>
				</div>
				</form>
				<div id="resultado"></div> 
			</div>
		</div>
	</div>
</div>-->