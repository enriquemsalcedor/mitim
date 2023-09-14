<!--<div id="dialog-form-incidentes-masivo" class="swal" style="display: none; width: 650px; padding: 20px; background: rgb(255, 255, 255); min-height: 171px; z-index:900" tabindex="-1">-->
<div class="modal fade" id="modalreportes" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Reportes</h4>
				</div>
				<div class="card-content">	
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group label-floating select2">
							<h4>Elija el tipo de reporte a generar</h4>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 text-center">
						<button type="button" class="swal-confirm button regularbold button-green color-white" id="btnexcel" onclick="exportarInc(1);"><i class="fa fa-file-excel-o fa-3"></i> RESUMEN</button></br>
						<!--<button type="button" class="swal-confirm button regularbold button-green color-white" id="btnexcelcomentarios" onclick="exportarInc(2);"><i class="fa fa-file-excel-o fa-3"></i> COMENTARIOS</button>-->
					</div>					
				</div>
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="button" class="swal-cancel button regularbold facebook-bg color-white" id="btncerrarreportes" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>