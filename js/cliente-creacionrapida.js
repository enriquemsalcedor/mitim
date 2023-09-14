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

provincias(0);
referidos(0);

//Botones de Modales
$("#guardar_cliente").on("click",function(){
    guardar_cliente();
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
    $("#id_provincia").val(null).trigger('change'); 
    $("#id_distrito").val(null).trigger('change'); 
    $("#id_corregimiento").val(null).trigger('change'); 
    $("#id_referido").val(null).trigger('change'); 
    $("#id_subreferido").val(null).trigger('change'); 
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

function guardar_cliente(){ 
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
    let oper = 'createclientes';

    if(validar(nombre,telefono,correo) == 1){
        $.ajax({
            type: 'post',
            url: 'controller/clientesback.php',
            data: { 
                'oper': oper,
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
                'id_subreferido': id_subreferido,
                'creacion_rapida': 1
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

$("select").select2({ language: "es" });


