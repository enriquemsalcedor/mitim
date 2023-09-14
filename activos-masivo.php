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
					<form id="form_activos_mas">
						<div class="form-row">
							<div class="col-xs-12 col-sm-12 content-incidente">
								<div class="form-group label-floating">
									<label class="control-label" style="font-weight: bold;">Activos</label>
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
									<label class="control-label">Marcas</label>
									<select name="idmarcasmas" id="idmarcasmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Modelos</label>
									<select name="idmodelosmas" id="idmodelosmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Responsables</label>
									<select name="idresponsablesmas" id="idresponsablesmas" class="form-control"></select>
								</div>
							</div> 
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Ubicación </label>
									<select name="idubicacionesmas" id="idubicacionesmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Área </label>
									<select name="idareasmas" id="idareasmas" class="form-control"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating select2">
									<label class="control-label">Estados</label>
									<select name="estadomas" id="estadomas" class="form-control">
										<option value="ACTIVO">Activo</option>
										<option value="INACTIVO">Inactivo</option>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Tipos</label>
									<select name="idtiposmas" id="idtiposmas" class="form-control text"></select>
								</div>
							</div>
							<div class="col-xs-12 col-sm-4">
								<div class="form-group label-floating">
									<label class="control-label">Subtipos</label> 
									<select name="idsubtiposmas" id="idsubtiposmas" class="form-control text"></select>
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