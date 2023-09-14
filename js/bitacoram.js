$(document).ready(function() {
    
    $('#tbbitacora thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0);
		if ((title!='') && (title!='-')) 
			$(this).html( '<input type="text" placeholder="'+title+'" size=12 /> ' );
		else if (title=='-') 
			$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
		if ((title=='Titulo') && (title!='-')) 
			$(this).html( '<input type="text" placeholder="'+title+'" size=32 /> ' );
		
		$(this).width(ancho1);
    });
    
	var tbbitacora = $("#tbbitacora").DataTable({
		scrollX: true,
		destroy: true,
		scrollY: '54vh',
		scrollCollapse: true,
		searching: true,
		stateSave: true, //enable state saving (pagination,search per column,current page, search inputs....à
		stateLoadParams: function (settings, data) {			
			$('th#cusuario input').val(data['columns'][1]['search']['search']);
			$('th#cfecha input').val(data['columns'][2]['search']['search']);
			$('th#cmodulo input').val(data['columns'][3]['search']['search']);
			$('th#caccion input').val(data['columns'][4]['search']['search']);
			$('th#cidentificador input').val(data['columns'][5]['search']['search']);
			$('th#csentencia input').val(data['columns'][6]['search']['search']);
		},
		serverSide: true, 
		select: true,
		colReorder: true,
		fixedHeader: true,
		processing: true, 
		bAutoWidth: false,
		ordering: false,
		"ajax"		:"controller/bitacoraback.php?oper=listbitacora",
		"columns"	: [
			{ 	"data": "id" },
			{ 	"data": "usuario" },
			{ 	"data": "fecha" },
			{ 	"data": "modulo" },
			{ 	"data": "accion" },
			{ 	"data": "identificador" },
			{ 	"data": "sentencia" }
			],
		rowId: 'id',
		"columnDefs": [ 
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			},
			{
				"targets"		: [1,2,3,4,5,6],
				"className"	: "dt-left"
			}
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
	
	$('#limpiarFiltros').click(function(){
		tbbitacora.state.clear();
		window.location.reload();
	});
	
	tbbitacora.columns().every( function () {
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
	});
	
	$("#tbbitacora tbody").on('dblclick','tr',function(){
		var idbitacora = $(this).attr("id"); 
		jQuery.ajax({
           url: "controller/bitacoraback.php?oper=getbitacora&idbitacora="+idbitacora,
           dataType: "json",
           beforeSend: function(){
               $(".loader-maxia").show();
           },success: function(item) {
                $("#modalbitacora").modal("show");
                $("#idbitacora").val(idbitacora);  
				$("#usuario").val(item.usuario);							
				$('#fecha').val(item.fecha);
				$('#modulo').val(item.modulo);
				$('#accion').val(item.accion);
				$('#identificador').val(item.identificador);
				$('#sentencia').val(item.sentencia);
           }
        }); 
	}); 
	
});


