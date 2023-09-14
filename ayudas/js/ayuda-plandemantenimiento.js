/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Plan de Mantenimiento");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$identa="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$ayudainicio='<div id="especial"><p style="line-height: 100%; font-size: 14.0pt ; margin-bottom: 15px">';
	$ayuda1='<div style="font-size:14.0pt; margin-bottom: 10px"><font color="navy">GENERAL</font></div>';
	$ayuda2='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Un plan de mantenimiento no es más que el conjunto de tareas concebidas para atender una instalación. Dicho plan contiene todas las tareas necesarias para prevenir los principales fallos que puedan presentarse, siendo muy importante entender que el objetivo de este plan es evitar determinadas averías.</div>';
	$ayuda3='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Los técnicos que tienen que abordar el trabajo de realizar un plan de mantenimiento en ocasiones se encuentran sin un modelo o una base de referencia. Este Módulo trata de ofrecer un modelo posible, que puede ser modificado y mejorado de acuerdo a las necesidades y requerimientos del cliente.</div>';
	$ayuda4='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'En el presente módulo se muestran las actividades que son asignadas a un plan de manteniemiento y para cada actividad se definen claramente las tareas, las frecuencias de cada tarea (diaria, semanal, quincenal, mensual, entre otras..), el responsables, el servicio, el sistema y otra serie de campos que permiten delimitar el rango de acción de cada actividad.</div>';
	$ayuda5='<div style="font-size:14.0pt; margin-bottom: 15px"><font color="navy">¿Como funciona?</font></div>';
	$ayuda6='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'El primer botón del menú principal, “<b>Actividades</b>”, se utiliza para actualizar o refrescar la información de la tabla de datos que se muestra en la pantalla. Inmediatamente el menú muestra el botón “<b>Nuevo</b>”, mediante el cual se pueden crear nuevos planes de mantenimiento. Al aparecer la ventana se ingresan en ella los datos de carácter obligatorio (<span style="color:red">*</span>) y otros datos complementarios.</div>'+$salto1;
	$ayuda7='<img class="center-block" src="imagenesAyuda/plandemantto/planmtto_nuevo.PNG" width="80%"/>'+$salto1;
	$ayuda8='<div style="font-size:14.0pt; margin-bottom: 10px"><font color="navy">Aspectos de interés:</font></div>';
	$ayuda9='<div style="font-size:12.0pt;line-height:100%;"><ol><li><font color="black">Se utiliza el campo “Tarea” para colocar una descripción de la labor a realizar en este nuevo plan de mantenimiento.</li><li>Luego de colocar el nombre de la empresa, él o los clientes asociados a esa empresa y el proyecto que se está ejecutando, se ingresa la información correspondiente a los campos “categorías” y “subcategorías” para delimitar su área de acción.</li><li>El campo “servicio” permite asignar el tipo de servicio que se va a prestar en el plan.</li><li>En el campo “sistema” se ingresa el nombre del sistema al que se le aplicará el plan de mantenimiento.</li><li>El campo de selección “Tipo de plan” permite determinar si el plan será manual o automático. Solo aquellos que sean registrados como “automáticos” podrán ser utilizados para generar las órdenes de trabajo.  La opción “manual” se emplea para aquellos planes que no tengan una frecuencia definida entre las opciones que aparecen en la ventana.</li><li>A través del campo “Frecuencia” se puede definir la periodicidad con la que se aplicará el plan de mantenimiento. Al seleccionar cualquier opción se abre una pequeña ventana para definir el día o el mes, dando así mayor detalle a la información.</li><li>Luego de colocar información en el campo “Observación” (de ser necesaria), se coloca la información referente a los campos “Ambiente” y “Subambiente” a fin de establecer claramente el lugar donde se va a efectuar el mantenimiento.</li><li>Los campos “Equipos”, “Departamentos” y “Responsable” son empleados para identificar él o los equipos que recibirán el mantenimiento, así como el Departamento y la persona responsables de su ejecución.</li><li>El campo “Centro de costos” permite definir el departamento o persona responsable recibir y distribuir los recursos financieros.</li><li>Con el campo “Entregable” concretamos si ese plan pertenece a un entregable y de ser así, se define a cual de ellos.</li></ol></font></div>'+$salto1;
	$ayuda10='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'En el menú principal también encontramos el botón “<b>Editar</b>”, el cual permite modificar, ingresar o eliminar información de manera simultánea de varios registros seleccionados previamente. El botón “<b>Importar</b>” se utiliza para ingresar un registro nuevo que ha sido previamente creado en una página de Excel. Al pulsar el botón se muestra la siguiente imagen:</div>'+$salto1;
	$ayuda11='<img class="center-block" src="imagenesAyuda/plandemantto/planmtto_importar.PNG" width="80%"/>'+$salto2;
	$ayuda12='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'El primer paso es descargar la plantilla en Excel, luego de descargarla se introducen los datos y se graba el archivo, posteriormente desde esta ventana se ubica el archivo donde se guardó, se selecciona y cuando su nombre aparezca en el recuadro se pulsa “Importar” para ingresar finalmente el nuevo plan a la tabla de datos. Con el botón “<b>Filtros</b>” se pueden buscar registros específicos filtrando por campos independientes o por varios campos a la vez, es decir, de manera masiva. El siguiente botón “<b>Generar órdenes</b>”, considerado de los más importantes dentro del sistema, permite crear las órdenes de trabajo de manera automática y de forma sencilla. Al pulsar el botón se muestra la siguiente imagen:</div>'+$salto1;
	$ayuda13='<img class="center-block" src="imagenesAyuda/plandemantto/planmtto_generar ordenes.PNG" width="80%"/>'+$salto2;
	$ayuda14='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Se introducen las fechas desde – hasta y luego se pulsa el botón “Generar”. Las órdenes de trabajo son creadas y cargadas en los módulos de preventivos, actas y calendario. Como se ha explicado anteriormente, se cargan solo las órdenes de aquellos planes que fueron creados como automáticos en el campo “Tipo de plan”.</div>';
	$ayuda15='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'El botón “<b>exportar</b>” permite trasladar los datos de la tabla a una página de Excel, desde donde se pueden manejar los datos a través de tablas dinámicas, gráficos específicos más personalizados o cualquier otra herramienta de análisis de datos suministrada por el software de Microsoft. Aunado a ello, desde Excel, los reportes pueden ser impresos para guardar un registro físico.</div>';
	$ayuda16='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Por último, el botón “<b>limpiar Columnas</b>” regresa a la tabla de datos toda la información que ella contiene luego de haber sido ocultada parcialmente por un proceso de filtrado. Este proceso no es más que la búsqueda de datos específicos. Para ello, se ubica sobre el título de una columna en la tabla de datos, ingresa el dato completo o parcial que se desea encontrar, pulsa el botón “Enter” y el sistema busca (filtra) la información hasta encontrar y presentar solamente el dato solicitado o datos similares al solicitado. Durante el proceso, las letras del título de la columna cambian a color amarillo. Ejemplo:</div>'+$salto1;
	$ayuda17='<img class="center-block" src="imagenesAyuda/plandemantto/planmtto_filtrado.PNG" width="80%"/>'+$salto2;
	$ayuda18='<div style=font-size:17.0pt; align="center"><font color="navy">Botones de la tabla</font></div>'+$salto1;
	$ayuda19='<img class="center-block" src="imagenesAyuda/plandemantto/planmtto_botones.PNG" width="70%"/>'+$salto2;
	$ayuda20='<div style="margin-bottom: 20px;font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-ansi-language:ES-VE;mso-fareast-language:EN-US;mso-bidi-language:AR-SA">'+$identa+'Este módulo permite ingresar a cualquier registro y modificar su contenido gracias a la “<b>Edición por doble clic</b>”. Se muestra una ventana que contiene los mismos campos que se muestran al crear un nuevo plan.</div>';
	$ayudafin="</p></div>";
	/* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayuda4+$ayuda5+$ayuda6+$ayuda7+$ayuda8+$ayuda9+$ayuda10+$ayuda11+$ayuda12+$ayuda13+$ayuda14+$ayuda15+$ayuda16+$ayuda17+$ayuda18+$ayuda19+$ayuda20+$ayudafin);


	});

});


