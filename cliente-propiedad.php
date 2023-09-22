<div class="modal fade" id="modal_propiedad">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Crear propiedad</h5>
				<button type="button" class="close" data-dismiss="modal" >&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="col-xs-4 col-sm-4">
						<div class="form-group">
							<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
							<input type="text" class="form-control text" name="nombrep" id="nombrep" autocomplete="off">
						</div>
					</div>
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Provincia <!--span class="text-red">*</span--></label>
						<select class="form-control" name="id_provinciap" id="id_provinciap" style="width:100%"></select>
					</div>
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Distrito <!--span class="text-red">*</span--></label>
						<select class="form-control" name="id_distritop" id="id_distritop" style="width:100%"></select>
					</div>
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Corregimiento <!--span class="text-red">*</span--></label>
						<select class="form-control" name="id_corregimientop" id="id_corregimientop" style="width:100%"></select>
					</div>
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-12 col-sm-12">
						<label class="control-label" for="direccion" >Dirección <span class="text-red">*</span></label>
						<input type="text" class="form-control text" name="direccionp" id="direccionp" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary btn-xs" id="guardar_propiedad"><i class="fas fa-check-circle mr-2" aria-hidden="true"></i>Guardar</button>
			</div>
		</div>
	</div>
</div>