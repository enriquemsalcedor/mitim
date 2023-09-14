<?php 
	include_once "funciones.php"; 
	$opcion  = (!empty($_REQUEST['opcion']) ? $_REQUEST['opcion'] : '');
	if ($opcion=='LO') {
		cerrarSesion();
	}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="images/favicon.png" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<title>Maxia Toolkit | SYM</title>

    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
	    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">

	<link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="./css/style6.css" rel="stylesheet">
	<link href="./css/ajustes1.css" rel="stylesheet">

    <!--
	    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
	    <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
	    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	    <link href="./css/style1.css" rel="stylesheet">
	    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
	    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	    <link href="./css/ajustes.css" rel="stylesheet"-->

	


    <style>
        .input-group-text{
            border-radius: 0.2rem;
        }        
        .alert{
            margin:0px !important;
            padding:0px !important;
        }
        
        input{
            border-radius: 0.2rem !important;
                        
        }
        
        .form-control::placeholder {
        	color: #6c757d8f;
        	opacity: 1;
        }
        
        

/*
        ::-webkit-input-placeholder { color: red; } 
        
        :-moz-placeholder { color: red; } 
        
        ::-moz-placeholder {  color: red; } 
        
        :-ms-input-placeholder { color: red; }        
*/

    </style>

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="profile-photo text-center mb-3">
										<img src="images/loginmitim.jpeg" width="50%" height="50%" class="img-fluid" alt="" style="max-width: 110px; border-radius: 50%;">
									</div> 
                                    <h4 class="text-center mb-1 text-success">Inicie sesión en su cuenta</h4> 
        							<div id="error"></div>

                                    <form role="form" action="#" id="frmAcceso" name="frmAcceso" method="POST" autocomplete="off">
                                        <div class="form-group">
											<label class="control-label" for="val-username">
    											    Usuario <span class="text-red">*</span>
											</label>

                                            <div class="input-group primary">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text "> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control text" id="val-username" name="val-username" placeholder="Usuario" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                                            </div>




                                        </div>

                                        <div class="form-group mb-4">
											<label class="control-label" for="val-password">Clave <span class="text-red">*</span></label>


                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div>
                                                <input type="password" class="form-control text" id="val-password" name="val-password" placeholder="Contraseña" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                                                <div class="input-group-append">
										            <button
										                style="height:40px"
														title="Mostrar contraseña"
														id="showPassword" class="btn btn-sm input-group-text" type="button">
                                                            <span class="fa fa-eye" id="icon">
                                                            </span>
										                     
										            </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center ">
                                            <button type="submit" class="btn btn-primary btn-block " id="submit">Iniciar sesión</button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
<script type="text/javascript" src="scripts/plugins.js"></script>
<script src="./vendor/jquery-validation/jquery.validate.min.js"></script>
<script>


        jQuery("#frmAcceso").validate({
            rules: {
                "val-username": {
                    required: !0,
                    minlength: 3
                },
                "val-password": {
                    required: !0,
                    minlength: 5
                }
            },
            messages: {
                "val-username": {
                    required: "Por favor introduzca su usuario.",
                    minlength: "El usuario debe tener al menos 3 caracteres."
                },
                "val-password": {
                    required: "Por favor introduzca su contaseña.",
                    minlength: "La contraseña debe tener al menos 5 caracteres."
                }
            },
        
            ignore: [],
            errorClass: "invalid-feedback animated fadeInUp",
            errorElement: "div",
            errorPlacement: function(e, a) {
                jQuery(a).parents(".form-group > div").append(e)
            },
            highlight: function(e) {
                jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
            },
            success: function(e) {
                jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-valid")
/*                $('submit').on('click',function(){
                    alert("Submit")    
                        
                    
                })*/
            }
        
        
        
        
        });


$(function(){
	$('#showPassword').click(function(){
		let tipo_input  = document.getElementById('val-password').type
		let cambio = "password";
		let tool = "Mostrar";
		if(tipo_input== cambio){
			cambio = "text"
			$('#icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
			tool = "Ocultar";

		}else{
			$('#icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
		}
		document.getElementById('val-password').type = cambio
		document.getElementById('showPassword').title = tool+' contraseña'
	});

    
    
    $('#frmAcceso').submit(function(e){

        //e.preventDefault()
    	var txtUsuario 	= $("#val-username").val();
    	var txtClave 	= $("#val-password").val();
		var pageURL 	= $(location). attr("href");
		var arrUrl		= pageURL.split("/");
		var sistema		= arrUrl[3];

        

		$.ajax({
			url:'login.php',
			type : 'POST',
			dataType: 'json',
//			data: {txtUsuario: txtUsuario, txtClave: txtClave},
			data: {txtUsuario: txtUsuario, txtClave: txtClave, sistema: sistema},
			success: function(data){
				if ( data.error === true ) {
					$('#error').css({"display": "block", "color": "#ff0000", "font-weight": "500", "text-align": "center"});
					var error = document.getElementById('error');
					error.innerHTML = data.msg;
					setTimeout(function() {
						$('#error').fadeOut(500);
					},3000);
				
					setTimeout(function(){
						window.scrollTo(0, 1);
					}, 100);
				}else {
					localStorage.setItem("user",txtUsuario);
					var today = new Date();
                    var dd = ("0" + today.getDate()).slice(-2)
                    var mm = today.getMonth() + 1; //January is 0!
                    var yyyy = today.getFullYear();
                    localStorage.setItem("fechaconsulta",`${yyyy}-${mm}-${dd}`);
					location.href = data.msg;
					return false;
				}
			},
			error: function(data){	
				console.log(data);
			}
		});
		return false;
	}); 
});
</script>
</body>

</html>