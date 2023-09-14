 <!--MODAL CATEGORÍAS-->
<div class="modal fade" id="modal_categorias">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Categoría</h5>
				<button type="button" class="close" data-dismiss="modal" >&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12 nombre_categoria">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idcategorias" id="idcategorias" type="hidden">
						<input name="idcategoriaspuente" id="idcategoriaspuente" type="hidden">
						<input name="nombre_categoria" id="nombre_categoria" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Módulos <span class="text-red">*</span> <span class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Estos son los módulos donde podrá ser visualizada esta categoría." data-placement="right" aria-hidden="true"></span> </label>
						<select name="tipo_categoria" id="tipo_categoria" class="form-control text" multiple>
							<option value="Correctivo">Correctivo</option>
							<option value="Preventivo">Preventivo</option>
							<option value="Postventa">Postventa</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarcategoria">Guardar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_modulos_categorias">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title titulo_label_categoria"></h5>
				<button type="button" class="close" data-dismiss="modal" >&times;
				</button>
			</div>
			<div class="modal-body"> 
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Módulos <span class="text-red">*</span> <span class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Estos son los módulos donde podrá ser visualizada esta categoría." data-placement="right" aria-hidden="true"></span> </label>
						<select name="tipo_categoria_edit" id="tipo_categoria_edit" class="form-control text" multiple>
							<option value="Correctivo">Correctivo</option>
							<option value="Preventivo">Preventivo</option>
							<option value="Postventa">Postventa</option>
						</select>
					</div>
				</div>
				
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary editarmodulos_categorias">Guardar</button>
			</div>
		</div>
	</div>
</div>  

<!--MODAL SUBCATEGORÍAS-->
 <div class="modal fade" id="modal_subcategorias">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Subcategoría</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idcat" id="idcat" type="hidden">
						<input name="idsubcategorias" id="idsubcategorias" type="hidden">
						<input name="nombre_subcategoria" id="nombre_subcategoria" class="form-control text">
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarsubcategoria">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!--MODAL AMBIENTES-->
 <div class="modal fade" id="modal_ubicaciones">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Ubicación</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idambientes" id="idambientes" type="hidden">
						<input name="nombre_ambiente" id="nombre_ambiente" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Responsables <span class="text-red">*</span></label>
						<select name="responsables" id="responsables" multiple class="form-control text">
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarubicacion">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL SUBAMBIENTES-->
 <div class="modal fade" id="modal_subambientes">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Área</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idamb" id="idamb" type="hidden">
						<input name="idsubambientes" id="idsubambientes" type="hidden">
						<input name="nombre_subambiente" id="nombre_subambiente" class="form-control text">
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarsubambiente">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!--MODAL DEPARTAMENTOS-->
 <div class="modal fade" id="modal_departamentos">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Departamento / Grupo</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="iddepartamentos" id="iddepartamentos" type="hidden">
						<input name="nombre_departamento" id="nombre_departamento" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Tipo <span class="text-red">*</span></label>
						<select name="tipo_departamento" id="tipo_departamento" class="form-control text">
							<option value="departamento">Departamento</option>
							<option value="grupo">Grupo</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardardepartamento">Guardar</button>
			</div>
		</div>
	</div>
</div>
<!--MODAL ESTADOS-->
 <div class="modal fade" id="modal_estados">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Estado</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idestados" id="idestados" type="hidden">
						<input name="idestadospuente" id="idestadospuente" type="hidden">
						<input name="nombre_estado" id="nombre_estado" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Descripción <span class="text-red">*</span></label>
						<input name="descripcion_estado" id="descripcion_estado" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Módulos <span class="text-red">*</span></label>
						<select name="tipo_estados" id="tipo_estados" class="form-control" multiple>
							<option value="Correctivo">Correctivo</option>
							<option value="Preventivo">Preventivo</option>
							<option value="Postventa">Postventa</option>
							<option value="Laboratorio">Laboratorio</option>
							<option value="Flota">Flota</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarestado">Guardar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_modulos_estados">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title titulo_label_estado"></h5>
				<button type="button" class="close" data-dismiss="modal" >&times;
				</button>
			</div>
			<div class="modal-body"> 
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Descripción <span class="text-red">*</span></label>
						<input name="descripcion_estado_edit" id="descripcion_estado_edit" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">					
					<div class="form-group">
						<label class="control-label">Módulos <span class="text-red">*</span> <span class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Estos son los módulos donde podrá ser visualizada esta categoría." data-placement="right" aria-hidden="true"></span> </label>
						<select name="tipo_estados_edit" id="tipo_estados_edit" class="form-control text" multiple>
							<option value="Correctivo">Correctivo</option>
							<option value="Preventivo">Preventivo</option>
							<option value="Postventa">Postventa</option>
							<option value="Laboratorio">Laboratorio</option>
							<option value="Flota">Flota</option>
						</select>
					</div>
				</div>
				
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary editarmodulos_estados">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL PRIORIDADES-->
 <div class="modal fade" id="modal_prioridades">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Prioridad</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idprioridades" id="idprioridades" type="hidden">
						<input name="nombre_prioridad" id="nombre_prioridad" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Descripcion </label>
						<input name="descripcion_prioridad" id="descripcion_prioridad" class="form-control text">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Tiempo de respuesta (horas)<span class="text-red">*</span></label>
						<input type="number" name="tiempo_respuesta" id="tiempo_respuesta" class="form-control text">
					</div>
				</div> 
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardarprioridad">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL ETIQUETAS-->
<div class="modal fade" id="modal_etiquetas">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success-light px-3">
				<h5 class="modal-title">Etiqueta</h5>
				<button type="button" class="close" data-dismiss="modal">&times;
				</button>
			</div>
			<div class="modal-body">
			
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group label-floating">
						<h5 class="text-success tituloetiqueta">Crear nueva etiqueta</h5>
					</div>
				</div>
												
				<div class="col-xs-12 col-sm-12">
					<div class="form-group">
						<label class="control-label">Nombre <span class="text-red">*</span></label>
						<input name="idetiquetas" id="idetiquetas" type="hidden">
						<input name="nombre_etiqueta" id="nombre_etiqueta" class="form-control text">
					</div>
				</div>
				<div class="form-group col-xs-12 col-sm-12"> 
					<label class="text-label" style="width:100%">Seleccionar un color <span class="text-red">*</span></label>
					<div class="colores-lista">
					</div> 
					<input type="hidden" id="idcolores">
				</div>
				<div class="col-lg-12 text-left"> 
					<button type="button" class="btn btn-primary crearetiqueta">Crear</button>
				</div>
				<hr class="mt-4 mb-4">
												
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group label-floating">
						<h5 class="text-success">Etiquetas</h5>
					</div>
				</div>
				<div class="form-group col-md-12 etiquetas m-0 p-0">  
					<label class="text-label" style="width:100%">Seleccionar etiquetas <span class="text-red">*</span></label> 
					<div class="etiquetas-lista">
					</div>  
				</div>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-primary guardaretiqueta">Guardar</button>
			</div>
		</div>
	</div>
</div>	  