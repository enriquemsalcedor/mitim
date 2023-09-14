 	
	$("#listado").click(function(){
		location.href = 'subcategorias.php';
	}); 
     
	let getUrl=getQueryVariable('id'); 
	idsubcategoria =getUrl!==false?getUrl:0;  
	
	categorias(0);
	
	function categorias(id){
		$.get("controller/combosback.php?oper=categorias", function(result)
		{
			$("#id_categoria").empty();
			$("#id_categoria").append(result);
			if (id != 0){
				$("#id_categoria").val(id).trigger('change');
			}
		});
	}
	
	if(getUrl!==false){
	    jQuery.ajax({
           url: "controller/subcategoriasback.php?oper=getsubcategorias&idsubcategoria="+idsubcategoria,
           dataType: "json",
           success: function(item) {
				$(".seccion_editar_categoria").removeClass('d-none');
                $("#id").val(item.id); 
				categorias(item.id_categoria);
                $("#nombre").val(item.nombre);  
				abrirCategoriasSubcategorias(idsubcategoria);
           } 
        }); 
	} 
	
	$("#guardar").on("click",function(){		
		getUrl != '' ? editar() : guardar();
	}); 
	
	const vsubcategoria = nombre => {
		let respuesta = 1;
		if (nombre ==''){
			notification('El campo Subcategoría es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}else if(id_categoria == undefined || id_categoria == 0 || id_categoria == null){
			notification('El campo Categoría es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}
		return respuesta;
	}
	
	const vaciarCampos = ()=>{ 
		$('#nombre').val(""); 
	}
	
	const guardar = ()=>{
		
		let respuesta =	 0;
		let id = $('#id').val(); 
		let nombre = $('#nombre').val();   
		let id_categoria = $('#id_categoria').val();   
		let oper = getUrl != '' ? "editarsubcategoria" : "createsubcategoria"; 
		
		if(vsubcategoria(id_categoria,nombre)== 1&& respuesta == 0){
			$.ajax({
				type: 'post', 
				url: `controller/subcategoriasback.php?oper=${oper}`,
				data: {   
					'id' : id, 
					'nombre' : nombre,
					'id_categoria' : id_categoria
				},
				success: function (response) {	
					if(response == 2){
						notification('La subcategoría ya existe!',"Advertencia!","warning")
					}else if(response == 0){ 
						notification('Error al guardar!',"ERROR!!","error");
					}else{
						location.href = `subcategoria.php?id=${response}`;
					} 										
				},
				error: function () {
					notification('Ha ocurrido un error al grabar el Registro, intente mas tarde',"ERROR!!","error")
				}
			});
		}  
	}  
	
	const abrirCategoriasSubcategorias = (id) => {
			tablacategorias = $("#tablacategorias").DataTable({
				scrollY: '100%',
				scrollX: true,
				scrollCollapse: true,
				destroy: true,
				ordering: false,
				processing: true,
				autoWidth : true,
				ajax: { 
					url: `controller/subcategoriasback.php?oper=categorias_subcategorias&idsubcategorias=${id}`,
				},
				columns	: [
					{ 	"data": "id" },
					{ 	"data": "acciones" },
					{ 	"data": "categoria" } 
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
					{ targets	: 0, width	: '0%' },
					{ targets	: 1, width	: '50px'} 


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
			
			$(document).on('click','.boton-eliminar', function(e){

				var id = $(this).attr("data-id");
				var nombre = $("#tablacategorias tr#"+id).find('td:nth-child(2)').html();
				eliminar(id,nombre);
				
			 });
			 
				function eliminar(id,nombre){
					
					let nombre_subcategoria = $("#nombre").val();
					swal({
						title: "Confirmar",
						text: `¿Esta seguro de eliminar la subcategoría ${nombre_subcategoria} de la categoría ${nombre}?`,
						type: "warning",
						showCancelButton: true,
						cancelButtonColor: 'red',
						confirmButtonColor: '#09b354',
						confirmButtonText: 'Si',
						cancelButtonText: "No"
					}).then(
						function(isConfirm){
							if (isConfirm.value === true) {
								$.get( "controller/subcategoriasback.php?oper=eliminar_categoria_subcategoria", 
								{  
									id : id 
								}, function(result){
									if(result == 1){
										notification(`La subcategoria ${nombre_subcategoria} fue eliminada de la categoría ${nombre} satisfactoriamente`,'Buen trabajo','success');		
										tablacategorias.ajax.reload(null, false);
									} else {
										notification(`Ha ocurrido un error al eliminar la subcategoría ${nombre_subcategoria} de la categoría ${nombre}, intente más tarde`,'ERROR','error');
									}
								});
							}
						}, function (isRechazo){  
						}
					);
						  
				} 
	}

	const agregar_categoria_subcategoria = () =>{
		
		let id_categoria = $('#id_categoria').val(); 
		let nombre_subcategoria = $("#nombre").val();
		let nombre_categoria = $('#id_categoria option:selected').text();
		
		if(id_categoria == undefined || id_categoria == 0 || id_categoria == null){
			notification('Debe seleccionar la categoria',"Advertencia!","warning")
		}else{
			$.ajax({
					type: 'post', 
					url: `controller/subcategoriasback.php?oper=agregar_categoria_subcategoria`,
					data: {   
						'id_subcategoria' : idsubcategoria, 
						'id_categoria' : id_categoria
					},
					success: function (response) {	
						if(response == 1){
							notification(`La subcategoría ${nombre_subcategoria} fue agregada a la categoría ${nombre_categoria} satisfactoriamente`,"Exito!","success");
							tablacategorias.ajax.reload();
						}else if(response == 2){
							notification(`La subcategoría ${nombre_subcategoria} ya fue agregada a la categoría ${nombre_categoria}`,"Advertencia!","warning")
						}else{
							notification('Ha ocurrido un error al grabar el Registro',"Error!!","error")
						} 										
					},
					error: function () {
						notification('Ha ocurrido un error al grabar el Registro',"Error!!","error")
					}
				});	
		} 
	}
	
	$("select").select2({ language: "es" });


