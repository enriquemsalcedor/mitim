<div class="modal fade" id="modalfiltrosmasivos" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Filtros Masivos</h4> 
				</div>
				<div class="clearfix"></div>
				<div class="card-content">
					<input type="hidden" name="calendarhidendesde" id="calendarhidendesde">
					<input type="hidden" name="calendarhidenhasta" id="calendarhidenhasta">
					<form id="form_filtrosmasivos" method="POST" autocomplete="off">
						<input type="text" id="frelleno" name="frelleno" class="form-control" style="width: 0px;height: 0px;margin: 0;padding: 0;">
						<div class="col-xs-12 col-sm-6">
							<label class="control-label">
								<input type="checkbox" class="cheki" id="tipoinc" name="tipoinc" value="1" checked> Incidente
							</label>
						</div>
						<div class="col-xs-12 col-sm-6">
							<label class="control-label">
								<input type="checkbox" class="cheki" id="tipoprev" name="tipoprev" value="1"> Preventivo
							</label>
						</div>
						<br/>
						<br/>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Desde</label> 
								<input type="text" name="desdef" id="desdef" class="form-control" placeholder="yyyy-mm-dd">
								<i class="fa fa-calendar iconcalfdesde" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Hasta</label> 
								<input type="text" id="hastaf" name="hastaf" class="form-control" placeholder="yyyy-mm-dd">
								<i class="fa fa-calendar iconcalfhasta" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6" style="display:none;">
							<div class="form-group label-floating">
								<label class="control-label">Empresas</label>
								<select name="idempresasf" id="idempresasf" class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Clientes</label>
								<select name="idclientesf" id="idclientesf" class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Proyectos</label>
								<select name="idproyectosf" id="idproyectosf" class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating ">
								<label class="control-label">Categor&iacute;a</label>
								<select name="categoriaf" id="categoriaf" multiple class="form-control"></select>
							</div>
						</div>									
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating ">
								<label class="control-label">Subcategor&iacute;a</label>
								<select name="subcategoriaf" id="subcategoriaf" multiple class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating select2">
								<label class="control-label">Ubicación</label>
								<select name="idambientesf" id="idambientesf" multiple class="form-control"></select>
							</div>
						</div>
						<!--
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating ">
								<label class="control-label">Modalidad</label>
								<select name="modalidadf" id="modalidadf" multiple class="form-control"></select>
							</div>
						</div>									
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating ">
								<label class="control-label">Marca</label>
								<select name="marcaf" id="marcaf" multiple class="form-control"></select>
							</div>
						</div>
						-->
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Prioridad</label>											
								<select name="prioridadf" id="prioridadf" multiple class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Estado</label>
								<select name="estadof" id="estadof" multiple class="form-control"></select>
							</div>
						</div>
						<?php if($nivel != 7): ?>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating">
								<label class="control-label">Departamentos / Grupos</label>
								<select name="iddepartamentosf" id="iddepartamentosf" class="form-control"></select>
							</div>
						</div>
						<?php endif; ?>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating select2">
								<label class="control-label">Asignado a</label>
								<select name="asignadoaf" id="asignadoaf" multiple class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group label-floating select2">
								<label class="control-label">Solicitante</label>
								<select name="solicitantef" id="solicitantef" multiple class="form-control"></select>
							</div>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<div class="float-right">
						<a href="#" id="btnguardarfiltrosmasivos" class="button regularbold button-green color-white" onclick="filtrosMasivos();"> Filtrar </a>
						<a href="#" id="btnlimpiarfiltrosmasivos" class="button regularbold facebook-bg color-white" onclick="limpiarFiltrosMasivos();"> Limpiar </a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>