 
 
 
 
 
 
 
 
 
 
const cargarModulo = () =>{
	var actualIndexVerdes = 0;
	var colectorVerdes = [{ 'id': 0, 'titulo': ''}];
	var actualIndexAmarillos = 0;
	var colectorAmarillos = [{ 'id': 0, 'titulo': ''}];
	var actualIndexRojos = 0;
	var colectorRojos = [{ 'id': 0, 'titulo': ''}];
	 
	let enviarSolicitud = (oper,txtcant,colector,actualIndex,capa,pagination,pag,func) =>{
		jQuery.ajax({
			url: `controller/semaforoback.php?oper=${oper}`,
			dataType: "json",
			beforeSend: function(){
				$('#preloader').css('display','block'); 
			},success: function(result) {
				$('#preloader').css('display','none'); 
				if (result !== '0') { 
					$(`.${txtcant}`).text(`(${result.total})`); 
					colector = result.data;  
					crearTarjetas(`${capa}`,actualIndex,colector,pagination,pag,func);
				}
			}
		});
	}

	enviarSolicitud('cargarCorrectivosVerdes','cantidadverdes',colectorVerdes,actualIndexVerdes,'itemsverdes',"paginationVerdes","pagVerdes","agregarlistadoverdes");
	enviarSolicitud('cargarCorrectivosAmarillos','cantidadamarillos',colectorAmarillos,actualIndexAmarillos,'itemsamarillos',"paginationAmarillos","pagAmarillos","agregarlistadoamarillos");
	enviarSolicitud('cargarCorrectivosRojos','cantidadrojos',colectorRojos,actualIndexRojos,'itemsrojos',"paginationRojos","pagRojos","agregarlistadorojos");
	
	var hoy = new Date();
	var fecha = `${('0' + hoy.getDate()).slice(-2)}-${('0' + ( hoy.getMonth() + 1 )).slice(-2)}-${hoy.getFullYear()}`;
	var hora = `${('0' + hoy.getHours()).slice(-2)}:${('0' + hoy.getMinutes()).slice(-2)}:${ ('0' + hoy.getSeconds()).slice(-2)}`;
	var prox = `${('0' + hoy.getHours()).slice(-2)}:${('0' + (hoy.getMinutes() + 5)).slice(-2)}:${ ('0' + hoy.getSeconds()).slice(-2)}`;
	
	var fechaYHora = `${fecha} ${hora}`;
	$(".ultima-actualizacion").text(`${fechaYHora}`);
	var minutes = hoy.getMinutes(); 
	var proximaFechaYHora = `${fecha} ${prox}`;
	$(".proxima-actualizacion").text(` ${proximaFechaYHora}`); 
} 
  
const crearTarjetas = (capa,index,colector,pagination,pag,func) =>{
	let html = [];
    actualIndex = index; 
	 
    if (colector.length !== 0) {
        html = colector[index].map(element => `<div class="mt-1 px-1 tarjeta c-pointer" data-id="${element.id}">
															<div class="card mb-0 br-04" style="height:auto;">
																<div class="card-header p-2 d-block b-none">
																	
																	<div class="my-0 pt-0 subtitle fs-13">
																	<span class="card-title fs-14 mr-1">${element.id}</span>
																	${element.titulo}
																	</div> 
																</div> 
															</div>
														</div>`);
    } 
	
    $(`#${capa}`).html(html.join('')); 
    agregarPagination(colector,actualIndex,`${pagination}`,`${pag}`,`${func}`);
} 
 
$("#itemsverdes").delegate(".tarjeta", "dblclick", function(el){
	let idincidentes = $(this).attr("data-id");  
	window.open(`correctivo.php?id=${idincidentes}`,'_blank') 
}); 
$("#itemsamarillos").delegate(".tarjeta", "dblclick", function(el){
	let idincidentes = $(this).attr("data-id"); 
	window.open(`correctivo.php?id=${idincidentes}`,'_blank') 
}); 
$("#itemsrojos").delegate(".tarjeta", "dblclick", function(el){
	let idincidentes = $(this).attr("data-id"); 
	window.open(`correctivo.php?id=${idincidentes}`,'_blank') 
}); 
cargarModulo();
var intervalID = window.setInterval(cargarModulo, 300000); 

$("#icono-refrescar").on("click",function(){ 
	cargarModulo();
});



