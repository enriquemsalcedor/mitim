<div class="modal fade" id="modalfusion" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h5 class="modal-title">Fusionar Correctivos</h5> 
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="clearfix"></div>
				<div class="card-content m-4">
					<form>
						<div class="form-row">
							<div class="col-xs-12">
								<p class="mb-0 font-w600 text-success">Correctivo que prevalecerá</p>
								<div class="incidente-fusion fs-18"></div><br>
							</div>
							<div class="col-xs-12 col-md-12">
								<div class="form-group label-floating selectsr">
									<label class="control-label font-w600 text-success">Correctivos a Fusionar</label>
									<select multiple name="incidenteafusionar" id="incidenteafusionar" class="form-control"></select>
								</div>
							</div>
						</div> 
					</form>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<button type="button" class="btn btn-danger btn-xs" style="float:right" onclick="cerrarDialogFusion();">
							<i class="fas mr-2"></i>Cancelar
						</button>
						<button type="button" class="btn btn-primary btn-xs mr-2" style="float:right" onclick="fusionarIncidentes();">
							<i class="fas fa-check-circle mr-2"></i>Guardar
						</button>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>