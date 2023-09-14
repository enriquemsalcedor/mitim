var id = getQueryVariable('id');
if(id != "" && id != undefined && id != null){
	getbitacora();
}else{
	location.href = "bitacoras.php";

}



	$("#listabitacora").on("click",function(){
		location.href = 'bitacora.php';
	});

    //Botones de Modales
    $("#salir-bitacora").on("click",function(){
		location.href = 'bitacora.php';
	});


	$('#fecha').bootstrapMaterialDatePicker({
		weekStart:0, format:'YYYY-MM-DD hh:mm:ss', switchOnClick:true, time:true, lang : 'es', cancelText: 'Cancelar', switchOnClick:true, clearButton: true, clearText: 'Limpiar'
	});
	  




function getbitacora() {


	var idbitacora = getQueryVariable('id'); 
		jQuery.ajax({
           url: "controller/bitacoraback.php?oper=getbitacora&idbitacora="+idbitacora,
           dataType: "json",
           beforeSend: function(){
               $('#overlay').css('display','block');
           },success: function(item) {
           		if(item!=0){
					$('#overlay').css('display','none');
	                $("#idbitacora").val(idbitacora);  
					$("#usuario").val(item.usuario);							
					$('#fecha').val(item.fecha);
					$('#modulo').val(item.modulo);
					$('#accion').val(item.accion);
					$('#identificador').val(item.identificador);
					$('#sentencia').val(item.sentencia);

					$("#textAccion").html(item.accion)

           		}else{
					swal('ERROR','Ha ocurrido un error al buscar la bitacora '+idbitacora+', intente más tarde',"error");
           		}
           }
        }); 

}


