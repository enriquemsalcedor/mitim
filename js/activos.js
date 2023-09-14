//$(document).ready(function() {
    ajustarDropdown();
	// TOOLTIPS
    $('[data-toggle="tooltip"]').tooltip();
    //Permite ver el nombre del campo
    $(".select2").removeClass("is-empty");
    //CSS
    $("#icono-filtrosmasivos").css("display","none");	  
	function ajustarTablas(){
		if (screen.width > 1024) { 
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}	
	//AJUSTAR DATATABLES
	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});
	
	$("#nuevo").click(function(){
		location.href = 'activo.php';
	}); 
	
	//REFRESCAR
	$("#refrescar").on('click', function() {
	    tbactivos.ajax.reload();
        ajustarTablas();  
    });

	//HEADER
	$('#tbactivos thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if(title == 'Nombre' || title == 'Ubicación' || title == 'Responsable' || title == 'Área'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 250px" /> ');
				}else if(title == 'Tipo' || title == 'Marca' || title == 'Modelo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 150px" /> ');
				}else if(title == 'Fecha instalación' || title == 'Clientes' || title == 'Proyectos'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '50px';
		}else if(title == '-'){
			$(this).html( '<input id="chkSelectAll" type="checkbox"  value="A11|" /> ' );
		}
		$(this).width(ancho);
	});

	/*tabla*/
	tbactivos = $("#tbactivos").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		searching: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
						
		serverSide: true,
        serverMethod: 'post',
		select: { style: 'multi' },		 
        stateLoadParams: function (settings, data) {
            const{columns}=data
            $('th#cserial1 input').val(columns[2]['search']['search']);
            $('th#cserial2 input').val(columns[3]['search']['search']);
			$('th#cnombre input').val(columns[4]['search']['search']);
			$('th#cmodalidad input').val(columns[5]['search']['search']);
			$('th#cmarca input').val(columns[6]['search']['search']);
			$('th#cmodelo input').val(columns[7]['search']['search']);
			$('th#cresponsable input').val(columns[8]['search']['search']);
			$('th#cidambientes input').val(columns[9]['search']['search']); 
            $('th#cambiente input').val(columns[10]['search']['search']);
            $('th#csubambiente input').val(columns[11]['search']['search']); 
			$('th#cfase input').val(columns[12]['search']['search']);
			$('th#cfechatopemant input').val(columns[13]['search']['search']);
			$('th#cfechainst input').val(columns[14]['search']['search']);
			$('th#cempresas input').val(columns[15]['search']['search']);
			$('th#cclientes input').val(columns[16]['search']['search']);
			$('th#cproyectos input').val(columns[17]['search']['search']); 
			$('th#cestado input').val(columns[18]['search']['search']);
        },
	    ajax: {
	        url: "controller/activosback.php?oper=activos",
	    },
	    columns	: [
			{ 	"data": "check" },			//0
			{ 	"data": "id" },				//1
			{ 	"data": "acciones" },		//2
			{ 	"data": "serie" },			//3
			{ 	"data": "activo" },			//4
			{ 	"data": "nombre" },			//5
			{ 	"data": "modalidad" },		//6
			{ 	"data": "marca" },			//7
			{ 	"data": "modelo" },			//8
			{ 	"data": "responsable" },	//9
            { 	"data": "idambientes" },	//10
            { 	"data": "ambiente" },		//11
			{ 	"data": "subambiente" }, 	//12
			{ 	"data": "fase" }, 			//13
			{ 	"data": "fechatopemant" },	//14
			{ 	"data": "fechainst" }, 		//15
			{ 	"data": "idempresas" },		//16
			{ 	"data": "idclientes" }, 	//17
			{ 	"data": "idproyectos" }, 	//18
			{ 	"data": "estado" }			//19
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				orderable	: false,
				className	: 'select-checkbox',
				searchable	: false,
				visible		: true,
				targets		: 0
			},
			{
				targets : [1,10,14,16],
				visible: false
			}, 
			{
	            targets: [2, 3, 4],
	            className: 'text-left'
	        }
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],rowCallback: function( row, data) {
	    },
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
           /*  this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            }); */
			//OCULTAR LOADER
			$('#preloader').css('display','none');
			quitarSelecciones();
	    },
		stateSaveParams: function(settings, data) {
            for (var i = 0, ien = data.columns.length; i < ien; i++) {
                delete data.columns[i].visible;
            }
        },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	/*fin tabla*/
	
	tbactivos.columns().every( function () {
        var that = this;
		$( 'input', this.header() ).keypress(function (event) {
			if (this.value!='A11|') {
				if ( event.which == 13 ) {
					if ( that.search() !== this.value ) {
						that
							.search( this.value )
							.draw();
					}
				}
			}
        });	
		/* jQuery('input', this.header()).on('change', function () {
		 alert("prueba 2");
		}); */
    }); 
	$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked')){
			//console.log("paso por aqui");
			seleccionarTodas();
		}else{
			//console.log("paso por else");
			quitarSelecciones();
		}
	});
	
	$('#tbactivos tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var json = tbactivos.rows('.selected').data();
		filasSeleccionadas = [];
		seriesSeleccionadas = [];
		for (var i=0; i<json.length; i++){
			filasSeleccionadas.push(json[i].id);	
			seriesSeleccionadas.push(json[i].serie);	
		} 
	});
	
	function seleccionarTodas() {
		$('#tablaincidentes > tbody  > tr').each(function() {
			$(this).addClass('selected');
		});
		$(this).closest('tr').toggleClass('selected');
		var json = tablaincidentes.rows('.selected').data();
		filasSeleccionadas = [];
		seriesSeleccionadas = [];
		for (var i=0; i<json.length; i++){
			filasSeleccionadas.push(json[i].id);
			seriesSeleccionadas.push(json[i].serie);
		} 
	}
	
	function quitarSelecciones() {
		$('#tablaincidentes > tbody  > tr').each(function() {
			$(this).removeClass('selected');
		});
		filasSeleccionadas = [];
	}
	
	$("#tbactivos tbody").on('dblclick','tr',function(){ 
		var id = $(this).attr("id");
		location.href="activo.php?id="+id;
	}) 
	
	$(document).on('click','.boton-eliminar', function(e){ 
		var id 	   = $(this).attr("data-id");
		var nombre = $("#tbactivos tr#"+id).find('td:nth-child(2)').html();
		eliminaractivo(id,nombre);
     });
	 
	$(document).on('click','.boton-adjuntar', function(e){
		var id = $(this).attr("data-id"); 
		$('#fevidencias').attr('src','filegator/activos.php#/?cd=activos/'+id);
		abrirsolicitudes(id);
	});
	
	$('#tbactivos').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		//$("#tablaincidentes").DataTable().search("").draw();
		//$('#tablaincidentes_wrapper thead input').val('').change();
		tbactivos.state.clear();
		window.location.reload();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbactivos.ajax.reload();
        ajustarTablas();
    }); 
	
	var dirxdefecto = 'incidente'; 
	$('#fevidencias').attr('src','filegator/activos.php#/?cd=%2F'+dirxdefecto); 
	
	//Adjuntos de activos
	function abrirsolicitudes(idactivo) {	
		var valid = true;
		if ( valid ) {
			$.ajax({
				type: 'post',
				url: 'controller/activosback.php',
				data: { 
					'oper': 	'abrirSolicitudes',
					'idactivo': idactivo
				},
				success: function (response) {
					
					$('#fevidencias').attr('src','filegator/activos.php#/?cd=activos/'+idactivo);
					$('#modalEvidencias').modal('show');
					$('#modalEvidencias .modal-lg').css('width','1000px');
					$('#activosevidencias').val(idactivo); 
					$('.titulo-evidencia').html('Activo: '+idactivo+' - Evidencia');
				},
				error: function () {  
					notification("¡Error!",response,"error");  
				}
			}); 
		}
		return valid; 
	}
	
	$("#modalEvidencias").on("hidden.bs.modal", function () {
		var id = $("#idactivos").val();		
		$('#fevidencias').attr('src','filegator/activos.php#/?cd=activos/'+id);
		tbactivos.ajax.reload(null, false);
	});
	
	$('#tbactivos').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
	
    function eliminaractivo(id,nombre){
		$.get( "controller/activosback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			var mensaje 	= "";
			var modulos 	= "";
			var existe  	= 0;  
			var correctivos = result.correctivos;
			var preventivos = result.preventivos; 
			if(correctivos == 0 && preventivos == 0){
				existe = 0;
			}else{
				existe = 1;
			} 
			if(existe == 1){
				notification('Hay registros asociados a este activo, no se puede eliminar.','ERROR!','warning');
			}else{
				//notification('No hay registros asociados a esta activo, confirme si desea eliminar.','INFORMACIÓN!','info');
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar el activo " +nombre+ "?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/activosback.php?oper=deleteactivo", 
							{ 
								onlydata : "true",
								id : id 
							}, function(result){
								if(result == 1){
									notification('Activo eliminado satisfactoriamente','Buen trabajo','success');		
									tbactivos.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar el activo, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);
			} 
		},'json');  
	}
	
	function reportes(){
		$('#modalreportes').modal('show');
	}
	
	$("#exportar").on('click',function(){exportar();});
	
	function exportar(){
		var param =''; 
		var misCookies = document.cookie;
		var listaCookies = misCookies.split(";");
		var micookie = '';
		for (i in listaCookies) {
			busca = listaCookies[i].search("sistema");
			if (busca > -1) {
				micookie=listaCookies[i];
			}
		}
		igual = micookie.indexOf("=");
		sistema = micookie.substring(igual+1);
		var localS = localStorage.getItem('DataTables_tbactivos_/'+sistema+'/activos.php');
		localS = jQuery.parseJSON(localS);
		//PARAMETROS
		param += "&bserial1=" + localS['columns'][3]['search']['search'];
		param += "&bserial2=" + localS['columns'][4]['search']['search'];	
		param += "&bnombre=" + localS['columns'][5]['search']['search'];
		param += "&bmarca=" + localS['columns'][7]['search']['search'];
		param += "&bmodelo=" + localS['columns'][8]['search']['search'];	
		param += "&bresponsable=" + localS['columns'][9]['search']['search'];
		param += "&bubicacion=" + localS['columns'][11]['search']['search']; 
		param += "&btipo=" + localS['columns'][6]['search']['search'];
		param += "&barea=" + localS['columns'][12]['search']['search'];
		param += "&bfase=" + localS['columns'][13]['search']['search'];	 
		param += "&bfechatopemant=" + localS['columns'][14]['search']['search'];	
		param += "&bfechainst=" + localS['columns'][15]['search']['search'];	
		param += "&bempresas=" + localS['columns'][16]['search']['search'];
		param += "&bclientes=" + localS['columns'][17]['search']['search'];	
		param += "&bproyectos=" + localS['columns'][18]['search']['search'];	
		param += "&bestado=" + localS['columns'][19].search.search;	
		
		window.open ("reportes/activosexportar.php?"+param, "_blank");
	}
	
	function fueraservicio(){
		var param ='';
		//var sistema = Cookies.get('sistema');
		var sistema = 'soportedesnew';
		var localS = localStorage.getItem('DataTables_tbactivos_/'+sistema+'/activos.php');
		localS = jQuery.parseJSON(localS);
		//PARAMETROS
		param += "&bserial1=" + localS['columns'][2]['search']['search'];
		param += "&bserial2=" + localS['columns'][3]['search']['search'];
		param += "&bnombre=" + localS['columns'][4]['search']['search'];	
		param += "&bmarca=" + localS['columns'][6]['search']['search'];	
		param += "&bmodelo=" + localS['columns'][7]['search']['search'];	
		param += "&bresponsable=" + localS['columns'][8]['search']['search'];	
		param += "&bubicacion=" + localS['columns'][10]['search']['search'];
		param += "&btipo=" + localS['columns'][5]['search']['search'];	
		param += "&barea=" + localS['columns'][11]['search']['search'];
		param += "&bfase=" + localS['columns'][12]['search']['search']; 	
		param += "&bfechatopemant=" + localS['columns'][13]['search']['search'];
		param += "&bfechainst=" + localS['columns'][14]['search']['search'];
		param += "&bempresas=" + localS['columns'][15]['search']['search'];
		param += "&bclientes=" + localS['columns'][16]['search']['search'];	
		param += "&bproyectos=" + localS['columns'][17]['search']['search'];
		param += "&bestado=" + localS['columns'][18]['search']['search'];
		
		window.open ("reportes/activosfueraservicio.php?"+param, "_blank");
	}
//});


