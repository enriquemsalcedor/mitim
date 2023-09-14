// TOOLTIPS
$('[data-toggle="tooltip"]').tooltip();
//CSS
$("#icono-notificaciones,#icono-limpiar,#icono-refrescar").css("display","none");
	$(document).ready(function() {
		$('#desdef').bootstrapMaterialDatePicker({
			weekStart:0, switchOnClick:false, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
		});	
		$('#hastaf').bootstrapMaterialDatePicker({
			weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
		});	 
	});
	
	document.addEventListener('DOMContentLoaded', function() {
    /*--LLamado-Funciones-----------------------------------------------------*/
        calendar.render();
      //  updateSize();
    });
    $('.select2-nosearch').select2({
        minimumResultsForSearch: 20
    });
    /*--Declaracion-de-variables----------------------------------------------*/
    let calendarEl = document.getElementById('calendar');
    /*-CONF-CALENDARIO--------------------------------------------------------*/  
    let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    //initialDate: '2021-04-01',
    expandRows: true,
    locale: 'es',
    windowResize: function(arg) {
        console.log('El calendario se ha ajustado a un cambio de tamaño de ventana. Vista actual: ' + arg.view.type);
    },
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay,listWeek'
    },
    views: {
        listDay: { buttonText: 'Lista por día' },
        listWeek: { buttonText: 'Lista por semana' }
      },
    heigth:700,    //navLinks: true, // can click day/week names to navigate views
    selectable: false,
    selectMirror: true,
    businessHours: true,
    editable: false,
    eventOverlap:false,
    dayMaxEvents: true,
    eventClick: function (info) {
        console.log('Event clic: ' + JSON.stringify(info.event))
        //console.log('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
        //console.log('View: ' + info.view.type);
        /*--DESTRUCTURACION-OBJETO-*/
        const {id}=info.event
        const {tipo}=info.event.extendedProps
        //preventivos-correctivos-postventas
        //let url=tipo==="correctivos"?"correctivo":"preventivo"
		let url = "correctivo";
		if(tipo === "correctivos"){
			url = "correctivo";
		}else if(tipo === "preventivos"){
			url = "preventivo";
		}else if (tipo == "postventas"){
			url = "postventa";
		}else if (tipo == "flotas"){
			url = "flota";
		}
		
        window.open(`${url}.php?id=${id}`,'_blank');
        /*--AGREGANDO-CONTENIDO-*/ 
        //info.event.remove()

    },events: {
        url:"controller/calendarioeventos.php",
        method: 'POST',
	    extraParams: {
	        oper: 'eventos'
        },
        failure: function() {
          console.warn("error")
        }
      },
      /* loading: function(bool) {
        document.getElementById('preloader').style.display =
          bool ? 'block' : 'none';
      } */ 
	  loading: function( isLoading, view ) {
            if(isLoading) {
               $('#preloader').css('display','block');
			   console.log('si cargando');
            } else { 
				$('#preloader').css('display','none');
				console.log('no cargando');
            } 
        }
    });
    calendar.updateSize()
    
	const cargar_combo = (oper,id_elemento,value) =>{
		$.get(`controller/combosback.php?oper=${oper}`, {}, function(result)
		{
			$(`#${id_elemento}`).empty();
			$(`#${id_elemento}`).append(result);
			if (value != 0){
				$(`#${id_elemento}`).val(value).trigger('change');
			}
		});
	}
	
    function cargarCombosF(){
		$("select").select2()
		var idempresasf = 1;
		cargar_combo('clientes','idclientesf',0);
		cargar_combo('departamentosgrupos','iddepartamentosf',0);
		cargar_combo('proyectos','idproyectosf',0);				
		cargar_combo('sitiosclientes','idambientesf',0);				
		cargar_combo('categorias','categoriaf',0);				
		cargar_combo('estadosfiltrosmasivos','estadof',0);				
		cargar_combo('prioridades','prioridadf',0);				
		cargar_combo('modalidades','modalidadf',0);				
		cargar_combo('marcas','marcaf',0);				
		cargar_combo('usuarios','solicitantef',0);				
    	
    	//CATEGORIAS - SUBCATEGORIAS
    	$('#categoriaf').on('select2:select',function(){
			var idcategoriaf = $("#categoriaf").val();		
    		
			$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoriaf, function(result){ 
				$("#subcategoriaf").empty();
				$("#subcategoriaf").append(result);
			});
    	});
    	//SITIOS / SERIE
    	$('#idambientesf').on('select2:select',function(){
    		var idsitiof = $("#idambientesf option:selected").val();
    		//SERIE
    		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitiof }, function(result){ 
    			$("#serief").empty();
    			$("#serief").append(result);
    		});
    	});
    	//DEPARTAMENTOS / ASIGNADO A
    	$('#iddepartamentosf').on('select2:select',function(){
    		var iddepartamentosf = $("#iddepartamentosf option:selected").val();	
    		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentosf }, function(result){ 
    			$("#asignadoaf").empty();
    			$("#asignadoaf").append(result);
    		});
    	});  	
    }	

    function verificarfiltros(){
    	$.ajax({
    		type: 'post',
    		dataType: "json",
    		url: 'controller/calendarioeventos.php',
    		data: { 
    			'oper'	: 'verificarfiltros'
    		},
    		success: function (response) {
    		if (response == 1) {
			    $('#icono-filtrosmasivos').removeClass('bg-success');
				$('#icono-filtrosmasivos').addClass('bg-warning');
			}else{
				$('#icono-filtrosmasivos').removeClass('bg-warning');
				$('#icono-filtrosmasivos').addClass('bg-success');
			}
    		}
    	});
    }
    verificarfiltros();
     
    function abrirFiltrosMasivos(){
    	//$("#idempresasf").val('1').trigger("change");
    	$.ajax({
    		type: 'post',
    		dataType: "json",
    		url: 'controller/calendarioeventos.php',
    		data: { 
    			'oper'	: 'abrirfiltros'
    		},
    		beforeSend: function() {
    			$('#preloader').css('display','block');
    		},
    		success: function (response) {
    			$('#preloader').css('display','none');
    			if (response.data!="") {
    				var obj = JSON.parse(response.data);					
    				$("#relleno").val(obj.relleno);
    				$("#desdef").val(obj.desdef);
    				$("#hastaf").val(obj.hastaf);
    				
    				if(obj.tipoprev == 1){ 
    					$("#tipoprev").prop("checked", true); 
    				} 
    				if(obj.tipoinc == 1){ 
    					$("#tipoinc").prop("checked", true); 
    				} 
      				if(obj.tiposol == 1){ 
    					$("#tiposol").prop("checked", true); 
    				} 
    				  
    				$("#idempresasf").val(obj.idempresas).trigger("change");
					cargar_combo('clientes','idclientesf',obj.idclientesf);
					cargar_combo('departamentosgrupos','iddepartamentosf',obj.iddepartamentosf);
					cargar_combo('proyectos','idproyectosf',obj.idproyectosf);				
					cargar_combo('sitiosclientes','idambientesf',obj.idambientesf);
    				cargar_combo('categorias','categoriaf',obj.categoriaf);
					
    				//CATEGORIAS - SUBCATEGORIAS
    				$.when( $('#categoriaf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
						let id_categorias = obj.categoriaf.toString(); 
    					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: id_categorias }, function(result){ 
    						$("#subcategoriaf").empty();
    						$("#subcategoriaf").append(result);
    						$("#subcategoriaf").val(obj.subcategoriaf).trigger("change");
    					});
    				});
    				//SITIOS / SERIE
    				$.when( $('#idambientesf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
    					//SERIE
    					$.get( "controller/combosback.php?oper=serie", { idsitio: obj.idambientesf }, function(result){ 
    						$("#serief").empty();
    						$("#serief").append(result);
    						$("#serief").val(obj.serief).trigger("change");
    					});
    				});
    				//DEPARTAMENTOS / ASIGNADO A
    				$.when( $('#iddepartamentosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
    					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: obj.iddepartamentosf }, function(result){ 
    						$("#asignadoaf").empty();
    						$("#asignadoaf").append(result);
							$("#asignadoaf").val(obj.asignadoaf).trigger("change");	
    					});
    				});
    				
    				if ('modalidadf' in obj) $("#modalidadf").val(obj.modalidadf).trigger('change');
    				if ('marcaf' in obj) $("#marcaf").val(obj.marcaf).trigger('change');
    				if ('solicitantef' in obj) $("#solicitantef").val(obj.solicitantef).trigger('change');
    				if ('estadof' in obj) $("#estadof").val(obj.estadof).trigger('change');	
    				if ('prioridadf' in obj) $("#prioridadf").val(obj.prioridadf).trigger('change');	
    			}
    		}
    	});
    }

	$("#filtrarmasivo").on('click', function() {
		filtrosMasivos();
	});
    $("#limpiarfiltros").on('click', function() {
		limpiarFiltrosMasivos();
	});
    function filtrosMasivos() {
    	var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
    	var data 			= {};
    	
    	for (var i in dataserialize) {
    		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
    		if( dataserialize[i].name == 'categoriaf' || dataserialize[i].name == 'subcategoriaf' || 
    			dataserialize[i].name == 'idempresasf' || dataserialize[i].name == 'iddepartamentosf' || 
    			dataserialize[i].name == 'idclientesf' || dataserialize[i].name == 'idproyectosf' || 
    			dataserialize[i].name == 'modalidadf'  || dataserialize[i].name == 'prioridadf' || 
    			dataserialize[i].name == 'solicitantef'  || dataserialize[i].name == 'estadof' || 
    			dataserialize[i].name == 'asignadoaf'  || dataserialize[i].name == 'idambientesf' ||
    			dataserialize[i].name == 'marcaf' ){
    			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
    		}else{
    			data[dataserialize[i].name] = dataserialize[i].value;	
    		}		
    	} 
    	if($('#tipoinc').is(':checked')){
    		data['tipoinc'] = 1;
    	}else{
    		data['tipoinc'] = '';
    	}
    	if($('#tipoprev').is(':checked')){
    		data['tipoprev'] = 1;
    	}else{
    		data['tipoprev'] = '';
    	}
       	if($('#tiposol').is(':checked')){
    		data['tiposol'] = 1;
    	}else{
    		data['tiposol'] = '';
    	}
    	
    	data = JSON.stringify(data);	
    	$.ajax({
    		type: 'post',
    		dataType: "json",
    		url: 'controller/calendarioeventos.php',
    		data: { 
    			'oper'	: 'guardarfiltros',
    			'data'	: data
    		},
    		beforeSend: function() {
			$('#preloader').css('display','block');
	    	},
    		success: function (response) {	
    		$('#preloader').css('display','none');
    			calendar.refetchEvents()
                console.log("refrescar")
    			$(".chatbox-close").click();
    			verificarfiltros();
    		}
    	});
    }
    function limpiarFiltrosMasivos(){
    	$('#icono-filtrosmasivos').removeClass('bg-warning');
    	$('#icono-filtrosmasivos').addClass('bg-success');
    	$.get( "controller/calendarioeventos.php?oper=limpiarFiltrosMasivos");
    	$("#tipoprev").prop("checked", false); 
    	$("#tiposol").prop("checked", false); 
    	var dataserialize = $("#form_filtrosmasivos").serializeArray();
    	for (var i in dataserialize) {
    		$("#"+dataserialize[i].name).val(null).trigger("change");
    		calendar.refetchEvents()
    		$(".chatbox-close").click();
    	}
    	$("#tipoinc").prop("checked", false); 
    } 
    
    //CALENDARIO
    //$('#desdef, #hastaf').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true });
    
    $(document).ready(function() {
    	cargarCombosF();
    });


