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
	$('#filtro-desde').bootstrapMaterialDatePicker({ weekStart : 0, time: false, switchOnClick: true
		}).on('change', function(e, date){
			$('#filtro-hasta').bootstrapMaterialDatePicker('setMinDate', date);
	});
	$('#filtro-hasta').bootstrapMaterialDatePicker({ weekStart : 0, time: false, switchOnClick: true });
	
	//	FILTROS REPORTES	//
	$('#reporte-desde').bootstrapMaterialDatePicker({weekStart:0, time:false, switchOnClick:true
		}).on('change', function(e, date){
			$('#reporte-hasta').bootstrapMaterialDatePicker('setMinDate', date);
	});
	$('#reporte-hasta').bootstrapMaterialDatePicker({weekStart:0, time:false, switchOnClick:true});
	$("#reporte-desde,#reporte-hasta").change(function(){
		$("#filtro-desde").val($("#reporte-desde").val());
		$("#filtro-hasta").val($("#reporte-hasta").val());
	});
	$("#filtro-desde,#filtro-hasta").change(function(){
		$("#reporte-desde").val($("#filtro-desde").val());
		$("#reporte-hasta").val($("#filtro-hasta").val());
	});
});

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


