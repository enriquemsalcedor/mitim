var id = getQueryVariable('id');

if(id){
    $('#tabdatos').css('display','block');
    $('#tabpropiedades').css('display','block');
}
	
function provincias(id){
    $.get("controller/combosback.php?oper=provincias", {}, function(result)
    {
        $("#id_provincia").empty();
        $("#id_provincia").append(result);
        if (id != 0){
            $("#id_provincia").val(id).trigger('change');
        }        
    });
}

$("#id_provincia").on('select2:select', function(e) {
    var id = 0;
    var id_provincia = $(this).val();
    distritos(id, id_provincia);
});

$("#id_provinciap").on('select2:select', function(e) {
    var id = 0;
    var id_provinciap = $(this).val();
    distritos(id, id_provinciap);
});

function distritos(id, id_provincia){
    $.get("controller/combosback.php?oper=distritos", { id_provincia: id_provincia }, function(result)
    {
        $("#id_distrito").empty();
        $("#id_distrito").append(result);
        if (id != 0){
            $("#id_distrito").val(id).trigger('change');
        }        
    });
}

$("#id_distrito").on('select2:select', function(e) {
    var id = 0;
    var id_provincia = $("#id_provincia").val();
    var id_distrito = $(this).val();
    corregimientos(id, id_provincia, id_distrito);
});

$("#id_distritop").on('select2:select', function(e) {
    var id = 0;
    var id_provinciap = $("#id_provinciap").val();
    var id_distritop = $(this).val();
    corregimientos(id, id_provinciap, id_distritop);
});

function corregimientos(id, id_provincia, id_distrito){
    $.get("controller/combosback.php?oper=corregimientos", { id_provincia: id_provincia, id_distrito: id_distrito }, function(result)
    {
        $("#id_corregimiento").empty();
        $("#id_corregimiento").append(result);
        if (id != 0){
            $("#id_corregimiento").val(id).trigger('change');
        }
    });
}  

function referidos(id){
    $.get("controller/combosback.php?oper=referidos", {}, function(result)
    {
        $("#id_referido").empty();
        $("#id_referido").append(result);
        if (id != 0){
            $("#id_referido").val(id).trigger('change');
        }
    });
}

$("#id_referido").on('select2:select', function(e) {
    var id = 0;
    var id_referido = $(this).val();
    subreferidos(id, id_referido);
});

function subreferidos(id, id_referido){
    $.get("controller/combosback.php?oper=subreferidos", { id_referido: id_referido }, function(result)
    {
        $("#id_subreferido").empty();
        $("#id_subreferido").append(result);
        if (id != 0){
            $("#id_subreferido").val(id).trigger('change');
        }
    });
}
    //OBTENER COMBOS
/*  	const cargarCombosEditar = ()=>{

    // Cargar opciones para el primer select (Provincias)

    $.post("controller/combosback.php", { oper: "provincias" }, function(result) {
    $("#id_provincia").empty().append(result);
    //console.log(result);
    }).done(function() {
    // Configurar el evento change para que se actualice el segundo select (Distritos)
    $('#id_provincia').on('change', function() {
        let id_provincia = $(this).val();
        
        // Realizar una nueva solicitud al servidor con el ID de la provincia seleccionada
        $.post("controller/combosback.php", { oper: "distritos", id_provincia: id_provincia }, function(result) {
        // Limpiar el segundo select y añadir las opciones actualizadas
        $("#id_distrito").empty().append(result);
        
        // Limpiar el tercer select (Corregimientos)
        $("#id_corregimiento").empty();
        });
    });
    
    // Configurar el evento change para que se actualice el tercer select (Corregimientos)
    $('#id_distrito').on('change', function() {
        let id_provincia = $('#id_provincia').val();
        let id_distrito = $(this).val();
        
        // Realizar una nueva solicitud al servidor con el ID del distrito seleccionado
        $.post("controller/combosback.php", { oper: "corregimientos", id_provincia: id_provincia, id_distrito: id_distrito }, function(result) {
        // Limpiar el tercer select y añadir las opciones actualizadas
        $("#id_corregimiento").empty().append(result);
        });
    });
    });


    // Cargar opciones para el primer select
    $.post("controller/combosback.php", { oper: "referidos" }, function(result) {
        $("#id_referido").empty().append(result);
    }).done(function() {
        // Cuando se haya cargado correctamente el primer select
        // Configuramos el evento change para que se actualice el segundo select
        $('#id_referido').on('change', function() {
            let id_referido = $(this).val();
            
            // Realizar una nueva solicitud al servidor con el ID de la especialidad seleccionada
            $.post("controller/combosback.php", { oper: "subreferidos", id_referido: id_referido }, function(result) {
                // Limpiar el segundo select y añadir las opciones actualizadas
                $("#id_subreferido").empty().append(result);
            });
        });
    });    

}  */

if(id != "" && id != undefined && id != null){
    getcliente();
} else{
    provincias(0);
    referidos(0);
    //cargarCombosEditar();
} 


$("#listado").on("click",function(){
    location.href = 'clientes.php';
}); 





//Botones de Modales
$("#guardar").on("click",function(){
    guardar();
});

//VACIAR
function vaciar(){
    $("#id").val("");
    $("#nombre").val("");
    $("#apellidos").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#correo").val("");
    $("#movil").val(""); 
}

//VALIDAR GRABAR
function validar(nombre,telefono,correo){
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
    }else if(telefono == ""){
        notification('Debe introducir el campo Teléfono','Advertencia!','warning');
        respuesta = 0;
    }else if(correo == ""){
        notification('Debe introducir el campo Correo','Advertencia!','warning');
        respuesta = 0;
    }
    return respuesta;
}  

function guardar(){
    var id = $("#id").val();
    var nombre = $("#nombre").val();
    var apellidos = $("#apellidos").val();
    var direccion = $("#direccion").val();
    var telefono = $("#telefono").val();
    var correo = $("#correo").val();
    var movil = $("#movil").val();
    var id_provincia = $("#id_provincia").val();
    var id_distrito = $("#id_distrito").val();
    var id_corregimiento = $("#id_corregimiento").val();
    var id_referido = $("#id_referido").val();
    var id_subreferido = $("#id_subreferido").val();
    
    let accion = "creado";
    if(id==''){
        oper = 'createclientes';
        //cargarCombosEditar();
    }else{
        oper = 'updateclientes';
        accion="actualizado"
        //cargarCombosEditar();
    }

    if(validar(nombre,telefono,correo) == 1){
        $.ajax({
            type: 'post',
            url: 'controller/clientesback.php',
            data: { 
                'oper': oper,
                'id': id,
                'nombre': nombre,
                'apellidos': apellidos,
                'direccion': direccion,
                'telefono': telefono,
                'correo': correo,
                'movil': movil,
                'id_provincia': id_provincia,
                'id_distrito': id_distrito,
                'id_corregimiento': id_corregimiento,
                'id_referido': id_referido,
                'id_subreferido': id_subreferido
            },
            beforeSend: function() {
                $('#overlay').css('display','block');
            },
            success: function (response) {
                $('#overlay').css('display','none');
                if(response == 1){ 


                        if(oper=="createclientes"){ 
                        
                            swal({		
                                        title: 'Cliente '+accion+' satisfactoriamente',	
                                        text: "¿Desea registrar otro cliente?",
                                        type: "success",
                                        allowEscapeKey : false,
                                        allowOutsideClick: false,
                                        showCancelButton: true,
                                        cancelButtonColor: 'red',
                                        confirmButtonColor: '#09b354',
                                        confirmButtonText: 'Sí',
                                        cancelButtonText: "No"
                                }).then(function(isConfirm) {
                                    console.log(isConfirm)
                                    if (isConfirm.value === true) {
//                						vaciarGuardar();
                                         document.getElementById('nombre').focus();
                                            
                                    }else{
                                        location.href = "clientes.php";
                                    }
                                });
                            
                        }else{
                            notification('Cliente '+accion+' satisfactoriamente','Buen trabajo!','success');
                            location.href = "clientes.php";


                        }


                }else if(response == 2){ 
                    notification('El nombre del cliente ya existe','Advertencia!','warning');
                }else if(response == 0){
                    notification('Ha ocurrido un error al guardar el cliente, intente más tarde','ERROR!','error');
                }else{
                    notification('Ha ocurrido un error al guardar el cliente, intente más tarde','ERROR!','error');

                }
                                    
            },
            error: function (error) {
                console.log(error)
                notification('Ha ocurrido un error al guardar el cliente, intente más tarde','ERROR!',"error");

            }
        });
    }
}



function getcliente(){
    var id = getQueryVariable('id');
    let tipo = getQueryVariable('type');
    
    jQuery.ajax({ 
       url: "controller/clientesback.php?oper=getclientes&idclientes="+id,
       dataType: "json",
       beforeSend: function(){
            $('#preloader').css('display','block');
       },success: function(item) {
               if(item!=0){ 
                provincias(item.id_provincia);
                distritos(item.id_distrito,item.id_provincia);
                corregimientos(item.id_corregimiento,item.id_provincia,item.id_distrito);
                referidos(item.id_referido);
                subreferidos(item.id_subreferido,item.id_referido);
                $('#preloader').css('display','none');
                $("#id").val(id);
                $("#nombre").val(item.nombre);
                $("#descripcion").val(item.descripcion);
                $("#apellidos").val(item.apellidos);
                $("#direccion").val(item.direccion);
                $("#telefono").val(item.telefono);
                $("#correo").val(item.correo);
                $("#movil").val(item.movil);  
               }else{
                notification('Ha ocurrido un error al buscar el cliente '+id+', intente más tarde','ERROR',"error");
                location.href = 'clientes.php';
               }

       },error:function(err) {
            notification('Ha ocurrido un error al buscar el cliente '+id+', intente más tarde','ERROR',"error");
            location.href = 'clientes.php';
       }
    });


}

//Creación rápida de cliente
$('#nueva_propiedad').on('click', function() {
    $('#modal_propiedad').modal('show');
});

$("select").select2({ language: "es" });


