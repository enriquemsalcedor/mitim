<!--<div id="dialog-form-incidentes-masivo" class="swal" style="display: none; width: 650px; padding: 20px; background: rgb(255, 255, 255); min-height: 171px; z-index:900" tabindex="-1">-->
<div class="modal fade" id="modalversalidas" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Salidas</h4>
				</div> 
				<div class="content">
					<table id="tablasalidas" class="mdl-data-table display nowrap table-striped" width="100%">
						<thead>
							<tr>  
								<th></th>				<!--0-->
								<th id="corden"># Orden</th>	<!--1-->
								<th id="cfecha">Fecha </th>		<!--2-->
								<th id="cusuario">Usuario</th>	<!--3--> 
							</tr>
						</thead>									
					</table>
				</div>
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="button" class="swal-cancel button regularbold facebook-bg color-white" id="btncerrarreportes" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>