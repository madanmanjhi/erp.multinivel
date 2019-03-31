<?php $ci = &get_instance();
$ci->load->model("bo/model_admin");
$empresa=$ci->model_admin->val_empresa_multinivel();
$logo = $ci->general->issetVar($empresa,"logo","/logo.png");
$nombre= $ci->general->issetVar($empresa,"nombre","logo");
$style=$ci->general->get_style(1);
$style = array(
    $ci->general->issetVar($style,"bg_color","#FAFAFA"),
    $ci->general->issetVar($style,"btn_1_color","#000000"),
    $ci->general->issetVar($style,"btn_2_color","#C0C0C0")
);
?>

<style>
    .navbar-default .navbar-nav>li>a{
        color: <?=$style[1]?>;
    }
    .navbar-default .navbar-nav>li>a:focus,
    .navbar-default .navbar-nav>li>a:hover{
        color: <?=$style[2]?>;
        text-shadow: 1px 2px 0px <?=$style[1]?>;
    }
    .navbar-default .navbar-nav>.active>a {
        color: <?=$style[2]?>;
        background-color: <?=$style[1]?>;
    }
    .navbar-default .navbar-nav>.active>a:focus,
    .navbar-default .navbar-nav>.active>a:hover {
        color: <?=$style[0]?>;
        background-color: <?=$style[2]?>;
    }
</style>
<header style="height: 60px;background-color: <?=$style[0]?>;
        border:1px solid <?=$style[1]?>;">
    <article class="col-sm-12" style="z-index: 1000;">

        <div class="navbar navbar-default"
             style="background-color: <?=$style[0]?>;border:none">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img style="height: 6em; width: auto; padding: 1rem;" src="<?=$logo?>" alt="<?=$nombre?>">
            </div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="/"><i style="font-size: 2rem;" class="fa fa-home "></i> Inicio</a>
							</li>
							<li class="">
								<a href="/bo/configuracion/">
								<i style="font-size: 2rem;" class="fa fa-wrench "></i> Configuración </a>
							</li>
							<li class="">
								<a href="/bo/comercial/">
								<i style="font-size: 2rem;" class="fa fa-money "></i> Comercial </a>
							</li>
							<li class="">
								<a href="/bo/logistico/">
								<i style="font-size: 2rem;" class="fa fa-cubes "></i> Logistico </a>
							</li>
							<li class="">
								<a href="/bo/administracion/">
								<i style="font-size: 2rem;" class="fa fa-folder-open "></i> Administrativo </a>
							</li>
							<li class="">
								<a href="/bo/oficinaVirtual/">
								<i style="font-size: 2rem;" class="fa fa-desktop "></i> Oficina Virtual </a>
							</li>
							<li class="">
								<a href="/bo/reportes">
								<i style="font-size: 2rem;" class="fa fa-book "></i> Reportes </a>
							</li>

						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li style="margin-right: 2rem; margin-top: 0.3rem;" class="">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="/template/img/blank.gif" class="flag flag-es" alt="Spanish"><span> Español </span></a>
							</li>
							<li class="dropdown">
							<div id="logout" class="btn-header transparent">
								<span> 
									<a style="width: 6rem !important; height: 4rem;color: rgb(255, 255, 255); background: rgb(206, 53, 44) none repeat scroll 0% 0%;" 
									href="/auth/logout" title="Salir" data-action="userLogout" 
									data-logout-msg="¿ Realmente desea salir ?">
									<i class="fa fa-sign-out fa-2x"></i>
									</a> 
									</span>
							</div>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				
			</div>
		</article>
</header>
