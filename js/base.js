    ajustarDropdown();
	$("#icono-filtrosmasivos").css("display","none");
        var incidenteselect = '';
        var tablaincidentes;
        var filasSeleccionadas = new Array();
    
    $(document).ready(function() {
	
	var cvisible = false;
	if(nivel == 1 || nivel == 2 || nivel == 3)
		cvisible = true;
	

	function ajustarTablas(){
		if (screen.width > 1024) {
			//console.log('screen.width: '+screen.width);
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}	

	//AJUSTAR DATATABLES
	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});


    $('#tablaincidentes thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if(title == 'Tipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 50px" /> ');
				}else if(	title == 'Estado' || title == 'Solicitante' || title == 'Creación' || title == 'Empresa' || 
					title == 'Cliente' || title == 'Proyecto' || title == 'Categoría' ||   
					title == 'Serial 1' || title == 'Marca' || title == 'Modelo' || title == 'Prioridad' || title == 'Cierre' ){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Id'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 30px" /> ');
				}else if(title == 'Título' || title == 'Hora creación' || title == 'Dep. / Grupo' || title == 'Sub Categoría' || title == 'Asignado a' || title == 'Estado Ant.' ){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 175px" /> ');
				}else if(title == 'Descripción' || title == 'Ubicación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 250px" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		}else if (title == 'Acción') {
			var ancho = '50px';
		}
		$(this).width(ancho);
	});
    
    $('#preloader').css( 'display','block');
	
	/*tabla*/
    tablaincidentes = $("#tablaincidentes").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
        searching: true,
		serverSide: true,
        serverMethod: 'post',
        stateLoadParams: function (settings, data) {
            const{columns}=data
            $('th#cid input').val(columns[1]['search']['search']);
            $('th#ctipo input').val(columns[2]['search']['search']);
            $('th#cestado input').val(columns[3]['search']['search']);
			$('th#ctitulo input').val(columns[4]['search']['search']);
			$('th#cdescripcion input').val(columns[5]['search']['search']);
			$('th#csolicitante input').val(columns[6]['search']['search']);
			$('th#ccreacion input').val(columns[7]['search']['search']);
			$('th#chorac input').val(columns[8]['search']['search']);
			$('th#cidempresas input').val(columns[9]['search']['search']);
			$('th#ciddepartamentos input').val(columns[10]['search']['search']);
			$('th#ccliente input').val(columns[11]['search']['search']);
			$('th#cproyecto input').val(columns[12]['search']['search']);
			$('th#ccategoria input').val(columns[13]['search']['search']);
			$('th#csubcategoria input').val(columns[14]['search']['search']);
			$('th#casignadoa input').val(columns[15]['search']['search']);
			$('th#csitio input').val(columns[16]['search']['search']);
			$('th#cmodalidad input').val(columns[17]['search']['search']);
			$('th#cserie input').val(columns[18]['search']['search']);
			$('th#cmarca input').val(columns[19]['search']['search']);
			$('th#cmodelo input').val(columns[20]['search']['search']);
			$('th#cprioridad input').val(columns[21]['search']['search']);
			$('th#ccierre input').val(columns[22]['search']['search']);
			$('th#cresolucion input').val(columns[23]['search']['search']);
			$('th#cobservaciones input').val(columns[24]['search']['search']);
        },
	    ajax: {
	        url: "controller/baseconocimientosback.php?oper=incidentes",
	    },
	    columns	: [
			{ 	"data": "acciones"},		//0 - 1
			{ 	"data": "id"},				//1 - 0
			{ 	"data": "tipo"},			//0 - 2
			{ 	"data": "estado" },			//2 - 3
			{ 	"data": "titulo"},			//3 - 4
			{ 	"data": "descripcion"},		//4 - 5
			{ 	"data": "solicitante"},		//5 - 6
			{ 	"data": "fechacreacion"},	//6
			{ 	"data": "horacreacion" },	//7		
			{ 	"data": "idempresas"},		//8
			{ 	"data": "iddepartamentos"},	//9
			{ 	"data": "idclientes" },		//10
			{ 	"data": "idproyectos" },	//11		
			{ 	"data": "idcategoria" },	//12
			{ 	"data": "idsubcategoria"},	//13		
			{ 	"data": "asignadoa" },		//14
			{ 	"data": "sitio" },			//15
			{ 	"data": "modalidad" },		//16
			{ 	"data": "serie" },			//17
			{ 	"data": "marca" },			//18
			{ 	"data": "modelo" },			//19
			{ 	"data": "idprioridad" },	//20
			{ 	"data": "fechacierre" },	//21
			{ 	"data": "resolucion" },		//22
			{ 	"data": "observaciones" }	//23 -24

		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 

			{
//				orderable	: false,
				//className	: 'select-checkbox',
//				searchable	: false,
				visible		: cvisible,
				targets		: [0,1]
			},
			{
				orderable	: false,
				targets		: [3],
				searchable	: false,
			},
			{
				targets	: [4],
				width	: '80px' 
			},
			{
				targets	: [5],
				width	: '140px' 
			},
			{
				targets	: [ 6 ],
				width	: '80px'
			},

			{
				targets	: [ 7,8],
				width	: '80px'
			},
			{
				targets	: [ 9 ],
				visible	: false
			},
			{
				targets		: [ 10 ],
				visible		: true,
				searchable	: true
			},
			{
				targets		: [ 11 ],
				width	: '140px'
			},
			{
				targets		: [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],
				className	: "text-left"
			}
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
            this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            });
			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	/*fin tabla*/
	$("#tablaincidentes tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		console.log(id);
		let url ="basedeconocimiento-v.php"
    //var win = window.open(url,'_blank');
		window.location.href = 'basedeconocimiento-v.php?id='+id;

        // Cambiar el foco al nuevo tab (punto opcional)
    //win.focus();
	});

	// AL CARGARSE LA TABLA
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tablaincidentes").DataTable().search("").draw();
		$('#tablaincidentes_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablaincidentes.ajax.reload();
        ajustarTablas();
    });
	
	// AL CARGARSE LA TABLA
	$('#tablaincidentes').on( 'draw.dt', function () {		
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();		
		// Cambiar color de fondo de columnas ocultas en el menú filtro
    });
	
    /*$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked'))
			seleccionarTodas();
		else
			quitarSelecciones();
	});*/
    $('#tablaincidentes').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
});


