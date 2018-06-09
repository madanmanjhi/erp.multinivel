[[$HEAD]]

		<!-- #CSS Links -->
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/smartadmin-skins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/styles_form.css">
		<!-- SmartAdmin RTL Support is under construction
			 This RTL CSS will be released in version 1.5
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/smartadmin-rtl.min.css"> -->

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/demo.min.css">

		<!--Estilos de los autores de la pagina-->
		<link rel="stylesheet" type="text/css" media="screen" href="../form_template/css/your_style.css">


		<!-- #FAVICONS -->
		<link rel="shortcut icon" href="../form_template/img/favicon/favicon.png" type="image/x-icon">
		<link rel="icon" href="../form_template/img/favicon/favicon.png" type="image/x-icon">

		<!-- #GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- #APP SCREEN / ICONS -->
		<!-- Specifying a Webpage Icon for Web Clip
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="../form_template/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../form_template/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../form_template/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../form_template/img/splash/touch-icon-ipad-retina.png">

		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="../form_template/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="../form_template/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="../form_template/img/splash/iphone.png" media="screen and (max-device-width: 320px)">
		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="../form_template/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="../form_template/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script> 

        <!-- Main content -->
        <div class="content" 
        		style="background-image: url('[[$FONDO]]');
        			   background-attachment: fixed;
        			   background-size: 100% auto;" >
            <div>
                <!-- Header -->
                <div class="row">
                	<div class="header col-sm-4"></div>
                    <div class="header col-sm-4">[[$MARCA]]</div>
                </div>
                <!-- /Header -->

                <!-- Description -->
                <div class="row">
                	<div class="header col-sm-4"></div>
                    <h4 style="text-align: center;text-shadow: 1px 1px 1px #fff;" 
                    		class="description col-sm-4">
                    	 [[$SUMARIO]] </h4>
                </div>
                <!-- /Description -->
            </div>
            
            <div class="container">
                <div class="row">
                    <div class="form-and-links-container col-sm-8 col-sm-offset-2">

						
                        <!-- Subscription form -->
                        <div class="row">

								 [[$FORM]]
                        
                        </div>
                        <!-- /Subscription form -->

                    </div>
                </div>
            </div>
        </div>
        <!-- /Main content -->
[[$CUSTOM]]
<script>
	var id = 0;

	function agregar(tipo) {
		if (tipo == 1) {
			$("#tel")
					.append(
							"<section id='tel_"+id+"' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono("
									+ id
									+ ")'>Eliminar <i class='fa fa-minus'></i></a></section>");
		} else {
			$("#tel")
					.append(
							"<section id='tel_"+id+"' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono("
									+ id
									+ ")'>Eliminar <i class='fa fa-minus'></i></a></section>");
		}

		id++;
	}
	function delete_telefono(id) {
		$("#tel_" + id + "").remove();
	}
</script>

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!

	$(document).ready( function() {
					// Para local /controller.php",data:{fnct : "crear_user
					// Produccion /auth/register
					// fuelux

					var wizard = $('.wizard').wizard();

					wizard .on( 'finished', function(e, data) {

										$(".invalid").remove();

										var ids = new Array(
												"#nombre",
												"#apellido", 
												"#datepicker",
												"#keyword",
												"#username",
												"#email", 
												"#password",
												"#confirm_password"

										);
										var mensajes = new Array(
												"Por favor ingresa tu nombre",
												"Por favor ingresa tu apellido",
												"Por favor ingresa tu fecha de nacimiento",
												"Por favor ingresa tu DNI",
												"Por favor ingresa un nombre de usuario",
												"Por favor ingresa un correo",
												"Por favor ingresa una contraseña",
												"Por favor confirma tu contraseña");

										var idss = new Array("#username");
										var mensajess = new Array(
												"El nombre de usuario no puede contener espacios en blanco");
										var validacion_ = valida_espacios(idss,
												mensajess);
										var validacion = valida_vacios(ids,
												mensajes);
										if (validacion && validacion_) {
											iniciarSpinner();
											$(".steps").slideUp();
											$(".steps").remove();
											$(".actions").slideUp();
											$(".actions").remove();
											$("#myWizard") .append( '<div class="progress progress-sm progress-striped active"><div id="progress" class="progress-bar bg-color-darken"  role="progressbar" style=""></div></div>');

											$ .ajax(
															{
																type : "POST",
																url : "/afiliar.php",
																data : $( '#nuevo_afiliado').serialize()
															})
													.done(
															function(msg) {

																$("#progress") .attr( 'style', 'width: 100%');
																bootbox .dialog({
																			message : msg,
																			title : "Atención",
																			buttons : {
																				success : {
																					label : "Ok!",
																					className : "btn-success",
																					callback : function() {
																						location.href = "/";
																						FinalizarSpinner();
																					}
																				}
																			}
																		});
															});
										} else {
											$ .smallBox({
														title : "<h1>Atención</h1>",
														content : "<h3>Por favor revisa que todos los datos estén correctos</h3>",
														color : "#C46A69",
														icon : "fa fa-warning fadeInLeft animated",
														timeout : 4000
													});
										}

									});

					$('#pais').val("MEX");

					pageSetUp();
});

$("#remove_step").click(function() {
	$("#tipo_plan").attr("name", "tipo_plan");
	$('.wizard').wizard('selectedItem', {
		step : 4
	});
	$("#step4").slideUp();
	$("#step4").remove();
	$("#paso4").slideUp();
	$("#paso4").remove();
	$(this).slideUp();
	$(this).remove();
});

$("#plan1").click(function(event) {
	$("#tipo_plan").attr("value", "1");
	$("#planuno").addClass('packselected');
	$("#plandos").removeClass('packselected');
	$("#plantres").removeClass('packselected');
	$("#plancuatro").removeClass('packselected');
});

$("#plan2").click(function(event) {
	$("#tipo_plan").attr("value", "2");
	$("#planuno").removeClass('packselected');
	$("#plandos").addClass('packselected');
	$("#plantres").removeClass('packselected');
	$("#plancuatro").removeClass('packselected');
});
$("#plan3").click(function(event) {
	$("#tipo_plan").attr("value", "3");
	$("#planuno").removeClass('packselected');
	$("#plandos").removeClass('packselected');
	$("#plantres").addClass('packselected');
	$("#plancuatro").removeClass('packselected');
});
$("#plan4").click(function(event) {
	$("#tipo_plan").attr("value", "4");
	$("#planuno").removeClass('packselected');
	$("#plandos").removeClass('packselected');
	$("#plantres").removeClass('packselected');
	$("#plancuatro").addClass('packselected');
});

/*
 * CODIGO PARA QUITAR ELEMENTO HACIENDO CLICK EN ELLOS
 * $("input").click(function() { $( this ).slideUp(); $( this ).remove(); });
 */
function codpos() {

}
function clickme() {
}

function SelecionarFase() {

	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "MensajeFase",
			id : 4,
			red : 1
		}
	}).done(function(msg) {
		bootbox.dialog({
			message : msg,
			title : "Informacion Personal",
			buttons : {
				success : {
					label : "Cerrar!",
					className : "hide",
					callback : function() {
						// location.href="";
					}
				}
			}
		});
	});
}

function faseCambio(fase) {

	bootbox.dialog({
		message : "¿Estas Seguro?",
		title : "Atención",
		buttons : {
			success : {
				label : "Si",
				className : "btn-success",
				callback : function() {

					$.ajax({
						type : "POST",
						url : "/controller.php",
						data : {
							fnct : "CambioFase",
							id : 4,
							red : 1,
							fase : fase
						},
					}).done(function(msg) {
						alert('Has Cambiado de fase' + msg);
						location.reload();
					})
				}
			},
			close : {
				label : "NO",
				className : "btn-danger",
				callback : function() {

				}
			}
		}
	});

}

function use_username() {
	$('#username').val($('#username').val().replace(" ", ""));
	var username = $("#username").val();
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_username",
			username : username
		},
	}).done(function(msg) {
		$("#msg_usuario").remove();
		$("#usuario").append("<div id='msg_usuario'>" + msg + "</div>")
	});
	validate_user_data();
}
function use_mail() {
	var mail = $("#email").val();
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_mail",
			mail : mail
		},
	}).done(function(msg) {
		$("#msg_correo").remove();
		$("#correo").append("<div id='msg_correo'>" + msg + "</div>")
	});
	validate_user_data()
}

function confirm_pass() {
	var password = $("#password").val();
	var confirm_password = $("#confirm_password").val();
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "confirm_password",
			password : password,
			confirm_password : confirm_password
		},
	}).done(
			function(msg) {
				$("#msg_confirm_password").remove();
				$("#confirmar_password").append(
						"<div id='msg_confirm_password'>" + msg + "</div>")
			});
	validate_user_data()
}

function validate_user_data() {
	var username = $("#username").val();
	var mail = $("#email").val();

	var password = $("#password").val();
	var confirm_password = $("#confirm_password").val();

	$("#validate_user_data").remove();

	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "validate_user_data",
			mail : mail,
			username : username,
			password : password,
			confirm_password : confirm_password
		},
	}).done(function(msg) {
		$("#validate_user_data").remove();
		$("#register").append("<div id='validate_user_data'>" + msg + "</div>")
	});
}

function otra() {
	if ($("#otro:checked").val() == "on") {
		$("#b_persona").removeClass("hidden");
		$("#afiliado_value").attr("name", "afiliados");
	} else {
		$("#b_persona").addClass("hidden");
		$("#afiliado_value").attr("name", "");
	}
}

function agregar_red(tipo) {
	if (tipo == 1) {
		$("#tel_red")
				.append(
						"<section class='col col-6'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	} else {
		$("#tel_red")
				.append(
						"<section class='col col-6'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	}
}
$(function() {
	a = new Date();
	año = a.getFullYear() - 18;
	$("#datepicker").datepicker({
		changeMonth : true,
		numberOfMonths : 2,
		maxDate : año + "-12-31",
		dateFormat : "yy-mm-dd",
		changeYear : true,
		yearRange : "-99:+0",
	});
});

function subred(id) {
	$("#" + id).children(".quitar").attr('onclick', '');
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "get_red_afiliar",
			id : id,
			red : 1
		},
	}).done(function(msg) {
		$("#" + id).append(msg);
	});
}

function check_keyword() {
	$("#msg_key").remove();
	$("#key_").append(
			'<i id="ajax_" class="icon-append fa fa-spinner fa-spin"></i>');

	var keyword = $("#keyword").val();
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_keyword",
			keyword : keyword
		},
	}).done(function(msg) {
		$("#msg_key").remove();
		$("#key").append("<p id='msg_key'>" + msg + "</msg>");
		$("#ajax_").remove();
	});
}
function check_keyword_co() {
	$("#msg_key_co").remove();
	$("#key_1").append(
			'<i id="ajax_1" class="icon-append fa fa-spinner fa-spin"></i>');
	var keyword = $("#keyword_co").val();
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_keyword",
			keyword : keyword
		},
	}).done(function(msg) {
		$("#msg_key_co").remove();
		$("#key_co").append("<p id='msg_key_co'>" + msg + "</msg>");
		$("#ajax_1").remove();
	});
}
function check_keyword_red() {
	$("#msg_key_red").remove();
	var keyword = $("#keyword_red").val();
	$("#key_2").append(
			'<i id="ajax_2" class="icon-append fa fa-spinner fa-spin"></i>');
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_keyword",
			keyword : keyword
		},
	}).done(function(msg) {
		$("#key_red").append("<p id='msg_key_red'>" + msg + "</msg>");
		$("#ajax_2").remove();
	});
}
function check_keyword_red_co() {
	$("#msg_key_red_co").remove();
	var keyword = $("#keyword_red_co").val();
	$("#key_3").append(
			'<i id="ajax_3" class="icon-append fa fa-spinner fa-spin"></i>');
	$.ajax({
		type : "POST",
		url : "/controller.php",
		data : {
			fnct : "use_keyword",
			keyword : keyword
		},
	}).done(function(msg) {
		$("#msg_key_red_co").remove();
		$("#key_red_co").append("<p id='msg_key_red_co'>" + msg + "</msg>");
		$("#ajax_3").remove();
	});
}
function codpos_red() {
	var pais = $("#pais_red").val();
	if (pais == "MEX") {
		var cp = $("#cp_red").val();
		$.ajax({
			type : "POST",
			url : "/controller.php",
			data : {
				fnct : "cp_red",
				cp : cp
			},
		}).done(function(msg) {
			$("#colonia_red").remove();
			$("#municipio_red").remove();
			$("#estado_red").remove();
			$("#dir_red").append(msg);
		})
	}
}
</script>
<script type="text/javascript">

		bache_ui();
		function bache_ui(){
			
			var pathname = window.location.pathname;
			var ruta = pathname.split("/");
			
			if(ruta[2]=="compras")
				return false;	

			var footer = $( "#page-footer" ).height();			
			var header = $( "header" ).height();
			
			var htm = $( document ).height();
			
			var size = (htm-(header+footer))-footer;

			$( "#content" ).height(size);
			
		}	

		</script>
 

		<script src="../form_template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="../form_template/js/validacion.js"></script>
<script src="../form_template/js/plugin/fuelux/wizard/wizard.min.js"></script>

		

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="../form_template/js/plugin/pace/pace.min.js"></script>

		<!-- spinner lib -->
		<script src="../form_template/js/spin.js"></script>
		<script src="../form_template/js/spinner-loader.js"></script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="../form_template/js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="../form_template/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

		<!-- BOOTSTRAP JS -->
		<script src="../form_template/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="../form_template/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="../form_template/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="../form_template/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="../form_template/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="../form_template/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="../form_template/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="../form_template/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="../form_template/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="../form_template/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="../form_template/js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- Demo purpose only -->
		<script src="../form_template/js/demo.min.js"></script>

		<!-- MAIN APP JS FILE -->
		<script src="../form_template/js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="../form_template/js/speech/voicecommand.min.js"></script>

		<!-- BOOTBOX.MIN.JS-->
		<script src="../form_template/js/plugin/bootbox/bootbox.min.js"></script>
		
[[$FOOTER]]
    