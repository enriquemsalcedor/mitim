<div id="dialog-form-fusion" class="swal-modal" style="display: none; width: 500px; padding: 20px; background: rgb(255, 255, 255); min-height: 171px;" tabindex="-1">
	<h2>Fusionar</h2>
	<div class="form-group label-floating">								
		<form>
			<div class="row">
				<div class="col-xs-12">
					<p style="margin-bottom:0px">Registro que prevalecerá</p>
					<div class="incidente-fusion"></div><br>
				</div>
				<div class="col-xs-12">
					<div class="form-group label-floating select2">
						<label class="control-label">Fusionar</label>
						<select multiple name="incidenteafusionar" id="incidenteafusionar" class="form-control"></select>
					</div>
				</div>
			</div>
		</form>
	</div>
	<br/><br/><br/><br/>
	<div class="text-center">
		<button type="button" class="swal-confirm btn btn-success" onclick="fusionarIncidentes();">Grabar</button>
		<button type="button" class="swal-cancel btn btn-danger" onclick="cerrarDialogFusion();">Cancelar</button>
	</div>
</div>