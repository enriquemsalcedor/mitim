/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre subproyectos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$salto3="<br /><br /><br />"
	$identa="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$ayudainicio="<div id='especial'><p style='line-height: 100%; font-size: 14.0pt ; margin-bottom: 15px;'>";
	$ayuda1='<div style="font-size:14.0pt; margin-bottom: 10px"><font color="navy">GENERAL</font></div>';
	$ayuda2='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'En términos generales un subproyecto es un proyecto insertado en otro proyecto. Los subproyectos se utilizan como un medio de dividir proyectos complejos en partes más pequeñas y por lo tanto más fáciles de manejar. También se le conoce con el nombre de proyectos insertados.</div>';
	$ayuda3='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'El presente módulo permite dentro del sistema mostrar los subproyectos que han sido asignados para cada proyecto general. Contiene una serie de opciones (botones) que permiten al usuario manejar y/o ampliar fácilmente la información que en él se presenta. Es importante señalar que a través de este módulo se accede al módulo de "actividades" haciendo uso del botón destinado para tal fin.</div>';
	$ayuda4='<div style="font-size:14.0pt; margin-bottom: 15px"><font color="navy">¿Como funciona?</font></div>';
    $ayuda5='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'El primer botón del menú principal, “<b>Atrás</b>”, permite regresar al módulo de proyectos, seguidamente, el botón “<b>Subproyectos</b>”, se utiliza para actualizar o refrescar la información de la tabla de datos que se muestra en la pantalla. Mediante el botón <b>“Nuevo”</b> se puede crear de manera muy sencilla un nuevo subproyecto, asociado a su proyecto general, basta con ingresar como datos obligatorios (<span style="color:red">*</span>) el nombre del subproyecto y una descripción del mismo. Como dato adicional se ingresa el número de fases en las que se desarrollará este subproyecto.<div>'+$salto2;
	$ayuda6='<img class="center-block" src="imagenesAyuda/subproyectos/subproyecto_nuevo.PNG" width="65%" />'+$salto2;
	$ayuda7='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Otra forma de crear un nuevo subproyecto es a través del botón <b>“Importar”, </b>el cual se utiliza para ingresar un Projet que haya sido previamente creado con todas sus actividades. Al pulsar el botón se muestra la siguiente imagen:</div>'+$salto1;
	$ayuda8='<img class="center-block" src="imagenesAyuda/subproyectos/importar.PNG" width="60%" />'+$salto3;
	$ayuda9='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Se ubica el Projet a través del botón “Seleccionar archivo” y luego se pulsa “Cargar MPP” para ingresar el nuevo subproyecto a la tabla de datos. Inicialmente el sistema lo carga como una pre-visualización y al regresar al módulo ya se encuentra inserto en la tabla de datos.</div>';
	$ayuda10='<div style="font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Finalizando el menú encontramos el botón “<b>limpiar Columnas</b>” el cual regresa a la tabla de datos toda la información que ella contiene luego de haber sido ocultada parcialmente por un proceso de filtrado. Este proceso no es más que la búsqueda de datos específicos. Para ello, se ubica sobre el título de una columna en la tabla de datos, ingresa el dato completo o parcial que se desea encontrar, pulsa el botón “Enter” y el sistema busca (filtra) la información hasta encontrar y presentar solamente el dato solicitado o datos similares al solicitado. Durante el proceso, las letras del título de la columna cambian a color amarillo. Ejemplo:</div>'+$salto2;
	$ayuda11='<img class="center-block" src="imagenesAyuda/subproyectos/limpiar_columnas.PNG" width="70%" />'+$salto3;
	$ayuda12='<div style=font-size:17.0pt; align="center"><font color="navy">Botones de la tabla</font></div>'+$salto1;
	$ayuda13='<img class="center-block" src="imagenesAyuda/subproyectos/subproyecto_botones.PNG" width="70%" />'+$salto3;
	$ayuda14='<div style="margin-bottom: 20px;font-size:12.0pt;line-height: 107% !important;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA" class="text-justify">'+$identa+'Este módulo permite ingresar a cualquier registro y modificar su contenido gracias a la <b>“edición por doble clic</b>”. Aparece una ventana que contiene dos de los tres campos que se muestran al pulsar el botón “Nuevo” para crear un nuevo subproyecto.</div>'+$salto2;
	$ayuda15='<img class="center-block" src="imagenesAyuda/subproyectos/subproyecto_editar.PNG" width="65%" />';

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayuda4+$ayuda5+$ayuda6+$ayuda7+$ayuda8+$ayuda9+$ayuda10+$ayuda11+$ayuda12+$ayuda13+$ayuda14+$ayuda15+$ayudafin);
	});

});


