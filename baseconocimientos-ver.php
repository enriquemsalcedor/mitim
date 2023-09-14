<div id="dialog-form-incidentes-editar" class="swal" style="display: none; width: 650px; padding: 20px; background: rgb(255, 255, 255); min-height: 171px; z-index:900" tabindex="-1">
	<!--<h2 id="tituloincidente">Incidente</h2>-->
	<form id="form_incidentes_editar" method="POST" autocomplete="off">
		<div class="row" style="height: 500px; overflow: auto;">
			<div class="col-xs-12 col-sm-3 col-md-2 content-incidente">
				<div class="form-group label-floating">
					<label class="control-label" style="font-weight: bold">Incidente</label>
					<input type="text" id="incidente_editar" name="incidente_editar" disabled="disabled" class="form-control" style="font-size: 18px;font-weight: bold;color: #000000;">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-2">
				<div class="form-group label-floating select2">
					<label class="control-label">Solicitante</label>
					<select name="solicitante_editar" id="solicitante_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<div class="form-group label-floating select2">
					<label class="control-label">C.C.</label>
					<select name="notificar_editar" id="notificar_editar" class="form-control" multiple></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 content-fechacreacion">
				<div class="form-group label-floating">
					<label class="control-label">Fecha Creación</label>
					<input type="text" id="relleno_editar" name="relleno_editar" class="form-control" style="width: 0px;height: 0px;margin: 0;padding: 0;">
					<input type="text" name="fechacreacion_editar" id="fechacreacion_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 content-horacreacion">
				<div class="form-group label-floating">
					<label class="control-label">Hora Creación</label>
					<input type="text" name="horacreacion_editar" id="horacreacion_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12" id="fusion_editar" style="display:none;" >
				<div class="form-group label-floating">
					<input type="hidden" id="idincidente_editar" value="">
					<label class="control-label">Fusionado con </label>
					<input type="text" name="fusionado_editar" id="fusionado_editar" class="form-control" disabled>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group label-floating">
					<label class="control-label">Titulo <span class="ast_red">*</span></label>
					<input type="text" id="titulo_editar" name="titulo_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group label-floating">
					<label class="control-label">Descripción</label>
					<textarea name="descripcion_editar" id="descripcion_editar" rows="7" class="form-control"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3" style="display:none;">
				<div class="form-group label-floating select2">
					<label class="control-label">Empresas <span class="ast_red">*</span></label>
					<select name="idempresas_editar" id="idempresas_editar" class="form-control"></select>
				</div>
			</div>								
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Clientes <span class="ast_red">*</span></label>
					<select name="idclientes_editar" id="idclientes_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Proyectos <span class="ast_red">*</span></label>
					<select name="idproyectos_editar" id="idproyectos_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Categoría <span class="ast_red">*</span></label>
					<select name="categoria_editar" id="categoria_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Subcategoría</label>											
					<select name="subcategoria_editar" id="subcategoria_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Sitio <span class="ast_red">*</span></label>
					<select name="unidadejecutora_editar" id="unidadejecutora_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Serie <span class="ast_red">*</span></label>
					<select name="serie_editar" id="serie_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Marca</label>
					<input type="text" name="marca_editar" id="marca_editar" disabled class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Modelo</label>
					<input type="text" id="modelo_editar" name="modelo_editar" disabled class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Prioridad</label>											
					<select name="prioridad_editar" id="prioridad_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Estado</label>
					<select name="estado_editar" id="estado_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating">
					<label class="control-label">Departamentos / Grupos <span class="ast_red">*</span></label>
					<select name="iddepartamentos_editar" id="iddepartamentos_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="form-group label-floating select2">
					<label class="control-label">Asignado a</label>
					<select name="asignadoa_editar" id="asignadoa_editar" class="form-control"></select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 content-fechacierre">
				<div class="form-group label-floating">
					<label class="control-label">Fecha y Hora de Resolución <span class="ast_red">*</span></label>
					<input type="text" id="fecharesolucion_editar" name="fecharesolucion_editar" class="form-control">
					<input type="hidden" id="fechacierre_editar" name="fechacierre_editar" class="form-control">
					<input type="hidden" id="horacierre_editar" name="horacierre_editar" class="form-control">
					<input type="hidden" id="creadopor_editar" name="creadopor_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 inprepse">
				<div class="form-group label-floating">
					<label class="control-label">Reporte de servicio</label>
					<input type="text" id="reporteservicio_editar" name="reporteservicio_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 inphorast">
				<div class="form-group label-floating">
					<label class="control-label">Horas Trabajadas</label>
					<input type="text" name="horastrabajadas_editar" id="horastrabajadas_editar" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 inpatencion">
				<div class="form-group label-floating">
					<label class="control-label">Atención</label>
					<select name="atencion_editar" id="atencion_editar" class="form-control">
						<option value="remoto">Remoto</option>
						<option value="ensitio">En Sitio</option>
					</select>
				</div>
			</div>  
			<div class="col-xs-12 inpresol">
				<div class="form-group label-floating">
					<label class="control-label"> Resolución <span class="ast_red">*</span></label>
					<textarea rows="7" cols="50" id="resolucion_editar" name="resolucion_editar" class="form-control"></textarea>
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
			<!-- HISTORIAL -->
			<div class="col-xs-12 gridBit">
				<h4>Historial</h4>
				<div class="cardtable" style="margin-top:0px">
					<table id="tablabitacora" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Usuario</th>
								<th>Fecha</th>
								<th>Acción</th>
							</tr>
						</thead>									
					</table>
				</div> 
			</div>
			<!--</div>-->
		</div>								
		<br/>
		<div class="content">
			<a href="#" id="btncancelarincidenteeditar" class="button regularbold facebook-bg color-white" onclick="cerrarDialogIncidenteEditar();"> Cancelar </a>
		</div>
	</form>
</div>