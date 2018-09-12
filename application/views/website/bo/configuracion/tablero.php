
<!-- MAIN CONTENT -->
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
					<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a> 
				<span>>
					<a href="/bo/configuracion">Configuración</a>
				</span>
				<span>>Tablero</span>
			</h1>
		</div>
	</div>
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">
			<!-- NEW COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
					<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

					-->
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Datos personales</h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						<!-- widget content -->
						<div class="widget-body no-padding">

							<form method="POST" action="/perfil_red/actualizar" id="checkout-form" class="smart-form" novalidate="novalidate">
								<fieldset>
									<legend>Oficina virtual</legend>
									<div class="row">
										<section class="col col-3">
											<label class="input">
												Color de fondo
                                                                                                <input type="color"  value="<?=$style[0]->bg_color?>">
												<input type="text" name="bg_color" value="<?=$style[0]->bg_color?>">
											</label>
										</section>
										<section class="col col-3">
											<label class="input">
												Color de botones primarios
                                                                                                <input type="color"  value="<?=$style[0]->btn_1_color?>">
												<input type="text" name="color_1" value="<?=$style[0]->btn_1_color?>">
											</label>
										</section>
										<section class="col col-3">
											<label class="input">
												Color de botones secundarios
                                                                                                <input type="color"  value="<?=$style[0]->btn_2_color?>">                                                                                                
												<input type="text" name="color_2" value="<?=$style[0]->btn_2_color?>">
											</label>
										</section>
									</div>
								</fieldset>
								<footer>
									<button type="button" onclick="actualiza()" class="btn btn-primary">
										Actualizar
									</button>
								</footer>
							</form>

						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
			</article>
			<!-- END COL -->
		</div>
		<div class="row">
	        <!-- a blank row to get started -->
	        <div class="col-sm-12">
	            <br />
	            <br />
	        </div>
        </div>
		<!-- END ROW -->
	</section>
	<!-- end widget grid -->
</div>
<!-- END MAIN CONTENT -->
<!-- PAGE RELATED PLUGIN(S) -->
<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
 
<script type="text/javascript">
$('input[type=color]').addClass("jscolor");
// DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {

	pageSetUp();
        colorPick();
	
});
$('input[type=text]').keyup(colorPick);
function colorPick(){
    $('input[type=text]').each(function(){
            $(this).prev().val($(this).val());
        });
}
$('input[type=color]').change(revPick);
function revPick(){
    $('input[type=color]').each(function(){
            $(this).next().val($(this).val());
        });
}
function actualiza()
{
	$.ajax({
		type: "POST",
		url: "/ov/cabecera/actualizar",
		data: $('#checkout-form').serialize()
	})
	.done(function( msg ) {
		location.href='';
	});
}
</script>
















