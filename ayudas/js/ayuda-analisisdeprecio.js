/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Análisis de Precios");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Si el precio es una de las variables de decisión principales para la adquisición de bienes y servicios, el análisis de esta variable resulta de suma importancia. Pero definitivamente Lo más importante de un Análisis de Precios es fijar el rendimiento de la obra."+$salto2;
	$ayuda2='Este módulo permite identificar cual es el proveedor que ofrece el mejor precio para un bien o servicio determinado; Por ejemplo, si tres proveedores ofrecen un equipo de Rayos X, la tabla de datos indicará a través de una franja de color verde cual de ellos oferta con el precio más bajo, aportando así una valiosa información para la toma de decisiones. El módulo se encuentra vinculado al módulo de "Proveedores". '+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


