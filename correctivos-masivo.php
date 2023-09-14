<div class="modal fade" id="modalmasivos" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h5 class="modal-title">Editar Masivo</h5> 					
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="clearfix"></div>
				<div class="card-content m-4">
					<form id="form_incidentes_mas">
						<div class="form-row">
							<div class="col-xs-12 col-sm-12 content-incidente">
								<div class="form-group label-floating">
									<label class="control-label text-success" style="font-weight: bold;" >Correctivos</label>
									<input type="hidden" id="incidentemas" name="incidentemas" disabled="disabled" class="form-control text" style="font-size: 18px;">
									<textarea rows="3" cols="50" id="incidentesview" name="incidentesview" disabled="disabled" class="form-control text" style="font-size: 18px;"></textarea>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-3" style="display:none;">
								<div class="form-group label-floating select2">
									<label class="control-label">Empresas <span class="text-red">*</span></label>
									<select name="idempresasmas" id="idempresasmas" class="form-control"></select>
								</div>
							</div>								
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Clientes <!--<span class="text-red">*</span>--></label>
									<select name="idclientesmas" id="idclientesmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Proyectos <!--<span class="text-red">*</span>--></label>
									<select name="idproyectosmas" id="idproyectosmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Categoría <!--<span class="text-red">*</span>--></label>
									<select name="categoriamas" id="categoriamas" class="form-control"></select>
								</div>
							</div>									
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Subcategoría</label>											
									<select name="subcategoriamas" id="subcategoriamas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Prioridad</label>											
									<select name="prioridadmas" id="prioridadmas" class="form-control"></select>
								</div>
							</div>								
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Estado</label>
									<select name="estadomas" id="estadomas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Departamentos / Grupos <!--<span class="text-red">*</span>--></label>
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
									<label class="control-label">Ubicación <!--<span class="text-red">*</span>--></label>
									<select name="unidadejecutoramas" id="unidadejecutoramas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Serial 1 <!--<span class="text-red">*</span>--></label>
									<select name="seriemas" id="seriemas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Marca</label>
									<input type="text" name="marcamas" id="marcamas" disabled class="form-control text">
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Modelo</label>
									<input type="text" id="modelomas" name="modelomas" disabled class="form-control text">
								</div>
							</div>
							<div class="col-xs-12 col-sm-4 content-fechacierre box-fechahoracierre">
								<div class="form-group label-floating">
									<label class="control-label">Fecha y Hora de Resolución </label>
									<input type="text" id="fecharesolucionmas" name="fecharesolucionmas" class="form-control text inc-edit">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 inpresol">
								<div class="form-group label-floating">
									<label class="control-label"> Resolución </label>
									<textarea rows="3" cols="50" id="resolucionmas" name="resolucionmas" class="form-control inc-edit"></textarea>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="float-right">
								<button type="button" class="btn btn-danger btn-xs" style="float:right" onclick="cerrarDialogIncidenteMasivo();">
									<i class="fas mr-2"></i>Cancelar
								</button>
								<button type="button" class="btn btn-primary btn-xs mr-2" style="float:right" onclick="guardarFormIncidenteMasivo();">
									<i class="fas fa-check-circle mr-2"></i>Guardar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>