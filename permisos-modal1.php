<div class="modal fade" id="modalpermisos" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<!-- Modal Header -->
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Permiso</h4> 
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
					<div class="col-md-12 col-xs-12 col-sm-12">	
						<div class="form-group label-floating is-empty">
							<label class="control-label" for="tipopermiso">Tipo de permiso <span class="ast_red">*</span></label> 
								<select class="form-control" name="tipopermiso" id="tipopermiso"autocomplete="off" style="width:93%">
									<option value="nivel">Nivel</option>
									<option value="excepcion">Excepción</option>
								</select>
								<span class="material-input"></span>
						</div>
					</div>
					<div class="div_nivel" style="display:none;">
						<div class="col-md-12 col-xs-12 col-sm-12">	
							<div class="form-group label-floating is-empty">
								<label class="control-label" for="nivelpermiso">Nivel <span class="ast_red">*</span></label> 
								<select class="form-control" name="nivelpermiso" id="nivelpermiso"autocomplete="off" style="width:93%"></select>
								<span class="material-input"></span>
							</div>
						</div>
						<div class="col-md-12 col-xs-12 col-sm-12">	
							<div class="form-group label-floating is-empty">
								<label class="control-label" for="notificacionnivel">Notificación <span class="ast_red">*</span></label> 
								<select class="form-control" name="notificacionnivel" id="notificacionnivel"autocomplete="off" style="width:93%"></select>
								<span class="material-input"></span>
							</div>
						</div>
						<div class="col-md-12 col-xs-12 col-sm-6">								
							<div class="form-group label-floating is-empty">
								<input type="hidden" name="idpermiso" id="idpermiso" >
								<label class="control-label" for="nombrepermiso">Nombre <span class="ast_red">*</span></label>
								<input type="text" class="form-control" name="nombrepermiso" id="nombrepermiso" autocomplete="off">
							</div>
						</div>																	
					</div>
					<div class="div_excepcion" style="display:none;">
						<div class="col-md-12 col-xs-12 col-sm-12">	
							<div class="form-group label-floating is-empty">
								<label class="control-label" for="usuariopermiso">usuario <span class="ast_red">*</span></label> 
								<select class="form-control" name="usuariopermiso" id="usuariopermiso"autocomplete="off" style="width:93%"></select>
								<span class="material-input"></span>
							</div>
						</div>
						<div class="col-md-12 col-xs-12 col-sm-12">	
							<div class="form-group label-floating is-empty">
								<label class="control-label" for="notificacionpermiso">Notificación <span class="ast_red">*</span></label> 
								<select class="form-control" name="notificacionpermiso" id="notificacionpermiso"autocomplete="off" style="width:93%"></select>
								<span class="material-input"></span>
							</div>
						</div>
					</div>
				</div>
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="button" class="button regularbold button-green color-white" id="guardar-permiso">Guardar</button>
					<button type="button" class="button regularbold facebook-bg color-white" id="cancelar-guardar-permiso" data-dismiss="modal">Cancelar</button>
				</div> 
			</div> 
		</div>	
	</div> 	
</div>