<div class="modal fade" id="modaleditincidentes" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title">Editar Correctivo</h4> 
				</div>
				<div class="clearfix"></div>
				<div class="card-content" style="width: 100%; margin-top:-14px"> 
					<form id="form_incidentes_editar" method="POST" autocomplete="off"> 
						<ul class="nav nav-pills nav-pills-blue menu-tab-sec" role="tablist">
							<li class="nav-item active">
								<a class="nav-link" data-toggle="tab" href="#boxcor" role="tablist" aria-expanded="true">
									Correctivo
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxcom" role="tablist">
									Comentarios
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxest" role="tablist">
									Estados
								</a>
							</li>
							<?php if($_SESSION['nivel'] != 4):	?>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxhis" role="tablist">
									Historial
								</a>
							</li>
							<?php endif; ?>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxfun" role="tablist">
									Fusionados
								</a>
							</li>
						</ul>
						<div class="tab-content tab-space">
							<div class="tab-pane active" id="boxcor">
								<div class="col-xs-12 col-sm-3 col-md-2 content-incidente">
									<div class="form-group label-floating">
										<label class="control-label text_tipo" style="font-weight: bold"></label>
										<input type="hidden" id="tipo_registro" name="tipo_registro">
										<input type="text" id="incidente_editar" name="incidente_editar" disabled="disabled" class="form-control inc-edit" style="font-size: 18px;font-weight: bold;color: #000000;">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-2">
									<div class="form-group label-floating select2">
										<label class="control-label">Solicitante</label>
										<select name="solicitante_editar" id="solicitante_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating select2">
										<label class="control-label">C.C.</label>
										<select name="notificar_editar" id="notificar_editar" class="form-control inc-edit" multiple></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 content-fechacreacion">
									<div class="form-group label-floating">
										<label class="control-label">Fecha Creación</label>
										<input type="text" id="relleno_editar" name="relleno_editar" class="form-control" style="width: 0px;height: 0px;margin: 0;padding: 0;">
										<input type="text" name="fechacreacion_editar" id="fechacreacion_editar" class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 content-horacreacion">
									<div class="form-group label-floating">
										<label class="control-label">Hora Creación</label>
										<input type="text" name="horacreacion_editar" id="horacreacion_editar" class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12" id="fusion_editar" style="display:none;" >
									<div class="form-group label-floating">
										<input type="hidden" id="idincidente_editar" value="">
										<label class="control-label">Fusionado con </label>
										<input type="text" name="fusionado_editar" id="fusionado_editar" class="form-control inc-edit" disabled>
									</div>
								</div>
								<div class="col-xs-12">
									<div class="form-group label-floating">
										<label class="control-label">Titulo <span class="ast_red">*</span></label>
										<input type="text" id="titulo_editar" name="titulo_editar" class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12">
									<div class="form-group label-floating">
										<label class="control-label">Descripción</label>
										<textarea name="descripcion_editar" id="descripcion_editar" rows="7" class="form-control inc-edit"></textarea>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3" style="display:none;">
									<div class="form-group label-floating select2">
										<label class="control-label">Empresa <span class="ast_red">*</span></label>
										<select name="idempresas_editar" id="idempresas_editar" class="form-control inc-edit"></select>
									</div>
								</div>								
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Cliente <span class="ast_red">*</span></label>
										<select name="idclientes_editar" id="idclientes_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Proyecto <span class="ast_red">*</span></label>
										<select name="idproyectos_editar" id="idproyectos_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Categoría <span class="ast_red">*</span></label>
										<select name="categoria_editar" id="categoria_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Subcategoría</label>											
										<select name="subcategoria_editar" id="subcategoria_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Ubicación <span class="ast_red">*</span></label>
										<select name="unidadejecutora_editar" id="unidadejecutora_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Serial 1 <span class="ast_red">*</span></label>
										<select name="serie_editar" id="serie_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Marca</label>
										<input type="text" name="marca_editar" id="marca_editar" disabled class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Modelo</label>
										<input type="text" id="modelo_editar" name="modelo_editar" disabled class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Prioridad</label>											
										<select name="prioridad_editar" id="prioridad_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Estado</label>
										<select name="estado_editar" id="estado_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<?php if($nivel != 7): ?>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Departamentos / Grupos <span class="ast_red">*</span></label>
										<select name="iddepartamentos_editar" id="iddepartamentos_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<?php endif; ?>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating select2">
										<label class="control-label">Asignado a</label>
										<select name="asignadoa_editar" id="asignadoa_editar" class="form-control inc-edit"></select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 content-fechacierre">
									<div class="form-group label-floating">
										<label class="control-label">Fecha y Hora de Resolución <span class="ast_red">*</span></label>
										<input type="text" id="fecharesolucion_editar" name="fecharesolucion_editar" class="form-control inc-edit">
										<input type="hidden" id="fechacierre_editar" name="fechacierre_editar" class="form-control inc-edit">
										<input type="hidden" id="horacierre_editar" name="horacierre_editar" class="form-control inc-edit">
										<input type="hidden" id="creadopor_editar" name="creadopor_editar" class="form-control inc-edit">
									</div>
								</div> 
								<div class="col-xs-12 col-sm-4 col-md-3 inprepse">
									<div class="form-group label-floating">
										<label class="control-label">Reporte de servicio</label>
										<input type="text" id="reporteservicio_editar" name="reporteservicio_editar" class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 inphorast">
									<div class="form-group label-floating">
										<label class="control-label">Horas Trabajadas</label>
										<input type="text" name="horastrabajadas_editar" id="horastrabajadas_editar" class="form-control inc-edit">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 inpfechreal">
									<div class="form-group label-floating">
										<label class="control-label">Fecha y hora real</label> 
										<input type="text" id="fechareal_editar" name="fechareal_editar" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 inpatencion">
									<div class="form-group label-floating">
										<label class="control-label">Atención</label>
										<select name="atencion_editar" id="atencion_editar" class="form-control inc-edit">
											<option value="remoto">Remoto</option>
											<option value="ensitio">En Sitio</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-10">
									<div class="form-group label-floating">
										<label class="control-label"> Resolución</label>
										<textarea rows="7" cols="50" id="resolucion_editar" name="resolucion_editar" class="form-control"></textarea>
									</div>
								</div>
								<!-- Modal Footer -->
								<div class="float-right">
									<button type="button" class="button regularbold button-green color-white" onclick="guardarFormIncidenteEditar();">Grabar</button>
									<button type="button" class="button regularbold facebook-bg color-white" onclick="cerrarDialogIncidenteEditar();">Cancelar</button>
									<button type="button" class="button regularbold button-orange color-white" style="display:none;" id="btnrevertirfusion" onclick="revertirfusion();">Revertir Fusión</button>									
								</div>
							</div>
							<div class="tab-pane" id="boxcom">
								<!-- COMENTARIO -->
								<div class="col-xs-12 inpcoment">
									<h4>Comentarios</h4>
									<div class="form-group label-floating col-sm-9">
										<label class="control-label" for="comentario">Nuevo Comentario</label>
										<textarea rows="3" class="form-control" name="comentario" id="comentario"></textarea>
									</div>
									<div class="form-group label-floating col-sm-3">
										<?php if($_SESSION['nivel'] != 4): ?>
											<label class="control-label" for="visibilidad">Visibilidad</label>
											<div class="one-half">
												<div class="fac fac-radio fac-green"><span></span>
													<input type="radio" name="visibilidad" id="visibilidad1" value="Público" checked>
													<label for="visibilidad1">Publico</label>
												</div>
											</div>
											<div class="one-half last-column">
												<div class="fac fac-radio fac-red"><span></span>
													<input type="radio" name="visibilidad" id="visibilidad2" value="Privado">
													<label for="visibilidad2">Privado</label>
												</div>
											</div>
										<?php endif; ?>
										<div class="one-half">
											<a href="#" id="btnAgregarComentario" class="button button-xs regularbold button-green color-white" onclick="agregarComentario();">Agregar</a>
										</div>
										<div class="one-half last-column">
											<a href="#" id="btnLimpiarComentario" class="button button-xs regularbold facebook-bg color-white" onclick="limpiarComentario();">Limpiar</a>
										</div>
									</div>
								</div>
								<div class="col-xs-12 gridComent">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablacomentario" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Acciones</th>
													<th>Comentario</th>
													<th>Usuario</th>
													<th>Visibilidad</th>
													<th>Fecha</th>
													<th>Adjunto</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<!-- COMENTARIO ADJUNTO -->
								<div id="dialog-grid-adjunto" style="display: none; padding: 20px; background: rgb(255, 255, 255);" tabindex="-1">
									<div class="row">
										<div class="col-xs-12">
											<input type="hidden" id="idincidentec" value="">
											<input type="hidden" id="idcomentarioc" value="">
										  <!-- Our markup, the important part here! -->
											<div id="drag-and-drop-zone" class="dm-uploader p-3">
												<h3 class="mb-5 mt-5 text-muted">Arrastrar y subir archivos aquí</h3>
												<div class="btn btn-primary btn-block mb-5">
													<span>Buscar archivo</span>
													<input type="file" title='Click para agregar archivos' />
												</div>
											</div><!-- /uploader -->
										</div>
										<div class="col-xs-12">
										  <div class="card h-100">
											<div class="card-header">
											  Lista de archivos
											</div>
											<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
											  <li class="text-muted text-center empty">No hay archivos cargados</li>
											</ul>
										  </div>
										</div>
									  </div><!-- /file list -->
									  <!-- <div class="alert alert-info" role="alert"></div> -->
								</div>
							</div>
							<div class="tab-pane" id="boxest">
								<!-- ESTADOS -->
								<div class="col-xs-12">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablaestados" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Estado anterior</th>
													<th>Estado actual</th>
													<th>Fecha de cambio</th>
													<th>Días transcurridos</th>
												</tr>
											</thead>
										</table>
									</div> 
								</div>
							</div>
							<div class="tab-pane" id="boxhis">
								<!-- HISTORIAL -->						
								<div class="col-xs-12 gridBit">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablabitacora" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Usuario</th>
													<th>Nombre</th>
													<th>Fecha</th>
													<th>Acción</th>
												</tr>
											</thead>
										</table>
									</div> 
								</div>
							</div>
							<div class="tab-pane" id="boxfun">
								<!-- FUSIONADOS -->	
								<div class="col-xs-12">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablafusionados" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Título</th>
													<th>Descripción</th>
													<th>Fecha Creación</th> 
												</tr>
											</thead>
										</table>
									</div> 
								</div>
							</div>
						</div>
					</form>	
				</div>
			</div>
		</div>	
	</div>
</div>