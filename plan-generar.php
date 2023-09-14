<div class="modal fade" id="modalplangenerar" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h5 class="modal-title titulo-modal">Generar Preventivos</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="card-content" style="margin-top:-14px"> 
					<form id="form_generar" method="POST" autocomplete="off"> 
						<div class="form-group col-md-12 mt-4"> 
							<label class="control-label">Desde <span class="text-red">*</span></label>
							<input type="text" name="filtro-desde" id="filtro-desde" class="form-control text"> 
						</div>
						<div class="form-group col-md-12"> 
							<label class="control-label">Hasta <span class="text-red">*</span></label>
							<input type="text" name="filtro-hasta" id="filtro-hasta" class="form-control text"> 
						</div>
						<div class="form-group col-md-12"> 
							<label class="control-label">Cliente <span class="text-red">*</span></label>
							<select name="idclientes" id="idclientes" class="form-control text"></select> 
						</div> 
						<div class="form-group col-md-12"> 
							<label class="control-label">Proyecto <span class="text-red">*</span></label>
							<select name="idproyectos" id="idproyectos" class="form-control text"></select> 
						</div>  
						<br/> 
						<div class="modal-footer">	   
							<button type="button" class="btn btn-primary btn-xs" onclick="generarOrdenes();"><i class="fas fa-check-circle mr-2" aria-hidden="true"></i>Generar</button>
							<button type="button" class="btn btn-primary btn-xs" style="float:right" data-dismiss="modal" ><i class="fas fa-ban mr-2"></i>Cerrar</button> 
						</div>
					</form>	
				</div>
			</div>
		</div>	
	</div>
</div>