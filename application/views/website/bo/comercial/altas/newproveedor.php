
<!-- MAIN CONTENT -->
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
			
			<?php  if($type=='5'){?>
						<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
							<span>
							> <a href="/bo/logistico2/alta">Alta</a>
							> <a href="/bo/comercial/actionProveedor">Proveedor </a>
							> Alta
							</span>
		   <?php } else if($type=='4'){?>		
						<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
							
							<span>
							> <a class="" href="/bo/comercial/altas/"><i class=""></i> Comercial</a>
							> <a class="" href="/bo/comercial/actionProveedor/"><i class=""></i> Proveedor</a>
							> Alta
							</span>
					
			<?php }else if($type=='8'||$type=='9'){
						 	$index= ($type=='8') ? '/CEDI' : '/Almacen';
						 	?>
							<a class="backHome" href="<?=$index?>"><i class="fa fa-home"></i> Menu</a>
							<span> 
								> <a href="<?=$index?>/altas"> Altas</a>
								> <a href="/bo/comercial/actionProveedor">Proveedor </a>
								> Alta
							</span>						
			<?php }else{?>
				      <a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
							<span>
							 	 > <a href="/bol/"> Logistico </a>
								 > <a class="" href="/bo/logistico2/alta/"><i class=""></i> Alta</a>
								 > <a href="/bo/comercial/actionProveedor">Proveedor </a>
								 > Alta
							</span>
			<?php }?>	
						
							</h1>
		</div>
	</div>
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">

			<!-- NEW COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1"
					data-widget-editbutton="false" data-widget-custombutton="false"
					data-widget-colorbutton="false">
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
						<span class="widget-icon"> <i class="fa fa-edit"></i>
						</span>
						<h2>Nuevo Proveedor</h2>
					</header>

					<!-- widget div-->
					<div>
						<form method="POST" id="proveedor" class="smart-form" >
							<fieldset>
								<legend>Datos personales del proveedor</legend>
								<div class="row">
									<section class="col col-3">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input required type="text" name="nombre" id="nombre"
											placeholder="Nombre">
										</label>
									</section>
									<section class="col col-3">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input required type="text" name="apellido" id="apellido"
											placeholder="Apellido">
										</label>
									</section>
									<section id="correo1" class="col col-3">
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input id="email" required type="email"
											name="email" placeholder="Email">
										</label>
									</section>
								</div>
								<div class="row">
									<div id="tel1" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<section class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-phone"></i>
												<input required name="fijo[]" placeholder="(99) 99-99-99-99"
												type="tel" pattern="[0-9]{7,50}" title="Por favor digite un numero de telefono valido">
											</label>
										</section>
										<section class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-mobile"></i>
												<input required name="movil[]"
			