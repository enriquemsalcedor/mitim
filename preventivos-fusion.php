<div class="modal fade" id="modalfusion" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					<h5 class="modal-title">Fusionar Correctivos</h5> 					
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="clearfix"></div>
				<div class="card-content" style="margin: 10px;">
					<form>
						<div class="col-xs-12">
							<p style="margin-bottom:0px">Correctivo que prevalecerá</p>
							<div class="incidente-fusion"></div><br>
						</div>
						<div class="col-xs-12">
							<div class="form-group label-floating selectsr selectcr2">
								<label class="control-label">Correctivos a Fusionar</label>
								<select multiple name="incidenteafusionar" id="incidenteafusionar" class="form-control"></select>
							</div>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<button type="button" class="btn btn-warning btn-xs" style="float:right" onclick="cerrarDialogFusion();">
							<i class="fas mr-2"></i>Cancelar
						</button>
						<button type="button" class="btn btn-primary btn-xs" style="float:right" onclick="fusionarIncidentes();">
							<i class="fas fa-check-circle mr-2"></i>Guardar
						</button>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>