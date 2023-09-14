/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre apartados");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Los apartados, desde el punto de vista contable, están constituidos por cuentas que representan montos estimados de gastos cargados a las operaciones de uno o más proyectos y destinados a reflejar las obligaciones de carácter eventual o circunstancial que mantiene la empresa, tales como: prestaciones sociales, garantías otorgadas a terceros, litigios pendientes, deudas con los proveedores, etc."+$salto2;
	$ayuda2="En este caso, el módulo muestra de manera explícita, el monto total de dinero que se debe cancelar a un proveedor (apartado), la cantidad que se ha cancelado de ese total (desembolso) y la deuda que aún se mantiene con él (pendiente).  Se trata de fondos aportados por los propietarios de la empresa o generados por ella."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


