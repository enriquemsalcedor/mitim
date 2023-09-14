/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Equipos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Podría decirse que al hablar de equipos nos referimos al conjunto de máquinas y dispositivo que se necesitan para llevar a cabo tareas propias de una oficina, negocio, empresa o institución, por ejemplo, En la actualidad, el equipo de una oficina suele estar compuesto de ordenadores, teléfonos, equipos de fax, impresoras con escáner, escritorios y sillas."+$salto2;
	$ayuda2='El módulo "Equipos" es considerado como uno de los más importantes dentro del sistema general, por cuanto guarda estrecha vinculación con la mayoria de módulos del sistema. Este módulo en particular guarda un registro detallado de los diferentes bienes sobre los que se asignan las tareas generales y específicas de mantenimiento. Por ello resulta de suma importancia describir con gran detalle las características de cada equipo.'+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


