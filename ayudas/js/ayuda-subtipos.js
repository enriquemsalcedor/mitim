/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Subtipos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1;
	$ayuda1='Los subtipos son subconjuntos de un conjunto de datos. Generalmente se usan cuando se quiere tener datos más específicos de un tipo de dato más general, pues, aunque se diferencian en algunos aspectos, no dejan de tener la misma raiz. Es decir, cada subtipo está formado por atributos, que son "heredados" del "tipo" al cual pertenecen.'+$salto2;
	$ayuda2='En consecuencia, el módulo "subtipo" presenta una descripción o característica más detallada de los equipos al cual pertenecen. Por ejemplo, si el tipo es "Limpieza", los subtipos podrían ser: detergentes, desinfectantes, lava platos, entre otros. De ese modo, los equipos que se ingresen al sistema tendrán una descripción mucho más exacta.'+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


