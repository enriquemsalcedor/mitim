(function($) {
	
	var direction =  getUrlParams('dir');
	if(direction != 'rtl')
	{direction = 'ltr'; }
	//inicia el menu compacto
    /*if (document.title!=='') {
        console.log("paso por aqui");
        //$(".hamburger").addClass('is-active');
        //$("#main-wrapper").addClass('menu-toggle');
        $('#preloader').css('display','block');
    }*/
    
    if(document.title==="SYM | Correctivos"){
       //document.getElementById("myBtn").style.height = "50px"; 
        const heightOutput = document.querySelector('#DZ_W_Filtros_Body');
        //const widthOutput = document.querySelector('#width');
        function resize() {
          let alto=window.innerHeight
          console.log(alto)
          /*if(alto<=300){
              console.log("tamaño--")
             document.querySelector("#DZ_W_Filtros_Body").style.height = "360px";  
          }else if(alto<=430){
              console.log("tamaño")
             document.querySelector("#DZ_W_Filtros_Body").style.height = "450px";  
          }*/
          
        }
        
        window.onresize = resize;   
    }

	var dezSettingsOptions = {
		typography: "poppins", //Todas las opciones => ["poppins" , "roboto" , "Open Sans" , "Helventivca" ]
		version: "light", //Todas las opciones => ["light" , "dark"]
		layout: "vertical", //Todas las opciones => ["horizontal" , "vertical"]
		headerBg: "color_1", //Todas las opciones => ["color_1," , "color_2," ..... "color_15"]
		navheaderBg: "color_1", //Todas las opciones => ["color_1," , "color_2," ..... "color_15"]
		sidebarBg: "color_1", //Todas las opciones => ["color_1," , "color_2," ..... "color_15"]
		sidebarStyle: "compact", //Todas las opciones => ["full" , "mini" , "compact" , "modern" , "overlay" , "icon-hover"]
		sidebarPosition: "fixed", //Todas las opciones => ["static" , "fixed"]
		headerPosition: "fixed", //Todas las opciones => ["static" , "fixed"]
		containerLayout: "full", //Todas las opciones => ["full" , "wide" , "wide-box"]
		direction: direction //Todas las opciones => ["ltr" , "rtl"]
	};
		
		//Ocultar total sidebarStyle: overlay
		
	new dezSettings(dezSettingsOptions); 

	jQuery(window).on('resize',function(){
		new dezSettings(dezSettingsOptions); 
	});

})(jQuery);


