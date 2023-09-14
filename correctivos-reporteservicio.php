<div class="modal fade" id="modaladdreporte" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
				<div class="card-header card-header-success card-header-icon">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="card-title"><span class="tit_reporte"></span>Reporte de Solicitud de Servicio</h4> 
				</div>
				<div class="clearfix"></div>
				<div class="card-content" style="margin-top:-14px">
					<form id="form_reporteservicio_nuevo" method="POST" autocomplete="off">  
						<ul class="nav nav-pills nav-pills-blue menu-tab-sec" role="tablist">
							<li class="nav-item active">
								<a class="nav-link" data-toggle="tab" href="#boxdat" role="tablist" aria-expanded="true">
									Datos
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxfec" role="tablist">
									Fechas
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxrep" role="tablist">
									Repuestos
								</a>
							</li> 
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxfir" role="tablist">
									Firmas
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#boxreportes" role="tablist">
									Reportes
								</a>
							</li>
						</ul>
						<div class="tab-content tab-space">
							<div class="tab-pane active" id="boxdat">
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2"> 								
									<div class="form-group label-floating">
										<label class="control-label"><span class="tit_numeracion"></span>N°</label>
										<input type="text" id="numero" name="numero" disabled class="form-control cls_crear">
										<input type="hidden" id="idreporte" name="idreporte" class="form-control"> 
										<input type="text" id="codigoreporte" name="codigoreporte" class="form-control cls_editar" style="display:none"> 
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de Solicitud </label> 
										<input type="text" name="fechacreacionreporte" id="fechacreacionreporte" class="form-control" disabled>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-2 col-lg-4 inpatencion box-atencion">
									<div class="form-group label-floating">
										<label class="control-label">Tipo de Servicio <span class="ast_red">*</span></label>
										<select name="tiposervicio" id="tiposervicio" class="form-control inc-edit">
											<option value="sinasignar">Sin asignar</option>
											<option value="correctivo">Correctivo</option>
											<!--<option value="preventivo">Preventivo</option>-->
											<option value="evaluacion">Evaluación</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4 inpatencion box-atencion">
									<div class="form-group label-floating">
										<label class="control-label">Estado Final del activo <span class="ast_red">*</span></label>
										<select name="estadoactivo" id="estadoactivo" class="form-control inc-edit">
											<option value="sinasignar">Sin asignar</option>
											<option value="funcional">Funcional</option>
											<option value="funcionalparcial">Funcional Parcial</option>
											<option value="fueraservicio">Fuera de Servicio</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4">
									<div class="form-group label-floating">
										<label class="control-label">Sitio</label>
										<input type="text" name="sitioreporte" id="sitioreporte" class="form-control" disabled> 
									</div>
								</div> 
								<div class="col-xs-12 col-sm-4">
									<div class="form-group label-floating">
										<label class="control-label">Activo</label>
										<input type="text" id="equiporeporte" name="equiporeporte" disabled class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 box-serie">
									<div class="form-group label-floating">
										<label class="control-label">Serie</label>
										<input type="text" name="seriereporte" id="seriereporte" disabled class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-2 box-marca">
									<div class="form-group label-floating">
										<label class="control-label">Marca</label>
										<input type="text" id="marcareporte" name="marcareporte" disabled class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-2 box-modelo">
									<div class="form-group label-floating">
										<label class="control-label">Modelo</label>
										<input type="text" id="modeloreporte" name="modeloreporte" disabled  class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4 box-departamentos">
									<div class="form-group label-floating">
										<label class="control-label">Departamento</label>
										<input type="text" name="departamentoreporte" id="departamentoreporte" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4">
									<div class="form-group label-floating">
										<label class="control-label">Ubicación del Activo</label>
										<input type="text" id="ubicacionactivo" name="ubicacion_activo" class="form-control">
									</div>
								</div>
								<div class="col-xs-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Falla o error reportado <span class="ast_red">*</span></label>
										<textarea name="fallareportada" id="fallareportada" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div>
								<div class="col-xs-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Trabajo realizado <span class="ast_red">*</span></label>
										<textarea name="trabajorealizado" id="trabajorealizado" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div> 
								<div class="col-xs-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Observaciones <span class="ast_red">*</span></label>
										<textarea name="observaciones" id="observaciones" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div>
								<div class="card-footer">
									<div class="float-right">
										<a href="#" class="button regularbold button-orange color-white btngenerarreporte" onclick="generarReporte();" style="display:none;"> Generar Reporte </a>
										<a href="#" class="button regularbold button-green color-white btnguardarreporte btnSave" onclick="guardarReporte();"> Grabar </a>
										<a href="#" class="button regularbold facebook-bg color-white btncancelarreporte" onclick="cerrarReporte();"> Cancelar </a>
										<a href="#" id="viewReport" class="button regularbold facebook-bg color-white btnviewReport" target="_blank"> Ver reporte </a>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="boxfec">
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de atención <span class="ast_red">*</span></label> 
										<input type="text" name="fechaatencion" id="fechaatencion" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Tiempo de viaje <span class="ast_red">*</span></label> 
										<input type="text" name="tiempoviaje" id="tiempoviaje" class="form-control">
									</div>
								</div> 
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Tiempo espera </label> 
										<input type="text" name="tiempoespera" id="tiempoespera" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Hora Inicio <span class="ast_red">*</span></label> 
										<input type="text" name="horainicio" id="horainicio" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Hora final <span class="ast_red">*</span></label> 
										<input type="text" name="horafin" id="horafin" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2 text-center">
									 <span aria-hidden="true" data-placement="right" data-original-title="Agregar" 
									 data-toggle="tooltip" class="icon-reporte icon-col green fa fa-plus" onclick="agregarFechas();"></span>
									 <span aria-hidden="true" data-placement="right" data-original-title="Limpiar" 
									 data-toggle="tooltip" class="icon-reporte icon-col facebook-bg fa fa-eraser" onclick="limpiarFechas();"></span>
								</div>
								<!--<div class="form-group label-floating float-right col-sm-3">
									<div class="one-half">
										<a href="#" id="btnAgregarFechas" class="button button-xs regularbold button-green color-white" onclick="agregarFechas();">Agregar</a>
									</div>
									<div class="one-half last-column">
										<a href="#" id="btnLimpiarFechas" class="button button-xs regularbold facebook-bg color-white" onclick="limpiarFechas();">Limpiar</a>
									</div>
								</div>-->
								<div class="col-xs-12">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablafechastemp" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Acciones</th>
													<th>Fecha</th>
													<th>Tiempo viaje</th>
													<th>Tiempo Labor</th>
													<th>Tiempo Espera</th>
													<th>Hora Inicio</th>
													<th>Hora Final</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class="card-footer">
									<div class="float-right">
									<a href="#" class="button regularbold button-orange color-white btngenerarreporte" onclick="generarReporte();" style="display:none;"> Generar Reporte </a>
										<a href="#" class="button regularbold button-green color-white btnguardarreporte btnSave" onclick="guardarReporte();"> Grabar </a>
										<a href="#" class="button regularbold facebook-bg color-white btncancelarreporte" onclick="cerrarReporte();"> Cancelar </a>
										<a href="#" id="viewReport" class="button regularbold facebook-bg color-white btnviewReport" target="_blank"> Ver reporte </a>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="boxrep">
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Código <span class="ast_red">*</span></label> 
										<input type="text" name="codigorep" id="codigorep" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Cantidad <span class="ast_red">*</span></label> 
										<input type="number" name="cantidadrep" id="cantidadrep" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating">
										<label class="control-label">Descripción <span class="ast_red">*</span></label> 
										<input type="text" name="descripcionrep" id="descripcionrep" class="form-control">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2 text-center">
									 <span aria-hidden="true" data-placement="right" data-original-title="Agregar" 
									 data-toggle="tooltip" class="icon-reporte icon-col green fa fa-plus" onclick="agregarRepuestos();"></span>
									 <span aria-hidden="true" data-placement="right" data-original-title="Limpiar" 
									 data-toggle="tooltip" class="icon-reporte icon-col facebook-bg fa fa-eraser" onclick="limpiarRepuestos();"></span>
								</div>
								<!--<div class="form-group label-floating float-right col-sm-3">
									<div class="one-half">
										<a href="#" id="btnAgregarRepuestos" class="button button-xs regularbold button-green color-white" onclick="agregarRepuestos();">Agregar</a>
									</div>
									<div class="one-half last-column">
										<a href="#" id="btnLimpiarRepuestos" class="button button-xs regularbold facebook-bg color-white" onclick="limpiarRepuestos();">Limpiar</a>
									</div>
								</div> -->
								<div class="col-xs-12">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablarepuestostemp" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Acciones</th>
													<th>Código</th>
													<th>Cantidad</th>
													<th>Descripción</th> 
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class="card-footer">
									<div class="float-right">
										<a href="#" class="button regularbold button-orange color-white btngenerarreporte" onclick="generarReporte();" style="display:none;"> Generar Reporte </a>
										<a href="#" class="button regularbold button-green color-white btnguardarreporte btnSave" onclick="guardarReporte();"> Grabar </a>
										<a href="#" class="button regularbold facebook-bg color-white btncancelarreporte" onclick="cerrarReporte();"> Cancelar </a>
										<a href="#" id="viewReport" class="button regularbold facebook-bg color-white btnviewReport" target="_blank"> Ver reporte </a>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="boxfir">
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Nombre de Técnico <span class="ast_red">*</span></label>
									<input type="text" name="nombretecnico" id="nombretecnico" disabled class="form-control">
								</div>
								
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating">
										<label class="control-label">Nombre 1 del Cliente <span class="ast_red">*</span></label> 
										<input type="text" name="nombrecliente1" id="nombrecliente1" class="form-control">
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating">
										<label class="control-label">Nombre 2 del Cliente </label> 
										<input type="text" name="nombrecliente2" id="nombrecliente2" class="form-control">
									</div>
								</div> 
								
								<canvas id="canvas-limpio" name="canvas-limpio" height="160" style="display: none;">
								 </canvas> 
								
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Firma Técnico 
										<span class="ast_red">*</span>
										<label class="text-right">
											<span class="icon-col blue fa fa-edit boton-editar-firmatecnico" data-id="1" data-toggle="tooltip" data-original-title="Editar" data-placement="right" style="top: -10px;"></span>
											<span class="icon-col yellow fa fa-eraser limpiar-firmatecnico" onclick="javascript:borrarFirma('tecnico');" data-toggle="tooltip" data-original-title="Limpiar " data-placement="right" style="top: -10px; display: none;"></span>
											<span class="icon-col red fa fa-close cancelareditar-firmatecnico" data-toggle="tooltip" data-original-title="Cancelar" data-placement="right" style="top: -10px; display: none;"></span>
										</label>
									</label>
									<canvas id="canvas-tecnico" name="canvas-tecnico" height="160" class="cls_crear cls_cajas_firmas">
										Este navegador no soporta la lectura de firmas.
									</canvas> 
									<img id="mostrarfirmatecnico" src="" alt="Firma Técnico" class="cls_editar cls_cajas_firmas" style="display:none"/>
								</div>
								
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Firma Cliente 1 
										<span class="ast_red">*</span>
										<label class="text-right">
											<span class="icon-col blue fa fa-edit boton-editar-firmacliente1" data-id="1" data-toggle="tooltip" data-original-title="Editar" data-placement="right" style="top: -10px;"></span> 
											<span class="icon-col yellow fa fa-eraser limpiar-firmacliente1" onclick="javascript:borrarFirma('cliente1');" data-toggle="tooltip" data-original-title="Limpiar" data-placement="right" style="top: -10px;display: none;"></span>
											<span class="icon-col red fa fa-close cancelareditar-firmacliente1" data-toggle="tooltip" data-original-title="Cancelar" data-placement="right" style="top: -10px; display: none;"></span>
										</label> 
									</label>
									<canvas id="canvas-cliente1" name="canvas-cliente1" height="160" class="cls_crear cls_cajas_firmas">
										Este navegador no soporta la lectura de firmas.
									</canvas> 
									<img id="mostrarfirmacliente1" src="" alt="Firma Cliente 1" class="cls_editar cls_cajas_firmas" style="display:none"/>
								</div>
								
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Firma Cliente 2  
										<label class="text-right">
											<span class="icon-col blue fa fa-edit boton-editar-firmacliente2" data-id="1" data-toggle="tooltip" data-original-title="Editar" data-placement="right" style="top: -10px;"></span>
											<span class="icon-col yellow fa fa-eraser limpiar-firmacliente2" onclick="javascript:borrarFirma('cliente2');" data-toggle="tooltip" data-original-title="Limpiar" data-placement="right" style="top: -10px;display: none;"></span> 
											<span class="icon-col red fa fa-close cancelareditar-firmacliente2" data-toggle="tooltip" data-original-title="Cancelar" data-placement="right" style="top: -10px; display: none;"></span>
										</label>
									</label>
									<canvas id="canvas-cliente2" name="canvas-cliente2" height="160" class="cls_crear cls_cajas_firmas">
										Este navegador no soporta la lectura de firmas.
									</canvas> 
									<img id="mostrarfirmacliente2" src="" alt="Firma Cliente 2" class="cls_editar cls_cajas_firmas" style="display:none"/>
								</div>
								<div class="clearfix"></div>
								
								<div class="col-xs-12 col-sm-4 col-md-4 cls_fechas_firmas">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de firma del reporte <span class="ast_red">*</span></label> 
										<input type="text" name="fechafirmatecnico" id="fechafirmatecnico" class="form-control">
									</div>
								</div>								
								<div class="col-xs-12 col-sm-4 col-md-4 cls_fechas_firmas">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de firma del Cliente <span class="ast_red">*</span></label> 
										<input type="text" name="fechafirmacliente" id="fechafirmacliente" class="form-control">
									</div>
								</div> 
								<div class="clearfix"></div>
								
								<div class="card-footer">
									<div class="float-right">
									<a href="#" class="button regularbold button-orange color-white btngenerarreporte" onclick="generarReporte();" style="display:none;"> Generar Reporte </a>
										<a href="#" class="button regularbold button-green color-white btnguardarreporte btnSave" onclick="guardarReporte();"> Grabar </a>
										<a href="#" class="button regularbold facebook-bg color-white btncancelarreporte" onclick="cerrarReporte();"> Cancelar </a>
										<a href="#" id="viewReport" class="button regularbold facebook-bg color-white btnviewReport" target="_blank"> Ver reporte </a>
									</div>
								</div>
							</div>
						
		<!--init-reportes-------------------------------------------------------------------------------------------->
							
							
                        <div class="tab-pane" id="boxreportes">
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<h2 class="control-label">Código </h2> 
									</div>
								</div>
							
		
								<div class="col-xs-12">
									<div class="cardtable" style="margin-top:0px">
										<table id="tablareporteservicio" class="table table-striped table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Id</th>
													<th>Codigo</th>
													<th>Fecha creacion</th>
													<th>Creado por:</th>
													<th>Imagen</th> 
												</tr>
											</thead>
										</table>
									</div>
								</div>
		
								<div class="card-footer">
									<div class="float-right">
										<a href="#" class="button regularbold button-orange color-white btngenerarreporte" onclick="generarReporte();" style="display:none;"> Generar Reporte </a>
										<a href="#" class="button regularbold button-green color-white btnguardarreporte btnSave" onclick="guardarReporte();"> Grabar </a>
										<a href="#" class="button regularbold facebook-bg color-white btncancelarreporte" onclick="cerrarReporte();"> Cancelar </a>
										<a href="#" id="viewReport" class="button regularbold facebook-bg color-white btnviewReport" target="_blank"> Ver reporte </a>
									</div>
								</div>
						</div>
							
		<!--end-reportes--------------------------------------------------------------------------------------------->
						</div> 
					</form>
				</div>
			</div>
		</div>
	</div>
</div>