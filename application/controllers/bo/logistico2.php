<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class logistico2 extends CI_Controller
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
		$this->load->model('bo/general');
		$this->load->model('general');
		$this->load->model('bo/modelo_logistico');
		$this->load->model('bo/modelo_proveedor_mensajeria');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$Logistico = $this->general->isAValidUser($id,"logistica");
	
		if(!$Comercial&&!$Logistico){
			redirect('/auth/logout');
		}
		
		$style=$this->modelo_dashboard->get_style(1);

		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/index');
	}
	
	function pedidos(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
			
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
		$style=$this->modelo_dashboard->get_style(1);
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set("type",$usuario[0]->id_tipo_usuario);
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/embarque/index');
	}

	function pedidos_pendientes(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
	
		$style=$this->modelo_dashboard->get_style(1);
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		
		/*$surtido_movimiento =$this->modelo_logistico->get_surtidos();
		$surtidos = array();
		
		foreach ($surtido_movimiento as $movimiento){
			
			$mercancia = $this->modelo_logistico->ObtenerMercancia($movimiento->id_mercancia);
			if(isset($mercancia[0]->nombre)){
				$surtidos[] = array(
						'id_surtido' => $movimiento->id_surtido,
						'id_venta' => $movimiento->id_venta,
						'keyword' => $movimiento->keyword,
						'mercancia' => $mercancia[0]->nombre,
						'cantidad'  => $movimiento->cantidad,
						'origen' => $movimiento->origen,
						'usuario' => $movimiento->destino,
						'email'	=> $movimiento->correo,
						'fecha' => $movimiento->fecha,
						'estatus' => $movimiento->estatus_e
				);
			}
		}*/
		
		$surtidos =$this->modelo_logistico->get_surtidos();
		
		$this->template->set("style",$style);
		$this->template->set("surtidos",$surtidos);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/embarque/pedidos_pendientes');
	}
	function surtir()
	{
		$this->modelo_logistico->surtir();	
	}
	
	function reporte_embarcados(){
		
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$fecha_ini=str_replace('.', '-', $data['inicio']);
		$fecha_fin=str_replace('.', '-', $data['fin']);
		$ano_ini=substr($fecha_ini, 6);
		$mes_ini=substr($fecha_ini, 3,2);
		$dia_ini=substr($fecha_ini, 0,2);
		$ano_fin=substr($fecha_fin, 6);
		$mes_fin=substr($fecha_fin, 3,2);
		$dia_fin=substr($fecha_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini;
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin;
		
		$surtidos = $this->modelo_logistico->get_embarcados($data['inicio'], $data['fin']);
		
		echo "<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' style='width: 100%;'>
							<thead id='tablacabeza' style='width: 90%;'>
								<tr style='width: 100%;'>
									<th data-class='expand'>ID</th>
									<th data-hide='phone'>No. guia</th>
									<th data-hide='phone'>Origen/Almacen</th>
									<th data-hide='phone,tablet'>Usuario/Destino</th>
									<th data-hide='phone,tablet'>Dirección de envío</th>
									<th data-hide='phone,tablet'>Telefono</th>
									<th data-hide='phone,tablet'>Email Usuario</th>
									<th data-hide='phone,tablet'>Fecha</th>
									<th data-hide='phone,tablet'>Acciones</th>
									
								</tr>
							</thead>
							<tbody style='width: 100%;'>";
							if($surtidos){	
							foreach ($surtidos as $surtido){
								
								$factura = ($surtido->id_venta > 0) ? "<a class='txt-color-green' style='cursor: pointer;' onclick='factura(".$surtido->id_venta.")' title='Detalles'><i class='fa fa-eye fa-3x'></i></a>" : "" ;
								
								echo "<tr style='width: 100%;'>
									<td class='sorting_1'>". $surtido->id_embarque."</td>
									<td>". $surtido->n_guia."</td>
									<td>". $surtido->origen."</td>
									<td>". $surtido->usuario."</td>
									<td>". $surtido->direccion."</td>
									<td>". $surtido->celular."</td>
									<td>". $surtido->correo."</td>
									<td>". $surtido->fecha_entrega."</td>
									<td class='text-center'>
										<a class='txt-color-orange' style='cursor: pointer;' onclick='detalles(".$surtido->id.")' title='Detalles'><i class='fa fa-cube fa-3x'></i></a>
										".$factura."
									</td>
								</tr>";
						}} 
						echo "	
						</tbody>
					</table> <tr class='odd' role='row'>
					";
			
	}
	function pedidos_transito(){
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
		
		$style=$this->modelo_dashboard->get_style(1);
		$this->template->set("usuario",$usuario);
		
	
		$this->template->set("usuario",$usuario);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
		/*$por_embarcar = $this->modelo_logistico->get_embarque();
		
		$surtidos = array();
		
		foreach ($por_embarcar as $movimiento){
			$mercancia = $this->modelo_logistico->ObtenerMercancia($movimiento->id_mercancia);
			
			if(isset($mercancia[0]->nombre)){
				$surtidos[] = array(
						'id_embarque' => $movimiento->id_embarque,
						'keyword' => $movimiento->keyword,
						'mercancia' => $mercancia[0]->nombre,
						'cantidad'  => $movimiento->cantidad,
						'origen' => $movimiento->origen,
						'usuario' => $movimiento->destino,
						'email'	=> $movimiento->correo,
						'fecha' => $movimiento->fecha_entrega,
						'estatus' => $movimiento->estado_e
				);	
			}
		}
	*/
		$surtidos = $this->modelo_logistico->get_embarque();
		//var_dump($surtidos);exit();
		$this->template->set("style",$style);
		$this->template->set("surtidos",$surtidos);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/embarque/pedidos_transito');
	}
	
	function embarcar()
	{
		$this->modelo_logistico->embarcar();
	}
	
	function detalles()
	{ 
		$id = $this->tank_auth->get_user_id();
		$usuario = $this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$Logistico = $this->general->isAValidUser($id,"logistica");
	
		if(!$Comercial&&!$Logistico){
			redirect('/auth/logout');
		}
		
		$id_surtido = $_POST['id'];
		
		$style=$this->modelo_dashboard->get_style(1);	
		$this->template->set("style",$style);
		
		$productos = "";
		$servicios = array();
		$combinados = array();
		$paquetes = array();
		
		$productos = $this->modelo_logistico->getDetalleProductoPendiente($id_surtido);
		#$servicios = $this->modelo_logistico->getDetalleVentaServicio($id_surtido);
		#$combinados = $this->modelo_logistico->getDetalleVentaCombinado($id_surtido);
		#$paquetes = $this->modelo_logistico->getDetalleVentaPaquete($id_surtido);
		
		$this->template->set("productos",$productos);
		$this->template->set("servicios",$servicios);
		$this->template->set("combinados",$combinados);
		$this->template->set("paquetes",$paquetes);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->build('website/bo/logistico2/embarque/detalles');
	}
	
	function detalles2()
	{
		$id = $this->tank_auth->get_user_id();
		$usuario = $this->general->get_username($id);
	
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$Logistico = $this->general->isAValidUser($id,"logistica");
	
		if(!$Comercial&&!$Logistico){
			redirect('/auth/logout');
		}
	
		$id_surtido = $_POST['id'];
	
		$style=$this->modelo_dashboard->get_style(1);
		$this->template->set("style",$style);
	
		$productos = "";
		$servicios = array();
		$combinados = array();
		$paquetes = array();
	
		$productos = $this->modelo_logistico->getDetalleProductoTransito($id_surtido);
		#$servicios = $this->modelo_logistico->getDetalleVentaServicio($id_surtido);
		#$combinados = $this->modelo_logistico->getDetalleVentaCombinado($id_surtido);
		#$paquetes = $this->modelo_logistico->getDetalleVentaPaquete($id_surtido);
	
		$this->template->set("productos",$productos);
		$this->template->set("servicios",$servicios);
		$this->template->set("combinados",$combinados);
		$this->template->set("paquetes",$paquetes);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->build('website/bo/logistico2/embarque/detalles');
	}
	
	function pedidos_embarcados(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
		
		$style=$this->modelo_dashboard->get_style(1);
		$this->template->set("usuario",$usuario);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
		$this->template->set("usuario",$usuario);
		
		
		/*
		
		$embarcados=$this->modelo_logistico->get_embarcados();
		
		$surtidos = array();
	
		foreach ($embarcados as $movimiento){
			
			$mercancia = $this->modelo_logistico->ObtenerMercancia($movimiento->id_mercancia);
			if(isset($mercancia[0]->nombre)){
				
				$surtidos[] = array(
						'id_embarque' => $movimiento->id_embarque,
						'keyword' => $movimiento->keyword,
						'mercancia' => $mercancia[0]->nombre,
						'cantidad'  => $movimiento->cantidad,
						'origen' => $movimiento->origen,
						'usuario' => $movimiento->destino,
						'email'	=> $movimiento->correo,
						'fecha' => $movimiento->fecha_entrega,
						'estatus' => $movimiento->estado_e
				);
			}
		}*/
		
		$inicio = date('Y-m').'-01';
		$fin = date('Y-m-d');
		
		if($inicio&&$fin){
			$surtidos = $this->modelo_logistico->get_embarcados($inicio,$fin);
		}
		
		$this->template->set("style",$style);
		$this->template->set("surtidos",$surtidos);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/embarque/pedidos_embarcados');
	}
	
	function alta(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$Comercial&&!$Logistico){
			redirect('/auth/logout');
		}
		
		$style=$this->modelo_dashboard->get_style(1);
		
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set("type",$usuario[0]->id_tipo_usuario);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/alta');
	}
	
	function nuevo_surtido(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
	
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
	
		$style=$this->modelo_dashboard->get_style(1);
	
		$surtido = $_POST['surtido'];
		$venta = $_POST['venta'];
		
		$proveedores = $this->modelo_proveedor_mensajeria->obtenerProveedores();
		
		$this->template->set("proveedores",$proveedores);
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set("surtido",$surtido);
		$this->template->set("venta",$venta);
		$this->template->set("type",$usuario[0]->id_tipo_usuario);
		
		$this->template->build('website/bo/logistico2/embarque/surtir');
	}
	
	function movimiento(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
	
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
	
		$style=$this->modelo_dashboard->get_style(1);
	
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/movimiento/index');
	}
	
	function producto(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
	
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
	
		$style=$this->modelo_dashboard->get_style(1);
	
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/producto/index');
	}
	
	function usuarios(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
	
		$Comercial = $this->general->isAValidUser($id,"comercial");
		$CEDI = $this->general->isAValidUser($id,"cedi");
		$almacen = $this->general->isAValidUser($id,"almacen");
		$Logistico = $this->general->isAValidUser($id,"logistica");
		
		if(!$CEDI&&!$almacen&&!$Logistico&&!$Comercial){
			redirect('/auth/logout');
		}
		
	
		$style=$this->modelo_dashboard->get_style(1);
	
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$type = $usuario[0]->id_tipo_usuario;
		$this->template->set("type",$type);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		if($type==8||$type==9){
			$data = array("user" => $usuario[0]->nombre."<br/>".$usuario[0]->apellido);
			$header = $type==8 ? 'CEDI' : 'Almacen';
			$this->template->set_partial('header', 'website/'.$header.'/header2',$data);
		}else{
			$this->template->set_partial('header', 'website/bo/header');
		}
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/logistico2/usuarios/index');
	}
}