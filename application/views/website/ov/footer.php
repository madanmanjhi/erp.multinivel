<!-- PAGE FOOTER -->

<?php $ci = &get_instance();
$ci->load->model("bo/model_admin");
$ci->load->library('tank_auth');
if ($this->tank_auth->is_logged_in())
    $id=$this->tank_auth->get_user_id();
$empresa=$ci->model_admin->val_empresa_multinivel();
$nombre_empresa = $ci->general->issetVar($empresa,"nombre","NetworkSoft");
$web = $ci->general->issetVar($empresa,"web","/");
$style=$ci->general->get_style($id);
$style = array(
    $ci->general->issetVar($style,"bg_color","#00B4DC"),
    $ci->general->issetVar($style,"btn_1_color","#17222d"),
    $ci->general->issetVar($style,"btn_2_color","#17222d")
);
?>

<div class="page-footer" style="background:<?=$style[1]?>; height: 6rem;margin-bottom: -4rem;">
    <div class="row">
        <div class="col-xs-8 col-sm-8">
					<span class="txt-color-white">
			Copyright © <?=date('Y');?> <?=$nombre_empresa?> Todos los derechos reservados.
		<a href="<?=$web?>" target="_BLANK"><?=$nombre_empresa?></a></span>
				</div>
				<div class="col-xs-4 col-sm-4">
					<span class="txt-color-white">
			
		<a class="txt-color-white" href="/ov/cabecera/sugerencia" target="_self"><i class="fa fa-send fa-2x"></i>&nbsp;Sugerencias</a>
		
		</span>
				</div>
			</div>
		</div>

		<script type="text/javascript">

		$(document).ready(function(){
			bache_ui();
		});	

		
		function bache_ui(){
			
			var pathname = window.location.pathname;
			var ruta = pathname.split("/");

            var rutaElement = ruta[2];
            if(rutaElement=="compras")
				return false;	

			var footer = $( "#page-footer" ).height();			
			var header = $( "header" ).height();
			
			var htm = $( document ).height();
			
			var size = (htm-(header+footer))-footer;

			$( "#content" ).height(size);
			
		}	

		</script>

		<!-- END PAGE FOOTER -->

		<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
		<div id="shortcut">
			<ul>
				<li>
					<a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
				</li>
				<li>
					<a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
				</li>
				<li>
					<a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
				</li>
				<li>
					<a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
				</li>
				<li>
					<a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
				</li>
				<li>
					<a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
				</li>
			</ul>
		</div>
		<!-- END SHORTCUT AREA -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="/template/js/plugin/pace/pace.min.js"></script>

		<!-- spinner lib -->
		<script src="/template/js/spin.js"></script>
		<script src="/template/js/spinner-loader.js"></script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="/template/js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="/template/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

		<!-- BOOTSTRAP JS -->
		<script src="/template/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="/template/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="/template/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="/template/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="/template/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="/template/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="/template/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="/template/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="/template/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="/template/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="/template/js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- Demo purpose only -->
		<script src="/template/js/demo.min.js"></script>

		<!-- MAIN APP JS FILE -->
		<script src="/template/js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="/template/js/speech/voicecommand.min.js"></script>

		<!-- BOOTBOX.MIN.JS-->
		<script src="/template/js/plugin/bootbox/bootbox.min.js"></script>
