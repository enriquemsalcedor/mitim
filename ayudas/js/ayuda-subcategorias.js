/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Subcategorías");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Una subcategoría es una parte o grupo que resulta de subdividir una categoría en otras. Son formas de clasificación u organización que se usan para presentar datos más específicos sobre una categoría más general. "+$salto2;
	$ayuda2="En consecuencia, este módulo presenta una información mucho más detallada sobre un determinado proyecto; por ejemplo, si la categoría a la que pertenece un proyecto es mantenimiento de instalaciones, esta se dividiría en las siguientes subcategorías: Pintura, herrería, plomería, albañilería, electricidad. Entre otras."+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


