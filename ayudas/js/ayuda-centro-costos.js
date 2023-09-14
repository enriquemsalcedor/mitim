$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Centro de Costos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial' style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2+
	"El Centro de Costos permite hacer una repartición de gastos para un mejor control de la empresa. "+
	"Con la información que genera se puede tener un mejor control y una mejor toma de decisiones para "+
	"definir el rumbo o la permanencia de los diferentes sistemas de costos dentro de la empresa.  "+$salto2+
	"Dicho de forma más sencilla, a nivel presupuestario centro de costos específica a quien se le carga el gasto, es decir, quien posee los fondos o recursos financieros (percibidos a través de una asignación mensual o bimensual o trimestral, etc.) para hacer frente a todos los egresos. Dentro de un proyecto puede existir un solo centro de costos, y se convertiría así en la cuenta madre para todo ese proyecto, o pueden crearse varios centros de costos si las dimensiones del proyecto así lo requieren. Su funcionalidad radica en llevar el control de ingresos y egresos para determinar, a fin de cada mes, si la ejecución de un proyecto mantiene los niveles de rentabilidad."+$salto2;
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"Se dividió la pantalla en siete(7) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/pantalla-principal.PNG' width='80%'/> "+
	"</div></div>"
	+$salto2
	;
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"Como en todos los módulos del 'Sistema de Mantenimiento' se va a encontrar con un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/icono-menu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/menu-lateral.png' width='80%'/> "+
	"</div></div>"
	+$salto2
	;
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de 'Centro de Costos', a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/menu-principal.png' width='90%'  /> "+$salto2+
	
	
	
	"<span class='titulo'>"+"a) CENTRO DE COSTOS"+"</span>"+$salto1+
	"Es la primera opción del menú principal y permite actualizar los datos que aparecen en la pantalla, se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/centrocostos.PNG'  />"+$salto2+
	"<span class='titulo'>"+"b) NUEVO"+"</span>"+$salto1+
	"Esta opción de un nuevo elemento para incluir al 'Centro de Costos' permite agregarlo con los datos obligatorios codigo, nombre, empresas, cliente, "+
	" proyecto, categoría, subcategoría, departamento y responsable. "+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/nuevo.png' /> "+$salto2+
	"<span class='titulo'>"+"FORMULARIO DE CENTRO DE COSTOS"+"</span>"+$salto1+
	"En el formulario se introduce los datos y se pulsa el botón 'Guardar' o 'Cancelar' según lo que desee hacer el usuario."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/nuevo-costo.PNG' width='80%'/> "+$salto2+
	
	
	
	"<span class='titulo'>"+"c) EXPORTAR A EXCEL"+"</span>"+$salto1+
	"Este es otro elemento gráfico en la barra de menu principal el cual permite exportar los datos a la hoja de cálculo excel:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/exportaraexcel.PNG'  />"+$salto2+
    "<span class='titulo'>"+"GRABAR ARCHIVO"+"</span>"+$salto1+
	"La opción muestra la pantalla donde pide el nombre y la ubicación del archivo a generar, que después de llenar la información solicitada y pulsar en el botón guardar, el archivo generado puede ser editado."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/grabarenexcel.PNG' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"EDITAR ARCHIVO EN EXCEL"+"</span>"+$salto1+
	"En éste paso buscamos el archivo en la ubicación dada en el paso anterior y lo seleccionamos para visualizar y terminar de adaptar el archivo para su impresión de ser necesario."+ $salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/enexcel.PNG'  width='80%'/>"+
	"</div></div>"
	+$salto2
	;
	$ayuda2="";
	$ayuda2="<span class='titulo'>"+"d) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' representado por el botón siguiente: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/limpiarcolumnas.PNG' /> "+$salto2+
	"y después al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros, "+
	" por ejemplo, si en la columna 'nombre' se filtra por la palabra 'cc' la imágen sería como la siguiente:"+
	$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/filtro.PNG' width='80%'/> "+
	"</div></div>"+
	$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/filtro2.PNG' width='80%'/> "+
	"</div></div>"+
	$salto2
	;
    $ayuda2=$ayuda2+"<span class='titulo'>"+"<br />Zona 3: REGISTROS POR PÁGINA"+"</span>"+$salto1+
	"Permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 7 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todo lo que está en el 'Centro de Costos'."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/numero-de-registros.png' /> "+
	$salto2; 
	
	
	
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En un conjunto de datos, como aparecen en la tabla del 'Centro de Costos', se muestran en filas y columnas, "+
	"donde cada columna es un dato que se quiere almacenar del 'Centro de Costos' como por ejemplo 'proyecto' y una fila es toda la información de un item del 'Centro de Costos'. "+
	"En cada nombre de columna se puede dar un clic con el botón izquierdo del ratón y escribir un criterio a través del cual se buscarán elementos en esa columna que coincidan con el criterio y "+
	"al pulsar la tecla 'Entrar' aparecen en pantalla los datos de acuerdo a los criterios de búsquedas introducidos."+
	"Por ejemplo tipeando la palabra 'cc' en la columna 'nombre' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/filtro.PNG' width='80%'/>"+ $salto2;
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS DE DETALLE O DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un elemento del 'Centro de Costos', donde en la primera columna de cada línea o fila tiene sus imágenes gráficas o iconos "+
	" de las acciones que pueden afectar a cada fila. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/detalles.PNG' width='80%' />"+ 
	"</div></div>"+
	$salto2+
	"En cualquier punto de una fila que se pulse doble clic permite editar los datos del 'Centro de Costos', es decir muestra un formulario "+
	"con los datos del elemento del centro de costos donde se hizo el doble clic para modificar los datos y guardar esa información. "
	+" Una fila de la figura anterior sería el recuadro rojo en la figura siguiente:"
	+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/lineadetalles.PNG' width='80%'/>"+
	"</div></div>"+
	$salto2+
	
	"Si pulsamos doble clic en cualquier punto de esa zona roja mostraría el formulario siguiente:"
	+$salto2+
	"<div class='frame'><div class='zoom lorem'>"+
	"<img class='center-block' src='imagenesAyuda/centrocostos/editar-centrocostos.PNG' width='80%'/>"+ 
	"</div></div>"+
	$salto2;

   $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/barra-desplazamiento.png' width='80%' /> "+$salto2
	; 

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 7: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/centrocostos/desplazamiento-paginas.png' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


