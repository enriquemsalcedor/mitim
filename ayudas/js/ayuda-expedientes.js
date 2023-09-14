/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Expedientes");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="El concepto de expediente dispone de varios usos en nuestro idioma, incluso la definición varía según el país. En general, se trata de un instrumento administrativo que recopila la documentación imprescindible que sustenta un acto. Es decir, es el conjunto de todos los documentos y gestiones correspondientes a un asunto o negocio. Es por ello que el expediente es una excelente fuente a la hora de conocer detalles sobre un caso, con leerlo se puede obtener información precisa y además oficial sobre un tema."+$salto2;
	$ayuda2="Los expedientes se almacenan y archivan en lugares especiales y destinados para ello y obviamente disponen de elementos, tales como números, que facilitan su reconocimiento y búsqueda en un archivo. Este módulo se creó con la intención de identificar con exactitud la ubicación física de los expedientes que se generen por cada proyecto. Los campos presentes en la tabla de datos permiten la ubicación de manera rápida y confiable."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


