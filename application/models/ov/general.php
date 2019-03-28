<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/modelos.php';
class general extends mGeneral
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('/bo/bonos/calculador_bono');
		$this->load->model('/bo/bonos/afiliado');
	
	}
	
	function IsActivedPago($id){
		$q = $this->db->query('select estatus from user_profiles where user_id = '.$id);
		$estado = $q->result();

        $estatus = $this->issetVar($estado,"estatus","DES");
        if($estatus == 'ACT'){
			return true;
		}else{
			if($this->VerificarCompraPaquete($id)){
				$this->actualizarEstadoAfiliado($id);
				return true;
			}else{
				return false;
			}
		}
	}
	
	function actualizarEstadoAfiliado($id){
		$this->db->query("UPDATE user_profiles SET estatus = 'ACT' WHERE user_id = ".$id);
	}
	
	function VerificarCompraPaquete($id){
		$q = $this->db->query("SELECT m.id FROM cross_venta_mercancia cvm, venta v, mercancia m
		where v.id_estatus = 2 and cvm.id_venta = v.id_venta and cvm.id_mercancia = m.id and m.id_tipo_mercancia = 4 and v.id_user = ".$id);
	
		$mercancias = $q->result();

        $id_item = $this->issetVar($mercancias,"id");
        $return = isset($id_item);

		return $return;

	}

	function isActived($id){
	
		if($id==2)
			return 0;
		
		return $this->validarMembresias($id);

	}
	
	function isActivedAfiliacionesPuntosPersonales($id_afiliado,$fecha){
        $cualquiera="0";

        $numeroAfiliadosDirectos=$this->getAfiliadosDirectos($id_afiliado);
        $afiliadosParaEstarActivo=$this->getAfiliadosParaEstarActivo();

        if($afiliadosParaEstarActivo>$numeroAfiliadosDirectos)
            return false;

        $fechaInicio=$this->calculador_bono->getInicioMes($fecha);
        $fechaFin=$this->calculador_bono->getFinMes($fecha);

        $puntosParaEstarActivo=$this->getPuntosParaEstarActivo();
        $puntosComisionablesMes=0;

        $redes = $this->getRedes();

        foreach ($redes as $red){
            $puntos=$this->afiliado->getPuntosTotalesPersonalesIntervalosDeTiempo($id_afiliado,$red->id,$cualquiera,$cualquiera,$fechaInicio,$fechaFin);
            $puntosComisionablesMes=$puntosComisionablesMes+$puntos;

        }

        if($puntosParaEstarActivo>$puntosComisionablesMes)
            return false;

        return true;
	}
	
	private function getAfiliadosDirectos($id_afiliado)
    {
		$q=$this->db->query('SELECT count(*) as directos FROM users u,afiliar a
		where u.id=a.id_afiliado and a.directo = '.$id_afiliado); 
		$afiliados=$q->result();

        $directos = $this->issetVar($afiliados,"directos");
        if(!$directos)
            return 0;
		
		return $directos;
	}
	
	private function getAfiliadosParaEstarActivo(){
		$q=$this->db->query('SELECT afiliados_directos as directos FROM empresa_multinivel');
		$afiliados=$q->result();

        $directos = $this->issetVar($afiliados,"directos");
        if(!$directos)
			return 0;
		
		return $directos;
	}
	
	private function getPuntosParaEstarActivo(){
		$q=$this->db->query('SELECT puntos_personales as puntos FROM empresa_multinivel');
		$afiliados=$q->result();

        $puntos = $this->issetVar($afiliados,"puntos");
        if(!$puntos)
			return 0;
	
		return $puntos;
	}
	
	private function validarMembresias($id)
    {
		$membresia=1;

        $obligatoria = $this->compraObligatoria($membresia);
        $hayTipo = $this->hayTipoDeMercancia($membresia);
        $isValidate = ($obligatoria && $hayTipo);

        $compraEstaActiva = (!$isValidate) ? true : $this->compraDeUsuarioEstaActiva($membresia, $id);

        //Mostrar Membresias
        if(!$compraEstaActiva)
		    return $membresia;

		//validar Paquetes
        return $this->validarPaqueteInscripcion($id);
	}
	
	private function validarPaqueteInscripcion($id)
    {
		$paquete=2;

        $obligatoria = $this->compraObligatoria($paquete);
        $hayTipo = $this->hayTipoDeMercancia($paquete);
        $isValidate = ($obligatoria && $hayTipo);

        $compraEstaActiva = (!$isValidate) ? true : $this->compraDeUsuarioEstaActiva($paquete, $id);

        //Mostrar Paquetes
        if(!$compraEstaActiva)
		    return $paquete;

		// validar Items
		return $this->validarItems($id);
	}
	
	private function validarItems($id)
    {
		$item=3;$todos = 0;

        $obligatoria = $this->compraObligatoria($item);
        $hayTipo = $this->hayTipoDeMercancia($item);
        $isValidate = ($obligatoria && $hayTipo);

        $compraEstaActiva = (!$isValidate) ? true : $this->compraDeUsuarioEstaActiva($item, $id);

        //Mostrar Item
        if(!$compraEstaActiva)
		    return $item;

		// Acceso
        return $todos;
	}
	
	private function compraObligatoria($id_tipo = 1)
    {
        $tipo = array(0,"membresia","paquete","item");

        $tipo_compra = isset($tipo[$id_tipo]) ? $tipo[$id_tipo] : false;
        if(!$tipo_compra)
            return false;

		$q = $this->db->query("SELECT $tipo_compra as estado FROM empresa_multinivel;");
		$dato=$q->result();

        $estado = $this->issetVar($dato,"estado","DES");
        $result = ($estado == 'ACT');
		
		return $result;
	}
	
	private function hayTipoDeMercancia($id_tipo = 1)
    {
        $tipo = array(0,"5","4","1,2,3");

        $tipo_compra = isset($tipo[$id_tipo]) ? $tipo[$id_tipo] : false;
        if(!$tipo_compra)
            return false;

		$q = $this->db->query("SELECT * FROM mercancia where id_tipo_mercancia in ($tipo_compra)");
		return $q->result();
	}
	
	private function compraDeUsuarioEstaActiva($id_tipo, $id) {
	
		$membresia = "SELECT v.id_venta,v.fecha,me.caducidad,DATEDIFF(now(),v.fecha) as dias_activacion
                        FROM venta v,cross_venta_mercancia cvm,mercancia m,membresia me
                        WHERE v.id_estatus='ACT'
                            and v.id_venta=cvm.id_venta
                            and m.id=cvm.id_mercancia
                            and m.id_tipo_mercancia=5
                            and v.id_user='".$id."'
                            and m.sku=me.id
                            and (DATEDIFF(now(),v.fecha)<=me.caducidad or me.caducidad=0)";
		
		$paquete = "SELECT v.id_venta,v.fecha,pa.caducidad,DATEDIFF(now(),v.fecha)as dias_activacion
                        FROM venta v,cross_venta_mercancia cvm,mercancia m,paquete_inscripcion pa
                        WHERE v.id_estatus='ACT'
                            and v.id_venta=cvm.id_venta
                            and m.id=cvm.id_mercancia
                            and m.id_tipo_mercancia=4
                            and v.id_user='".$id."'
                            and m.sku=pa.id_paquete
                            and (DATEDIFF(now(),v.fecha)<=pa.caducidad or pa.caducidad=0)";
		
		$item = "SELECT v.id_venta,v.fecha
                    FROM venta v,cross_venta_mercancia cvm,mercancia m
                    WHERE v.id_estatus='ACT'
                        and v.id_venta=cvm.id_venta
                        and m.id=cvm.id_mercancia
                        and m.id_tipo_mercancia not in (4,5)
                        and v.id_user='".$id."'";
		
		$query = array( 
				1 => $membresia,
				2 => $paquete,
				3 => $item 				
		);

        $tipo_query = isset($query[$id_tipo]) ? $query[$id_tipo] : false;
        if(!$tipo_query)
			return false;
		
		$q = $this->db->query($tipo_query);
		return $q->result();
	}

	function get_email($id)
	{
		$q=$this->db->query('select email from users where id = '.$id);
		return $q->result();
	}
	
	function get_pais($id)
	{
        $query = "select
                          cu.pais as pais,c.Name as nombrePais,c.Code2 as codigo,
                          concat(cu.calle,' ',cu.colonia,' ',cu.municipio,' ',cu.estado) as direccion,
                          cu.cp as codigo_postal,cu.estado as estado,cu.municipio as municipio,
                          cu.colonia as colonia,cu.calle as calle
                      from 
                          cross_dir_user cu,Country c
                      where 
                          c.Code=cu.pais and cu.id_user =$id";
        $q=$this->db->query($query);
		return $q->result();
	}
	
	function username($id)
	{
		$q=$this->db->query('select username from users where id = '.$id);
		return $q->result();
	}
	function emailPagos()
	{
		$q=$this->db->query(' SELECT email FROM emails_departamentos LIMIT 0 , 1');
		return $q->result();
	}

    public function setAfiliadoenRed($id,$red){

        $isAfiliado = $this->isAfiliadoenRed ( $id, $red );

        if ($isAfiliado)
            return $this->setRedActived($id, $red);

        $Redes = $this->isAfiliadoenRed($id);
        $isNew = (sizeof($Redes)==1) && (sizeof($isAfiliado) == 1);

        $this->insertNewRed($id, $red);

        return ($isNew);

    }

    private function insertNewRed($id, $red) {

        $this->nueva_red($id,$red);

        $negocio = "(SELECT
					            max(id_red) id
					        FROM
					            red
					        WHERE
					            lider = a.directo AND tipo_red = $red and estatus = 'ACT')";

        $query = "INSERT INTO afiliar
                          SELECT null,$red,$id,0,directo,lado,'ACT',$negocio
                                    FROM afiliar a WHERE id_afiliado = $id AND id_red = 1";
        $this->db->query($query);
    }

    function getLider($id,$red){
        $query = "SELECT
					            debajo_de id
					        FROM
					            afiliar
					        WHERE
					            id_afiliado in (SELECT debajo_de FROM afiliar 
                                        WHERE id_red = $red  
                                            AND id_afiliado = $id)
                                AND id_red = $red ";
        $q = $this->db->query($query);

        $q = $q->result();
        return $q ? $q[0]->id : 2;

    }

    private function nueva_red($id,$red = 1) {

        $ciclo = ($red == 2) ? 1 : 0;

        $dato_red = array(
            'tipo_red' => $red,
            "lider" => $id,
            "ciclo" => $ciclo,
            "estatus" => "ACT"
        );

        $this->db->insert("red", $dato_red);

        return $this->db->insert_id();
    }

    private function setRedActived($id_usuario,$id_red)
    {
        $dato = array(
            "estatus" => "ACT"
        );

        $this->db->where('id_afiliado', $id_usuario);
        $this->db->where('id_red', $id_red);
        $this->db->update('afiliar', $dato);

        return true;

    }

    public function isAfiliadoenRed($id, $red = false) {

        $query = "SELECT * FROM afiliar WHERE id_afiliado = $id";

        if($red)
            $query.=" AND id_red in ($red)";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }


    function isPreActived($id)
    {
        if($id==2)
            return true;

        $q=$this->db->query("SELECT * FROM venta WHERE id_user = $id");
        return $q->result();
    }

}