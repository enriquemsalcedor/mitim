<div class="modal fade" id="modal_cliente_creacionrapida">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Crear nuevo cliente</h5>
				<button type="button" class="close" data-dismiss="modal" >&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="col-xs-6 col-sm-6">
						<div class="form-group">
							<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
							<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6">
						<div class="form-group">
							<label class="control-label" for="apellidos" >Apellidos </label>
							<input type="text" class="form-control text" name="apellidos" id="apellidos" autocomplete="off">
						</div>
					</div> 
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="direccion" >Dirección </label>
						<input type="text" class="form-control text" name="direccion" id="direccion" autocomplete="off">
					</div>
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="telefono" >Teléfono <span class="text-red">*</span></label>
						<input type="text" class="form-control text" name="telefono" id="telefono" autocomplete="off">
					</div>
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Provincia </label>
						<select class="form-control" name="id_provincia" id="id_provincia" style="width:100%"></select>
					</div>
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Distrito </label>
						<select class="form-control" name="id_distrito" id="id_distrito" style="width:100%"></select>
					</div>
					<div class="col-xs-4 col-sm-4">
						<label class="control-label" for="provincia" >Corregimiento </label>
						<select class="form-control" name="id_corregimiento" id="id_corregimiento" style="width:100%"></select>
					</div>
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="correo" >Correo </label>
						<input type="text" class="form-control text" name="correo" id="correo" autocomplete="off">
					</div>
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="movil" >Movil </label>
						<input type="text" class="form-control text" name="movil" id="movil" autocomplete="off">
					</div>
				</div>
				<div class="form-row mt-2">
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="provincia" >¿Cómo supo de nosotros? </label>
						<select class="form-control" name="id_referido" id="id_referido" style="width:100%"></select>
					</div>
					<div class="col-xs-6 col-sm-6">
						<label class="control-label" for="provincia" >Especifique </label>
						<select class="form-control" name="id_subreferido" id="id_subreferido" style="width:100%"></select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary btn-xs" id="guardar_cliente"><i class="fas fa-check-circle mr-2" aria-hidden="true"></i>Guardar</button>
			</div>
		</div>
	</div>
</div>