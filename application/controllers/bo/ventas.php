<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ventas extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('bo/modelo_dashboard');
		$this->load->model('ov/general');
		$this->load->model('bo/modelo_comercial');
		$this->load->model('ov/model_perfil_red');
		$this->load->model('model_tipo_red');
		$this->load->model('model_cat_tipo_usuario');
		$this->load->model('model_cat_sexo');
		$this->load->model('model_cat_edo_civil');
		$this->load->model('model_cat_estudios');
		$this->load->model('model_cat_ocupacion');
		$this->load->model('model_cat_estatus_afiliado');
		$this->load->model('model_cat_tiempo_dedicado');
		$this->load->model('model_cat_usuario_fiscal');
		$this->load->model('model_users');
		$this->load->model('model_user_profiles');
		$this->load->model('model_coaplicante');
		$this->load->model('bo/model_admin');
		$this->load->model('bo/model_mercancia');
		$this->load->model('ov/modelo_compras');
		$this->load->model('modelo_cobros');
		$this->load->model('model_excel');
		$this->load->model('cemail');
		$this->load->model('model_servicio');
	}

	function index(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		if(!$this->general->isAValidUser($id,"administracion"))
		{
			redirect('/auth/logout');
		}

		$usuario=$this->general->get_username($id);
		
		$style=$this->modelo_dashboard->get_style(1);
	
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/administracion/ventas/listar');
	}
	
	function reporte_ventas_oficinas_virtuales()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
	
		if(!$this->general->isAValidUser($id,"comercial"))
		{
			redirect('/auth/logout');
		}
	
	
		$total_venta = 0;
		$total_costo = 0;
		$total_impuesto = 0;
		$total_comision = 0;
		$total_neto = 0;
	
	
		$ventas = $this->model_servicio->listar_todos_por_venta_y_fecha($_POST['startdate'],$_POST['finishdate']);
	
		$id=$this->tank_auth->get_user_id();
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID Venta</th>
					<th data-hide='phone,tablet'>Username</th>
					<th data-hide='phone,tablet'>Nombre</th>
					<th data-hide='phone,tablet'>Apellido</th>
					<th data-hide='phone,tablet'>Subtotal</th>
					<th data-hide='phone,tablet'>Impuestos</th>
					<th data-hide='phone,tablet'>Total Venta</th>
					<th data-hide='phone,tablet'>Total Comisiones</th>
					<th data-hide='phone,tablet'>Total Neto</th>
					<th data-hide='phone,tablet'>Accion</th>
				</thead>
				<tbody>";
	
		if ($_POST['startdate']!=""){
			foreach($ventas as $venta)
			{
				echo "<tr>
			<td class='sorting_1'>".$venta->id_venta."</td>
			<td>".$venta->username."</td>
			<td>".$venta->name."</td>
			<td>".$venta->lastname."</td>
			<td> $	".number_format(($venta->costo-$venta->impuestos), 2, '.', '')."</td>
			<td> $	".number_format($venta->impuestos, 2, '.', '')."</td>
			<td> $	".number_format($venta->costo, 2, '.', '')."</td>
			<td> $	".number_format($venta->comision, 2, '.', '')."</td>
			<td> $	".number_format((($venta->costo)-($venta->impuestos+$venta->comision)), 2, '.', '')."</td>
			<td>
				<a title='Factura' style='cursor: pointer;' class='txt-color-blue' onclick='factura(".$venta->id_venta.");'>
				<i class='fa fa-eye fa-3x'></i>
				</a>
				<a title='Eliminar' style='cursor: pointer;' class='txt-color-red' onclick='eliminar(".$venta->id_venta.");'>
				<i class='fa fa-trash-o fa-3x'></i>
				</a>
			</td>
			</tr>";
					
				$total_costo = $total_costo + ($venta->costo-$venta->impuestos);
				$total_impuesto = $total_impuesto + $venta->impuestos;
				$total_venta = $total_venta  + $venta->costo;
				$total_comision = $total_comision + $venta->comision;
				$total_neto = $total_neto + (($venta->costo)-($venta->impuestos+$venta->comision));
					
			}
	
			echo "<tr>
			<td class='sorting_1'></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
				
			echo "<tr>
			<td class='sorting_1'><b>TOTALES</b></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b> $	".number_format($total_costo, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_impuesto, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_venta, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_comision, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_neto, 2, '.', '')."</b></td>
			<td></td>
			</tr>";
		}
		echo "</tbody>
		</table><tr class='odd' role='row'>";
	}
	
	function kill_venta()
	{
		$this->model_admin->kill_venta($_POST['id']);
		echo "Se ha eliminado la venta y sus comisiones.";
	}
	
	function factura(){


		$id = $this->modelo_compras->get_datos_venta($_POST['id']);
		$fecha=$id[0]->fecha;
		$id=$id[0]->id_user;

		$datos_afiliado = $this->model_perfil_red->datos_perfil($id);
		$this->template->set("datos_afiliado",$datos_afiliado);
		
		$mercanciaFactura = $this->modelo_compras->get_mercancia_venta($_POST['id']);
		
		$data=array();
		$contador=0;
		$info_compras=Array();
		
		foreach ($mercanciaFactura as $items)
		{
		
			$imagenes=$this->modelo_compras->get_imagenes($items->id_mercancia);
			$id_tipo_mercancia=$this->modelo_compras->get_tipo_mercancia($items->id_mercancia);
			$id_tipo_mercancia=$id_tipo_mercancia[0]->id_tipo_mercancia;
			if($id_tipo_mercancia==1)
				$detalles=$this->modelo_compras->detalles_productos($items->id_mercancia);
			else if($id_tipo_mercancia==2)
				$detalles=$this->modelo_compras->detalles_servicios($items->id_mercancia);
			else if($id_tipo_mercancia==3)
				$detalles=$this->modelo_compras->detalles_combinados($items->id_mercancia);
			else if($id_tipo_mercancia==4)
				$detalles=$this->modelo_compras->detalles_paquete($items->id_mercancia);
			else if($id_tipo_mercancia==5)
				$detalles=$this->modelo_compras->detalles_membresia($items->id_mercancia);

			$info_mercancia[$contador]=Array(
					"imagen" => $imagenes[0]->url,
					"nombre" => $detalles[0]->nombre,
					"descripcion" => $detalles[0]->descripcion
			);
			$contador++;
		
		}

		$this->template->set("mercanciaFactura",$mercanciaFactura);
		$this->template->set("info_mercancia",$info_mercancia);
		
		$pais = $this->general->get_pais($id);
		$this->template->set("pais_afiliado",$pais);
		
		$empresa  = $this->model_admin->val_empresa_multinivel();
		$this->template->set("empresa",$empresa);

		$this->template->set("fecha",$fecha);
		
		$this->template->set_theme('desktop');
		$this->template->build('website/bo/administracion/ventas/factura');
	}
}