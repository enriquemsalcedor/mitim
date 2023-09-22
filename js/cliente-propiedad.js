var id_cliente = getQueryVariable('id');

getpropiedades(id_cliente);

function provincias(id){
    $.get("controller/combosback.php?oper=provincias", {}, function(result)
    {
        $("#id_provinciap").empty();
        $("#id_provinciap").append(result);
        // if (id != 0){
        //     $("#id_provinciap").val(id).trigger('change');
        // }
    });
}

$("#id_provinciap").on('select2:select', function(e) {
    var id = 0;
    var id_provincia = $(this).val();
    distritos(id, id_provincia);
});

function distritos(id, id_provincia){
    $.get("controller/combosback.php?oper=distritos", { id_provincia: id_provincia }, function(result)
    {
        $("#id_distritop").empty();
        $("#id_distritop").append(result);
        // if (id != 0){
        //     $("#id_distritop").val(id).trigger('change');
        // }
    });
}

$("#id_distritop").on('select2:select', function(e) {
    var id = 0;
    var id_provincia = $("#id_provincia").val();
    var id_distrito = $(this).val();
    corregimientos(id, id_provincia, id_distrito);
});

function corregimientos(id, id_provincia, id_distrito){
    $.get("controller/combosback.php?oper=corregimientos", { id_provincia: id_provincia, id_distrito: id_distrito }, function(result)
    {
        $("#id_corregimientop").empty();
        $("#id_corregimientop").append(result);
        // if (id != 0){
        //     $("#id_corregimientop").val(id).trigger('change');
        // }
    });
}  



provincias(0);
referidos(0);

//Botones de Modales
$("#guardar_propiedad").on("click",function(){
    guardar_propiedad();
});

//VACIAR
function vaciar(){
    $("#id").val("");
    $("#nombrep").val("");
    $("#direccionp").val("");
    $("#id_provinciap").val(null).trigger('change'); 
    $("#id_distritop").val(null).trigger('change'); 
    $("#id_corregimientop").val(null).trigger('change'); 
}

//VALIDAR GRABAR
function validar(nombre,direccion,provincia,distrito,corregimiento){
    var respuesta = 1;
    if (nombre != ""){
        if (nombre.length < 3){
            notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
            respuesta = 0;
        }
    } 
    if (nombre == ""){
        notification('Debe introducir el campo Nombre','Advertencia!','warning');
        respuesta = 0;
    }else if(direccion == ""){
        notification('Debe introducir el campo Dirección','Advertencia!','warning');
        respuesta = 0;
    }else if(provincia == ""){
        notification('Debe seleccionar el campo Provincia','Advertencia!','warning');
        respuesta = 0;
    }else if(distrito == ""){
        notification('Debe seleccionar el campo Distrito','Advertencia!','warning');
        respuesta = 0;
    }else if(corregimiento == ""){
        notification('Debe seleccionar el campo Corregimiento','Advertencia!','warning');
        respuesta = 0;
    }
    console.log(respuesta);
    return respuesta;
}  

function guardar_propiedad(){ 
    var nombre = $("#nombrep").val();
    var direccion = $("#direccionp").val();
    var id_provincia = $("#id_provinciap").val();
    var id_distrito = $("#id_distritop").val();
    var id_corregimiento = $("#id_corregimientop").val();

    let accion = "creado";
    let oper = 'crearpropiedad';

    if(validar(nombre,direccion) == 1){
        $.ajax({
            type: 'post',
            url: 'controller/propiedadesback.php',
            data: { 
                'oper': oper,
                'nombre': nombre,
                'direccion': direccion,             
                'id_provincia': id_provincia,
                'id_distrito': id_distrito,
                'id_corregimiento': id_corregimiento,
                'id_cliente': id_cliente
                
            },
            beforeSend: function() {
                $('#overlay').css('display','block');
            },
            success: function (response) {
                $('#overlay').css('display','none');
                if(response == 2){ 
                    notification('El nombre del cliente ya existe','Advertencia!','warning');
                }else if(response == 0){
                    notification('Ha ocurrido un error al guardar el cliente, intente más tarde','ERROR!','error');
                }else{
                    notification('Cliente '+accion+' satisfactoriamente','Buen trabajo!','success');
                    $('#modal_cliente_creacionrapida').modal('hide');
                    vaciar();
                    $.get( "controller/combosback.php?oper=clientes", function(result){ 
                        $("#idclientes").empty();
                        $("#idclientes").append(result); 
                        $('#idclientes').val(response).trigger('change');	
                    });
                    
                } 
                                    
            },
            error: function (error) {
                console.log(error)
                notification('Ha ocurrido un error al guardar el cliente, intente más tarde','ERROR!',"error");

            }
        });
    }
} 

function getpropiedades(id_cliente){
    
    console.log('.....');
    /*tabla*/
	tablaclientes = $("#tablapropiedades").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		searching: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
		//serverSide: true,
        //serverMethod: 'post',
        stateLoadParams: function (settings, data) {			
			$('th#cnombre input').val(data['columns'][2]['search']['search']);
			$('th#cprovincia input').val(data['columns'][3]['search']['search']);
			$('th#cdistrito input').val(data['columns'][4]['search']['search']);
			$('th#ccorregimiento input').val(data['columns'][5]['search']['search']);
			
        },
        ajax: {
	        url: "controller/propiedadesback.php?oper=getpropiedades&idcliente="+id_cliente,
	    },
	    columns	: [
			{ 	"data": "id" },         //0
			{ 	"data": "acciones" },   //1
			{ 	"data": "nombre" },     //2
            { 	"data": "provincia" }, 	//3
            { 	"data": "distrito" }, 	//4
            { 	"data": "corregimiento"},//5
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				targets : [0],
				visible: false
			},
			{
				targets: [1],
				visible: true,
	            className: 'text-center'
				
			},
			{
	            targets: [2, 3],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ 
				targets	: 3,
				width 	: '200px',
			},
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

}

$("select").select2({ language: "es" });


