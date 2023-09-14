<div class="modal fade" id="modaladdreporte" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card" style="margin:0px !important;">
			<div class="card-header card-header-success card-header-icon" style="background-color: #36C95F;">
					<button type="button" class="close" data-dismiss="modal" style="color: #fff !important;">&times;</button>
					<h4 class="card-title" style=" color: #fff;">Reporte de Solicitud de Servicio</h4> 
				</div>
				<div class="clearfix"></div>
				<div class="card-content" style="margin: 10px;">
					<form id="form_reporteservicio_nuevo" method="POST" autocomplete="off">  
						<ul class="nav nav-pills review-tab" role="tablist">
							<li class="nav-item active">
								<a class="nav-link active" data-toggle="tab" href="#boxdat" role="tablist" aria-expanded="true">
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
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-4"> 								
									<div class="form-group label-floating">
										<label class="control-label"><span class="tit_numeracion"></span></label>
										<input type="text" id="numero" name="numero" disabled class="form-control text cls_crear">
										<input type="hidden" id="idreporte" name="idreporte" class="form-control"> 
										<input type="text" id="codigoreporte" name="codigoreporte" class="form-control cls_editar text" style="display:none"> 
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-4">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de Solicitud </label> 
										<input type="text" name="fechacreacionreporte" id="fechacreacionreporte" class="form-control text" disabled>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-2 col-lg-4 inpatencion box-atencion">
									<div class="form-group label-floating">
										<label class="control-label">Tipo de Servicio <span class="text-red">*</span></label>
										<select name="tiposervicio" id="tiposervicio" class="form-control text inc-edit">
											<option value="sinasignar">Sin asignar</option>
											<!--<option value="correctivo">Correctivo</option>-->
											<option value="preventivo">Preventivo</option>
											<option value="evaluacion">Evaluación</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4 inpatencion box-atencion">
									<div class="form-group label-floating">
										<label class="control-label">Estado Final del activo <span class="text-red">*</span></label>
										<select name="estadoactivo" id="estadoactivo" class="form-control text inc-edit">
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
										<input type="text" name="sitioreporte" id="sitioreporte" class="form-control text" disabled> 
									</div>
								</div> 
								<div class="col-xs-12 col-sm-4">
									<div class="form-group label-floating">
										<label class="control-label">Activo</label>
										<input type="text" id="equiporeporte" name="equiporeporte" disabled class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 box-serie">
									<div class="form-group label-floating">
										<label class="control-label">Serie</label>
										<input type="text" name="seriereporte" id="seriereporte" disabled class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-2 box-marca">
									<div class="form-group label-floating">
										<label class="control-label">Marca</label>
										<input type="text" id="marcareporte" name="marcareporte" disabled class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-2 box-modelo">
									<div class="form-group label-floating">
										<label class="control-label">Modelo</label>
										<input type="text" id="modeloreporte" name="modeloreporte" disabled  class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4 box-departamentos">
									<div class="form-group label-floating">
										<label class="control-label">Departamento</label>
										<input type="text" name="departamentoreporte" id="departamentoreporte" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-lg-4">
									<div class="form-group label-floating">
										<label class="control-label">Ubicación del Activo</label>
										<input type="text" id="ubicacionactivo" name="ubicacion_activo" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Falla o error reportado <span class="text-red">*</span></label>
										<textarea name="fallareportada" id="fallareportada" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Trabajo realizado <span class="text-red">*</span></label>
										<textarea name="trabajorealizado" id="trabajorealizado" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div> 
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group label-floating is-empty">
										<label class="control-label">Observaciones<span class="text-red">*</span></label>
										<textarea name="observaciones" id="observaciones" rows="4" class="form-control inc-edit"></textarea>
									<span class="material-input"></span></div>
								</div>
							</div>
								<div class="card-footer">
									<div class="float-right">
										<button type="button" class="btn btn-primary btn-xs mr-2 btngenerarreporte" style="display:none;float:right;" onclick="generarReporte();">Generar Reporte</button>
										<button type="button" class="btn btn-primary btn-xs mr-2 btnSave" style="float:right" onclick="guardarReporte();"><i class="fas fa-check-circle"></i>Grabar</button>
										<button type="button" class="btn btn-primary btn-xs mr-2" onclick="cerrarReporte();">Cancelar</button>
										<button type="button" id="viewReport" class="btn btn-primary btn-xs mr-2 btnviewReport" onclick="cerrarReporte();">Ver reporte</button>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="boxfec">
							<div class="row">
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de atención <span class="text-red">*</span></label> 
										<input type="text" name="fechaatencion" id="fechaatencion" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Tiempo de viaje <span class="text-red">*</span></label> 
										<input type="text" name="tiempoviaje" id="tiempoviaje" class="form-control text">
									</div>
								</div> 
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Tiempo espera </label> 
										<input type="text" name="tiempoespera" id="tiempoespera" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Hora de Inicio <span class="text-red">*</span></label> 
										<input type="text" name="horainicio" id="horainicio" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-2 col-md-2">
									<div class="form-group label-floating">
										<label class="control-label">Hora de finalizacion <span class="text-red">*</span></label> 
										<input type="text" name="horafin" id="horafin" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12"></div>
								<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-6">
									<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarFechas();"><i class="fas fa-eraser"></i> Limpiar</button>
									<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregarFechas();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
								</div> 
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
										<button type="button" class="btn btn-primary btn-xs mr-2 btngenerarreporte" style="display:none;float:right;" onclick="generarReporte();">Generar Reporte</button>
										<button type="button" class="btn btn-primary btn-xs mr-2 btnSave" style="float:right" onclick="guardarReporte();"><i class="fas fa-check-circle"></i>Grabar</button>
										<button type="button" class="btn btn-primary btn-xs mr-2" onclick="cerrarReporte();">Cancelar</button>
										<button type="button" id="viewReport" class="btn btn-primary btn-xs mr-2 btnviewReport" onclick="cerrarReporte();">Ver reporte</button>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="boxrep">
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Código <span class="text-red">*</span></label> 
										<input type="text" name="codigorep" id="codigorep" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<label class="control-label">Cantidad <span class="text-red">*</span></label> 
										<input type="number" name="cantidadrep" id="cantidadrep" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-6">
									<div class="form-group label-floating">
										<label class="control-label">Descripción <span class="text-red">*</span></label> 
										<input type="text" name="descripcionrep" id="descripcionrep" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12"></div>
								<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-6">
									<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarRepuestos();"><i class="fas fa-eraser"></i> Limpiar</button>
									<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregarRepuestos();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
								</div> 
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
										<button type="button" class="btn btn-primary btn-xs mr-2 btngenerarreporte" style="display:none;float:right;" onclick="generarReporte();">Generar Reporte</button>
										<button type="button" class="btn btn-primary btn-xs mr-2 btnSave" style="float:right" onclick="guardarReporte();"><i class="fas fa-check-circle"></i>Grabar</button>
										<button type="button" class="btn btn-primary btn-xs mr-2" onclick="cerrarReporte();">Cancelar</button>
										<button type="button" id="viewReport" class="btn btn-primary btn-xs mr-2 btnviewReport" onclick="cerrarReporte();">Ver reporte</button>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="boxfir">
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Nombre de Técnico <span class="text-red">*</span></label>
									<input type="text" name="nombretecnico" id="nombretecnico" disabled class="form-control text">
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating">
										<label class="control-label">Nombre 1 del Cliente <span class="text-red">*</span></label> 
										<input type="text" name="nombrecliente1" id="nombrecliente1" class="form-control text">
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group label-floating">
										<label class="control-label">Nombre 2 del Cliente </label> 
										<input type="text" name="nombrecliente2" id="nombrecliente2" class="form-control text">
									</div>
								</div>
								<canvas id="canvas-limpio" name="canvas-limpio" height="160" style="display: none;">
								 </canvas> 
								<div class="col-xs-12 col-sm-4 col-md-4"> 
									<label class="control-label">Firma Técnico 
										<span class="text-red">*</span>
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
										<span class="text-red">*</span>
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
										<label class="control-label">Fecha de firma del reporte <span class="text-red">*</span></label> 
										<input type="text" name="fechafirmatecnico" id="fechafirmatecnico" class="form-control text">
									</div>
								</div>								
								<div class="col-xs-12 col-sm-4 col-md-4 cls_fechas_firmas">
									<div class="form-group label-floating">
										<label class="control-label">Fecha de firma del Cliente <span class="text-red">*</span></label> 
										<input type="text" name="fechafirmacliente" id="fechafirmacliente" class="form-control text">
									</div>
								</div>
							</div>
								<div class="clearfix"></div>
								
								<div class="card-footer">
									<div class="float-right">
										<button type="button" class="btn btn-primary btn-xs mr-2 btngenerarreporte" style="display:none;float:right;" onclick="generarReporte();">Generar Reporte</button>
										<button type="button" class="btn btn-primary btn-xs mr-2 btnSave" style="float:right" onclick="guardarReporte();"><i class="fas fa-check-circle"></i>Grabar</button>
										<button type="button" class="btn btn-primary btn-xs mr-2" onclick="cerrarReporte();">Cancelar</button>
										<button type="button" id="viewReport" class="btn btn-primary btn-xs mr-2 btnviewReport" onclick="cerrarReporte();">Ver reporte</button>
									</div>
								</div>
							</div>
<!--init-reportes-------------------------------------------------------------------------------------------->
                        <div class="tab-pane" id="boxreportes">
								<div class="col-xs-12 col-sm-4 col-md-3">
									<div class="form-group label-floating">
										<h5 class="col-form-label text-success">Código </h5> 
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
										<button type="button" class="btn btn-primary btn-xs mr-2 btngenerarreporte" style="display:none;float:right;" onclick="generarReporte();">Generar Reporte</button>
										<button type="button" class="btn btn-primary btn-xs mr-2 btnSave" style="float:right" onclick="guardarReporte();"><i class="fas fa-check-circle"></i>Grabar</button>
										<button type="button" class="btn btn-primary btn-xs mr-2" onclick="cerrarReporte();">Cancelar</button>
										<button type="button" id="viewReport" class="btn btn-primary btn-xs mr-2 btnviewReport" onclick="cerrarReporte();">Ver reporte</button>
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