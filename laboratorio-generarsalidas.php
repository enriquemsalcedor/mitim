<div class="modal fade" id="modalsalidas" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon bg-success-light">
					<h5 class="modal-title">Generar Salidas</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button> 
				</div>
				<div class="clearfix"></div>
				<div class="card-content m-4">
					<form id="form_incidentes_salidas" method="POST" autocomplete="off">
						<div class="col-xs-12 content-incidente">
							<div class="form-group label-floating">
								<label class="control-label" style="font-weight: bold">Generar Salidas</label>
								<input type="text" id="incidentesalidas" name="incidentesalidas" disabled="disabled" class="form-control" style="font-size: 18px;font-weight: bold;color: #000000;">
							</div>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<button type="button" id="btnguardarincidentesalidas" class="btn btn-primary btn-xs mr-2" onclick="guardarFormIncidenteSalidas();"><i class="fas fa-check-circle mr-2"></i>Guardar </button>
							<button type="button" id="btncancelarincidentesalidas" class="btn btn-danger btn-xs" onclick="cerrarDialogSalidas();"><i class="fas mr-2"></i>Cancelar </button>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>