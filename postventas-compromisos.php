<div class="modal fade" id="modal-compromisos" tabindex="-1" role="dialog" aria-hidden="true">
	 <div class="modal-dialog modal-md">
		 <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Compromiso</h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body"> 
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12"> 
						<div class="form-group label-floating">
							<label class="control-label">Compromiso</label>
							<input type="hidden" name="idcomentario" id="idcomentario">
							<textarea textarea name="compromiso_editar" id="compromiso_editar" rows="7" class="form-control" disabled="true"></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group label-floating">
							<label class="control-label">Usuario</label>
							<select class="form-control" name="cusuario_editar" id="cusuario_editar"></select>
						</div>
					</div>	
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group label-floating">
							<label class="control-label">Fecha</label>
							<input type="text" name="cfecha_editar" id="cfecha_editar" class="form-control" disabled="true">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group label-floating">
							<label class="control-label">Estado</label>
							<select class="form-control" name="cestado_editar" id="cestado_editar">
								 <option value="Nuevo">Nuevo</option>
								 <option value="Terminado">Terminado</option>
								 <span class="material-input"></span>
							</select>
						</div>
					</div>																
					<div class="col-xs-12 col-sm-12 col-md-12 inpresol">
						<div class="form-group label-floating">
							<label class="control-label"> Resolución </label>
							<textarea rows="5" id="cresolucion_editar" name="cresolucion_editar" class="form-control"></textarea>
						</div>
					</div>
				</div>  		
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary btn-xs m-2" id="guardarcompromiso">Guardar</button>
				<button type="button" class="btn btn-danger btn-xs m-2" id="cancelarcompromiso" data-dismiss="modal">Cancelar</button>
			</div>  
		 </div>
	 </div>
</div>