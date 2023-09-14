$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Gestión de Activos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial' style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1+
	"La Gestión de Activos según definición de las normas ISO es la actividad coordinada de una "+
	"organización para obtener valor a partir de los activos, donde la obtención del valor generalmente  "+
	"implicará balance de costos, riesgos, oportunidades y beneficios de desempeño.  "+
	$salto2
	;
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"Se dividió la pantalla en siete(7) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/pantalla-principal.PNG' width='80%'/> "+
	"</div></div>"
	+$salto2
	;
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"Como en todos los módulos del 'Sistema de Mantenimiento' se va a encontrar con un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/icono-menu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/menu-lateral.png' width='80%'/> "+
	"</div></div>"
	+$salto2
	;
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de 'Gestión de Activos', a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/menu-principal.png' width='90%'  /> "+$salto2+	
	"<span class='titulo'>"+"a) ACTIVOS"+"</span>"+$salto1+
	"Es la primera opción del menú principal y permite actualizar los datos que aparecen en la pantalla, se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/activos.PNG'  />"+$salto2+
	"<span class='titulo'>"+"b) NUEVO"+"</span>"+$salto1+
	"Esta opción de un nuevo elemento para incluir a la 'Gestión de Activos' permite agregarlo con los datos necesarios "+
	" para la gestión del activo. "+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/nuevo.png' /> "+$salto2+
	"<span class='titulo'>"+"FORMULARIO DE GESTIÓN DE ACTIVOS"+"</span>"+$salto1+
	"En el formulario se introduce los datos y se pulsa el botón 'Guardar' o 'Cancelar' según lo que desee hacer el usuario."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/nuevo-activo.PNG' width='80%'/> "+$salto2+	
	"<span class='titulo'>"+"c) EXPORTAR A EXCEL"+"</span>"+$salto1+
	"Este es otro elemento gráfico en la barra de menu principal el cual permite exportar los datos a la hoja de cálculo excel:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/activos/exportaraexcel.PNG'  />"+$salto2+
    "<span class='titulo'>"+"GRABAR ARCHIVO"+"</span>"+$salto1+
	"La opción muestra la pantalla donde pide el nombre y la ubicación del archivo a generar, que después de llenar la información solicitada y pulsar en el botón guardar, el archivo generado puede ser editado."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/activos/grabarenexcel.PNG' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"EDITAR ARCHIVO EN EXCEL"+"</span>"+$salto1+
	"En éste paso buscamos el archivo en la ubicación dada en el paso anterior y lo seleccionamos para visualizar y terminar de adaptar el archivo para su impresión de ser necesario."+ $salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/enexcel.PNG'  width='80%'/>"+
	"</div></div>"
	+$salto2
	;
	$ayuda2="";
	$ayuda2="<span class='titulo'>"+"d) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' representado por el botón siguiente: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/limpiarcolumnas.PNG' /> "+$salto2+
	"y después al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros, "+
	" por ejemplo, si en la columna 'equipo' se filtra por la palabra 'correas' la imágen sería como la siguiente:"+
	$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/filtro2.PNG' width='80%'/> "+
	"</div></div>"+
	$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/filtro.PNG' width='80%'/> "+
	"</div></div>"+
	$salto2
	;
    $ayuda2=$ayuda2+"<span class='titulo'>"+"<br />Zona 3: REGISTROS POR PÁGINA"+"</span>"+$salto1+
	"Permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 7 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todo lo que está en el 'Centro de Costos'."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/registros.png' /> "+
	$salto2; 
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En un conjunto de datos, como aparecen en la tabla de la 'Gestión de Activos', se muestran en filas y columnas, "+
	"donde cada columna es un dato que se quiere almacenar para la 'Gestión de Activos' como por ejemplo 'marca' y una fila es toda la información de un item de la 'Gestión de Costos'. "+
	"En cada nombre de columna se puede dar un clic con el botón izquierdo del ratón y escribir un criterio a través del cual se buscarán elementos en esa columna que coincidan con el criterio y "+
	"al pulsar la tecla 'Entrar' aparecen en pantalla los datos de acuerdo a los criterios de búsquedas introducidos."+
	"Por ejemplo tipeando la palabra 'correas' en la columna 'equipo' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/filtro.PNG' width='80%'/>"+ $salto2;
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS DE DETALLE O DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un elemento de la 'Gestión de Activos', donde en la primera columna de cada línea o fila tiene sus imágenes gráficas o iconos "+
	" de las acciones que pueden afectar a cada fila. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/detalles.PNG' width='80%' />"+ 
	"</div></div>"+
	$salto2+
	
 	"<span class='titulo'>"+"ELIMINAR FILAS"+"</span>"+$salto1+
	"Cada fila de detalle es un elemento de la 'Gestión de Activos' se puede eliminar dependiendo si sus datos están relacionados con otros o no."+
	""+
	"Las diferentes combinaciones al momento de eliminar son las siguientes: "+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/eliminar.PNG' width='80%' />"+ 
	"</div></div>"+
	$salto2+
	
	
	
	"En cualquier punto de una fila que se pulse doble clic permite editar los datos de la 'Gestión de Activos', es decir muestra un formulario "+
	"con los datos del elemento del centro de costos donde se hizo el doble clic para modificar los datos y guardar esa información. "
	+" Una fila de la figura anterior sería el recuadro rojo en la figura siguiente:"
	+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/lineadetalles.PNG' width='80%'/>"+
	"</div></div>"+
	$salto2+
	
	"Si pulsamos doble clic en cualquier punto de esa zona roja mostraría el formulario siguiente:"
	+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/activos/modificar-activos.PNG' width='80%'/>"+ 
	"</div></div>"+
	$salto2;

   $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/barra-desplazamiento.png' width='80%' /> "+$salto2
	; 

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 7: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/activos/desplazamiento-paginas.png' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


