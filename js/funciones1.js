jQuery(function($) {
	// INTERACION CON EL MENU //
	$('#minimizeSidebar').click(function(){
		if($(".switch-sidebar-mini input").prop("checked")){
			$(".switch-sidebar-mini input").prop("checked", false);
		}else{
			$(".switch-sidebar-mini input").prop("checked", true);
		}
	});
	//TITULO MENU
	var titulomenu = $('title').html().split("|");
	var nombremenu = titulomenu[1].toUpperCase();
	$('#nombremenu').html(nombremenu);
	
	function styleCheckbox(table) {
	/**
		$(table).find('input:checkbox').addClass('ace')
		.wrap('<label />')
		.after('<span class="lbl align-top" />')


		$('.ui-jqgrid-labels th[id*="_cb"]:first-child')
		.find('input.cbox[type=checkbox]').addClass('ace')
		.wrap('<label />').after('<span class="lbl align-top" />');
	*/
	}
	//	FILTROS GLOBALES	//
	

    
    
});


function evaluarancho(idtabla){
    var	height =$('#'+idtabla).height();
	var tabla=  $('#'+idtabla).DataTable();  
	    if (tabla.data().count()==0) {
	        $('#'+idtabla).height('36px');
	    }else if ( height <120 && tabla.data().count()>0) {
		    $('#'+idtabla).height('120px');
		}
}


// MENU - CAMBIO DE CLAVE //
function cambiarClave() {
	var valid = true;
	if( $("#nuevaclave").val()==''){
		demo.showSwal('error-message','ALERTA!','debe llenar el campo Nueva Clave');
		return;
	}
	if ( valid ) {
	$.ajax({
		  type: "post",
		  url: "controller/usuariosback.php",
		  data: { 
			"oper"	: "cambiarClave",
			"clave" : $("#nuevaclave").val()
		  },
		  success: function (response) {
				if(response){
					demo.showSwal('success-message','Buen trabajo!','Clave modificada satisfactoriamente');
					$("#nuevaclave").val('');
				}else{
					demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Registro, intente más tarde');
				}
		  },
		  error: function () {
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Registro, intente más tarde');
		  }
	   }); 
	}
	return valid;
}

function getQueryVariable(variable) {
	var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
    }
    return(false);
}

function imageFormat( cellvalue, options, rowObject ){
	return '<img src="'+cellvalue+'"  />';
}
	
function imageUnFormat( cellvalue, options, cell){
	return $('img', cell).attr('src');
}

/**************** **************** **************** GRILLA **************** **************** ****************/
// Función para activar los icono de la Grilla (Comentarios, Evidencias, etc)
function getColumnIndexByName(grid,columnName) {
	var cm = grid.jqGrid('getGridParam','colModel'), i=0,l=cm.length;
	for (; i<l; i+=1) {
		if (cm[i].name===columnName) {
			return i; // return the index
		}
	}
	return -1;
};

//switch element when editing inline
function aceSwitch( cellvalue, options, cell ) {
	setTimeout(function(){
		$(cell) .find('input[type=checkbox]')
			.addClass('ace ace-switch ace-switch-5')
			.after('<span class="lbl"></span>');
	}, 0);
}

//unlike navButtons icons, action icons in rows seem to be hard-coded
function updateActionIcons(table) {
	//ACCIONES COL 
	$(table).closest(".ui-jqgrid").find(".ui-sghref>span.glyphicon-eye-open").removeClass("ui-icon glyphicon glyphicon-eye-open center blue") .addClass("icon-col blue fa fa-eye");
	$(table).closest(".ui-jqgrid").find(".ui-sghref>span.glyphicon-eye-close").removeClass("ui-icon glyphicon glyphicon-eye-close center blue") .addClass("icon-col blue fa fa-eye-slash");
	$(table).closest(".ui-jqgrid-btable").find(".subgrid-cell>span.fa-chevron-right").removeClass("ui-icon ace-icon fa fa-chevron-right center blue") .addClass("icon-col blue fa fa-chevron-right");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-pencil").removeClass("ui-icon ui-icon-pencil") .addClass("icon-col blue fa fa-pencil");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-trash").removeClass("ui-icon ui-icon-trash") .addClass("icon-col red fa fa-trash");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-disk").removeClass("ui-icon ui-icon-disk") .addClass("icon-col green-light fa fa-check");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.ui-icon-cancel").removeClass("ui-icon ui-icon-cancel") .addClass("icon-col red fa fa-times");
	
	//ACCIONES FOOTER - add, edit, view, del, search, refresh 
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.add").removeClass("ui-icon add") .addClass("icon-actions green-light fa fa-plus");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.edit").removeClass("ui-icon edit") .addClass("icon-actions yellow fa fa-pencil");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.view").removeClass("ui-icon view") .addClass("icon-actions blue fa fa-eye");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.del").removeClass("ui-icon del") .addClass("icon-actions red fa fa-times");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.search").removeClass("ui-icon search") .addClass("icon-actions blue fa fa-search");
	$(table).closest(".ui-jqgrid").find(".ui-pg-div>span.refresh").removeClass("ui-icon refresh") .addClass("icon-actions green-light fa fa-refresh");
}

//replace icons with FontAwesome icons like above
function updatePagerIcons(table) {
	//PAGINACIÓN
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-first").removeClass("ui-icon ui-icon ui-icon-seek-first") .addClass("icon-pager fa fa-angle-double-left");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-prev").removeClass("ui-icon ui-icon ui-icon-seek-prev") .addClass("icon-pager fa fa-angle-left");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-next").removeClass("ui-icon ui-icon ui-icon-seek-next") .addClass("icon-pager fa fa-angle-right");
	$(table).closest(".ui-jqgrid").find(".ui-pg-button>span.ui-icon-seek-end").removeClass("ui-icon ui-icon ui-icon-seek-end") .addClass("icon-pager fa fa-angle-double-right");
}

//"¡Exito!""¡Error!""¡Advertencia!"
const notification=(msj,title,tipo)=>{
    
    let option={positionClass: "toast-top-right",
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1}
                
    if(tipo==="success"){
        toastr.success( msj,title,option);
    }else if(tipo==="error"){
        toastr.error( msj,title,option);
    }else if(tipo==="warning"){
        toastr.warning( msj,title,option);    
    }else if(tipo==="info"){
        toastr.info( msj,title,option);    
    }         
}

function beforeDeleteCallback(e) {
	var form = $(e[0]);
	if(form.data('styled')) return false;
	form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
	style_delete_form(form);
	form.data('styled', true);
}

function beforeEditCallback(e) {
	var form = $(e[0]);
	form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	style_edit_form(form);
}

function enableTooltips(table) {
	$('.navtable .ui-pg-button').tooltip({container:'body'});
	$(table).find('.ui-pg-div').tooltip({container:'body'});
}

//enable datepicker
function pickDate( cellvalue, options, cell ) {
	setTimeout(function(){
		$(cell) .find('input[type=text]')
				.datepicker({format:'yyyy-mm-dd' , autoclose:true}); 
	}, 0);
}

//VALIDAR CAMPOS 
function validCampos(t) { 
	if($(t).val()=='' || $(t).val()==0){$(t).addClass('form-valide-error-bottom');}
	else{$(t).removeClass('form-valide-error-bottom');}	
}

function limpiarFormulario(form){
	$(form)[0].reset();
	$("#id").val('');
	$('select option').removeAttr("selected");
	$('input[type=radio]').removeAttr('checked');
	$('.form-control').removeClass('form-valide-error-bottom');
	$('input[type="checkbox"]').parent().removeClass('form-valide-error-bottom');
} 

//Función que permite seleccionar por defecto si hay una sola opción en un selector - (Ignora Sin Asignar)
const optionDefault = (id) =>{
	let longitud = $(`#${id} > option`).length;
	if(longitud == 2) {
		$(`#${id} option:eq(1)`).prop('selected', true);
		$(`#${id}`).trigger('select2:select');
	} 
}

const ajustarDropdown = () =>{
	var dropdownMenu;                                     
	$(window).on('show.bs.dropdown', function(e) {        
		dropdownMenu = $(e.target).find('.dropdown-menu.droptable');
		$('body').append(dropdownMenu.detach());          
		dropdownMenu.css('display', 'block');             
		dropdownMenu.position({                           
		  'my': 'right top',                            
		  'at': 'right bottom',                         
		  'of': $(e.relatedTarget)                      
		})                                                
	});                                                   
	$(window).on('hide.bs.dropdown', function(e) {        
		$(e.target).append(dropdownMenu.detach());        
		dropdownMenu.hide();                              
	});
}
  
var today = new Date();
var dd = ("0" + today.getDate()).slice(-2)
var mm = today.getMonth() + 1; //January is 0!
var yyyy = today.getFullYear();
var hoy = `${yyyy}-${mm}-${dd}`;
var vernotificaciones = 0;

var fechaconsulta = localStorage.getItem('fechaconsulta');
'2021-08-12' !== hoy ? vernotificaciones = 1 : vernotificaciones = 0;
if(vernotificaciones == 1){
    console.log('paso1')
    jQuery.ajax({
       url: "controller/proyectosback.php?oper=hayNotificaciones",
       dataType: "json", 
       success: function(item) { 
           if(item !== 0){ 
               console.log('paso2')
                //$('.notificaciones').show(); 
                //$('.notificaciones').append(item);
           } 
       }
    });
} 
const getNotificaciones = () =>{
	$.ajax({ 
		url: 'controller/proyectosback.php',
		dataType: "json",
		data: { 
			'oper' : 'getnotificaciones' 
		}, 
		success: function (response) {  
			if(response.notific === '0'){
				$('#icono-notificaciones').addClass('bg-warning')
			}else{
				 $('#icono-notificaciones').addClass('bg-success')
				 $('#icono-notificaciones').removeClass('bg-warning')
			}   
			$('.notificaciones').show(); 
		   $(".ul-notificaciones").empty(); 
			$('.ul-notificaciones').append(response.dropdown); 
		}
	 });
} 

getNotificaciones()  


$(".ul-notificaciones").delegate(".eliminar-notificacion", "click", function(){
	
	let idnot = $(this).attr("data-id");	
	let idp  = $(this).attr("data-idproyectos");	
	let tipo = $(this).attr("data-tipo");	
	
	swal({
		title: "Confirmar",
		text: 'Desea eliminar la notificación?',
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Si',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.value === true) {
				
				$.ajax({
					type: 'post',
					url: 'controller/proyectosback.php',
					data: { 
						'oper' 		  : 'deletenotificaciones',
						'id'		  : idnot,
						'idproyectos' : idp,
						'tipo' 		  : tipo
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) { 
						$('#overlay').css('display','none');
						if(response == 1){
							
							getNotificaciones();
							notification('Notificación eliminada satisfactoriamente','Buen trabajo','success');
						}
					}
				 }); 
			}
		}, function (isRechazo){  
		}
	);
			
 
});

$(".ul-notificaciones").delegate(".ir-enlace", "click", function(){
	
	let id   = $(this).attr("data-id");	
	let idp  = $(this).attr("data-idproyectos");	
	let idm  = $(this).attr("data-idmodulo");	
	let tipo = $(this).attr("data-tipo");	
	
	$.ajax({
		type: 'post',
		url: 'controller/proyectosback.php',
		data: { 
			'oper' : 'updatenotificaciones', 
			'id' : id 
		}, 
		success: function (response) { 
			if(response == 1){
				
				setTimeout(function(){
					if(tipo == 'Fin de proyecto' || tipo == 'Fin de horas contratadas'){
						location.href = `proyecto.php?id=${idp}`;
					}else if(tipo == 'Comentario realizado correctivo' || tipo == 'Adjunto realizado correctivo' || tipo == 'Cambio de estado correctivo' || tipo == 'Fuera de servicio'){
						location.href = `correctivo.php?id=${idm}`;
					}else if(tipo == 'Comentario realizado preventivo' || tipo == 'Adjunto realizado preventivo' || tipo == 'Cambio de estado preventivo'){
						location.href = `preventivo.php?id=${idm}`;
					}else if(tipo == 'Compromiso realizado postventa' || tipo == 'Adjunto realizado postventa' || tipo == 'Cambio de estado postventa'){
						location.href = `postventa.php?id=${idm}`;
					}else if(tipo == 'Comentario realizado laboratorio' || tipo == 'Adjunto realizado laboratorio' || tipo == 'Cambio de estado laboratorio'){
						location.href = `laboratorio.php?id=${idm}`;
					}else if(tipo == 'Comentario realizado flota' || tipo == 'Adjunto realizado flota' || tipo == 'Cambio de estado flota'){
						location.href = `flota.php?id=${idm}`;
					} 
				}, 200)  
			}
		}
	 });  
});

 $.ajax({ 
	url: 'controller/proyectosback.php',
	dataType: "json",
	data: { 
		'oper' : 'verHorasContratadas' 
	}, 
	success: function (response) {  
	     console.log(response)
	}
 }); 
 
$('#icono-notificaciones').on('click', function(e){ 
	$('#notificaciones_a').addClass('active')
	$('.tabpane-notificaciones').addClass('active')
	$('.tabpane-notificaciones').addClass('show')
	$('#filtrosmasivos').removeClass('active')  
	$('.tabpane-filtros').removeClass('active')  
});
$('#icono-filtrosmasivos').on('click', function(e){
	$('#notificaciones_a').removeClass('active')
	$('.tabpane-notificaciones').removeClass('active') 
	$('#filtrosmasivos').addClass('active')
	$('.tabpane-filtros').addClass('active')
	$('.tabpane-filtros').addClass('show')
});

var pathname = window.location.pathname;

var arrpathname = pathname.split('/');
var pagina = arrpathname[2]; 
if(pagina != 'correctivos.php' && pagina != 'preventivos.php' && pagina != 'postventas.php' && pagina != 'laboratorios.php' && pagina != 'flotas.php'){
	$("#icono-notificaciones").css("display","none");
};

function agregarPagination(colectorpag, actualIndexpag, idblock, idpag, funcioncall) {
    let arrayPagination = [];

    if (colectorpag.length > 1) {
        $(`#${idblock}`).css("display", "block");
        let indexLeft = (actualIndexpag != 0) ? actualIndexpag - 1 : 0;
        let buttonLeft = '';
        if (actualIndexpag > 0) {

            buttonLeft = `<li class="page-item page-indicator button-pag m-1 mb-2" onclick="${funcioncall}(${indexLeft});">Anterior</li>`;
        }
        let indexRight = (actualIndexpag >= 0) ? actualIndexpag + 1 : 0;
        let buttonRight = '';

        if (indexRight < colectorpag.length) {
            buttonRight = `<li class="page-item page-indicator button-pag m-1 mb-2" onclick="${funcioncall}(${indexRight});">Siguiente</li>`;
        }
        if (buttonLeft != '') {
            arrayPagination.push(buttonLeft);
        } else {
            arrayPagination.push(`<li class="page-item page-indicator button-pag m-1 mb-2">Anterior</li>`);
        }

        for (let index = 0; index < colectorpag.length; index++) {
            if (colectorpag.length < 8) {
               arrayPagination.push(`<li class="page-item m-1 ${(index==actualIndexpag)?`active`:``}" ${(index!==actualIndexpag)?`onclick="${funcioncall}(${index});"`:``}><a class="buton-pag-number page-link" href="javascript:void(0);" style="${(index!==actualIndexpag)?`border:0;`:``}">${index+1}</a></li>`);

            }else if (colectorpag.length > 5 && actualIndexpag < 4) {
                if (index == colectorpag.length-2) {

                    arrayPagination.push(`<span class="ellipsis list">…</span>`);

                }else if (index < 5 || index == colectorpag.length-1){

                   arrayPagination.push(`<li class="page-item m-1 ${(index==actualIndexpag)?`active`:``}" ${(index!==actualIndexpag)?`onclick="${funcioncall}(${index});"`:``}><a class="buton-pag-number page-link" href="javascript:void(0);" style="${(index!==actualIndexpag)?`border:0;`:``}">${index+1}</a></li>`);

                }


            }else if (colectorpag.length > 5 && actualIndexpag > colectorpag.length - 5) {
                if (index == 1) {

                    arrayPagination.push(`<span class="ellipsis list">…</span>`);

                }else if (index > colectorpag.length - 6 || index == 0){

                   arrayPagination.push(`<li class="page-item m-1 ${(index==actualIndexpag)?`active`:``}" ${(index!==actualIndexpag)?`onclick="${funcioncall}(${index});"`:``}><a class="buton-pag-number page-link" href="javascript:void(0);" style="${(index!==actualIndexpag)?`border:0;`:``}">${index+1}</a></li>`);

                }


            }else{
                if (index == 1 || index == colectorpag.length-2) {

                    arrayPagination.push(`<span class="ellipsis list">…</span>`);

                }else if (index == actualIndexpag ||  index== actualIndexpag -1 ||  index== actualIndexpag + 1 || index == 0 || index == colectorpag.length - 1 ){

                    arrayPagination.push(`<li class="page-item m-1 ${(index==actualIndexpag)?`active`:``}" ${(index!==actualIndexpag)?`onclick="${funcioncall}(${index});"`:``}><a class="buton-pag-number page-link" href="javascript:void(0);" style="${(index!==actualIndexpag)?`border:0;`:``}">${index+1}</a></li>`);
                }
            }

        }
            if (buttonRight!='') {
                arrayPagination.push(buttonRight);
            }else{
                arrayPagination.push(`<li class="page-item page-indicator button-pag m-1 mb-2">Siguiente</li>`);
            }
            let html=arrayPagination.join('');
            $(`#${idpag}`).html(html);
    }else{
        $(`#${idblock}`).css("display","none");
    }
}


