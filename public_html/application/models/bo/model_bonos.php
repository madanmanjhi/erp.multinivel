<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_bonos extends CI_Model
{

public function setUp($nombre,$descripcion,$inicio,$fin,$mes_desde_afiliacion,$mes_desde_activacion,$frecuencia,$estatus,$plan){

	$bono = array(
			'nombre' => $nombre,
			'descripcion' => $descripcion,
			'inicio' => $inicio,
			'fin' => $fin,
			'mes_desde_afiliacion' => $mes_desde_afiliacion,
			'mes_desde_activacion' => $mes_desde_activacion,
			'frecuencia' => $frecuencia,
			'plan' => $plan,
			'estatus' => $estatus,
	);
	return $bono;
}

public function setUpValoresBones($idBono,$nivel,$valor){

	$bono_valores = array(
			'id_bono' => $idBono,
			'nivel' => $nivel,
			'valor' => $valor,
	);
	return $bono_valores;
}

public function setUpCondicion($idBono,$idRango,$idTipoRango,$red,$condicion1,$condicion2){
	$rango=$this->get_rangos_id_tipo($idRango, $idTipoRango);
	$bonoCondiciones = array(
			'id_bono' => $idBono,
			'id_rango' => $idRango,
			'id_tipo_rango' => $idTipoRango,
			'condicion_rango' => $rango[0]->valor,
			'id_red' => $red,
			'condicion1' => $condicion1,
			'condicion2' => $condicion2,
	);
	return $bonoCondiciones;
}

function insert_bono($bono){
	$this->db->insert("bono",$bono);
	$q=$this->db->query("SELECT id FROM bono order by id desc  limit 0,1 ");
	$q=$q->result();
	return $q[0]->id;
}

function actualizar_bono($idBono,$datosBono){

	$this->db->where('id',$idBono);
	$this->db->update('bono', $datosBono);
	return true;
}

function kill_bono($id){
	$this->db->query("DELETE FROM cat_bono_valor_nivel where id_bono='".$id."'");
	$this->db->query("DELETE FROM cat_bono_condicion where id_bono='".$id."'");
	$this->db->query("DELETE FROM bono where id='".$id."'");
}

function cambiar_estado_bono($estado,$id_bono){
	$this->db->query("update bono set estatus = '".$estado."' where id=".$id_bono);
	return true;
}

function insert_bono_valor_niveles($valoresBono){
	$this->db->insert("cat_bono_valor_nivel",$valoresBono);

}

function kill_bono_valor_nivel($idBono){
	$this->db->query("DELETE FROM cat_bono_valor_nivel where id_bono='".$idBono."'");
}

function kill_bono_condicion($idBono){
	$this->db->query("DELETE FROM cat_bono_condicion where id_bono='".$idBono."'");
}

function actualizar_bono_valor_niveles($idBono,$valoresBono){

	$this->db->insert("cat_bono_valor_nivel",$valoresBono);

}

function insert_condicion_bono($condicion){
	$this->db->insert("cat_bono_condicion",$condicion);
}

function get_rangos(){

		$q=$this->db->query("SELECT cr.id_rango as id_rango,cr.nombre as nombre_rango,cr.descripcion as descripcion,
								ct.id as tipo_rango,ct.nombre as nombre_tipo_rango,crt.valor as valor,cr.estatus
								FROM cat_rango cr,cat_tipo_rango ct,cross_rango_tipos crt
								where (cr.id_rango=crt.id_rango)
								and (ct.id=crt.id_tipo_rango)and cr.estatus='ACT'
								group by id_rango ");
		return $q->result();
}
function get_rangos_id($id){

	$q=$this->db->query("SELECT cr.id_rango as id_rango,cr.nombre as nombre_rango,cr.descripcion as descripcion,
								ct.id as tipo_rango,ct.nombre as nombre_tipo_rango,crt.valor as valor,cr.estatus
								FROM cat_rango cr,cat_tipo_rango ct,cross_rango_tipos crt
								where (cr.id_rango=crt.id_rango)
								and (ct.id=crt.id_tipo_rango)and cr.estatus='ACT'
								and(cr.id_rango=".$id.") ");
	return $q->result();
}
function get_rangos_bono($id){
	$q=$this->db->query("SELECT * FROM cat_bono_condicion where id_bono=".$id." group by id_rango");
	return $q->result();
}
function get_tipo_rangos_bono($id){
	$q=$this->db->query("SELECT * FROM cat_bono_condicion where id_bono=".$id." group by id_rango,id_tipo_rango");
	return $q->result();
}
function get_rangos_id_tipo($id,$tipoRango){

	$q=$this->db->query("SELECT cr.id_rango as id_rango,cr.nombre as nombre_rango,cr.descripcion as descripcion,
								ct.id as tipo_rango,ct.nombre as nombre_tipo_rango,crt.valor as valor,cr.estatus
								FROM cat_rango cr,cat_tipo_rango ct,cross_rango_tipos crt
								where (cr.id_rango=crt.id_rango)
								and (ct.id=crt.id_tipo_rango)and cr.estatus='ACT'
								and(cr.id_rango=".$id.") and(ct.id=".$tipoRango.")");
	return $q->result();
}

function get_mercancia_tipos(){

	$q=$this->db->query("SELECT * FROM cat_tipo_mercancia;");
	return $q->result();
}
function get_productos_red($idRed){
	$q=$this->db->query("select M.id,P.nombre, CTM.descripcion, TR.nombre red,C.Name
							from mercancia M, producto P, cat_tipo_mercancia CTM, 
							cat_grupo_producto CGP, tipo_red TR, Country C
							where M.sku = P.id and CTM.id = M.id_tipo_mercancia 
							and M.id_tipo_mercancia=1 
							and P.id_grupo = CGP.id_grupo 
							and CGP.id_red = TR.id 
							and C.Code = M.pais
							and TR.id=".$idRed."");
	return $q->result();
}
function get_producto_por_id($idproducto){
	$q=$this->db->query("select M.id,P.nombre, CTM.descripcion, TR.nombre red,C.Name
							from mercancia M, producto P, cat_tipo_mercancia CTM,
							cat_grupo_producto CGP, tipo_red TR, Country C
							where M.sku = P.id and CTM.id = M.id_tipo_mercancia
							and M.id_tipo_mercancia=1
							and P.id_grupo = CGP.id_grupo
							and CGP.id_red = TR.id
							and C.Code = M.pais
							and M.id=".$idproducto."");
	return $q->result();
}
function get_servicios_red($idRed){
	$q=$this->db->query("select M.id, M.sku, M.fecha_alta, M.real, M.costo, M.costo_publico, M.estatus , S.nombre, CI.url, CTM.descripcion, TR.nombre red, M.pais, C.Name, C.Code2
							from mercancia M, servicio S, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country C
							where M.sku = S.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=2 and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = S.id_red and CGP.id_red = TR.id and C.Code = M.pais
							and TR.id=".$idRed."");
	return $q->result();
}
function get_servicio_por_id($idServicio){
	$q=$this->db->query("select M.id, M.sku, M.fecha_alta, M.real, M.costo, M.costo_publico, M.estatus , S.nombre, CI.url, CTM.descripcion, TR.nombre red, M.pais, C.Name, C.Code2
							from mercancia M, servicio S, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country C
							where M.sku = S.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=2 and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = S.id_red and CGP.id_red = TR.id and C.Code = M.pais
							and M.id=".$idServicio."");
	return $q->result();
}
function get_combinados_red($idRed){
	$q=$this->db->query("select M.id,C.nombre, CTM.descripcion, TR.nombre red,CO.Name
							 from mercancia M, combinado C, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, 
								  tipo_red TR, cat_grupo_producto CGP, Country CO
							
							where M.sku = C.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=3 and 
							CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = C.id_red and
							CGP.id_red = TR.id and CO.Code = M.pais
							and TR.id=".$idRed."");
	return $q->result();
}
function get_combinado_por_id($idCombinado){
	$q=$this->db->query("select M.id,C.nombre, CTM.descripcion, TR.nombre red,CO.Name
							 from mercancia M, combinado C, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI,
								  tipo_red TR, cat_grupo_producto CGP, Country CO
				
							where M.sku = C.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=3 and
							CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = C.id_red and
							CGP.id_red = TR.id and CO.Code = M.pais
							and M.id=".$idCombinado."");
	return $q->result();
}
function get_paquetes_red($idRed){
	$q=$this->db->query("select M.id,P.nombre, CTM.descripcion, TR.nombre red,CO.Name
						from mercancia M, paquete_inscripcion P, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country CO
						where M.sku = P.id_paquete and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia= 4 
						and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = P.id_red and
						CGP.id_red = TR.id and CO.Code = M.pais
							and TR.id=".$idRed."");
	return $q->result();
}
function get_paquete_por_id($idPaquete){
	$q=$this->db->query("select M.id,P.nombre, CTM.descripcion, TR.nombre red,CO.Name
						from mercancia M, paquete_inscripcion P, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country CO
						where M.sku = P.id_paquete and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia= 4
						and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = P.id_red and
						CGP.id_red = TR.id and CO.Code = M.pais
							and M.id=".$idPaquete."");
	return $q->result();
}

function get_membresia_red($idRed){
	$q=$this->db->query("select M.id, M.sku, M.fecha_alta, M.real, M.costo, M.costo_publico, M.estatus , S.nombre, CI.url, CTM.descripcion, TR.nombre red, M.pais, C.Name, C.Code2
							from mercancia M, membresia S, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country C
							where M.sku = S.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=5 and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = S.id_red and CGP.id_red = TR.id and C.Code = M.pais
							and TR.id=".$idRed."");
	return $q->result();
}
function get_membresia_por_id($idMembresia){
	$q=$this->db->query("select M.id, M.sku, M.fecha_alta, M.real, M.costo, M.costo_publico, M.estatus , S.nombre, CI.url, CTM.descripcion, TR.nombre red, M.pais, C.Name, C.Code2
							from mercancia M, membresia S, cat_tipo_mercancia CTM, cat_img CI, cross_merc_img CMI, tipo_red TR, cat_grupo_producto CGP, Country C
							where M.sku = S.id and CTM.id = M.id_tipo_mercancia and M.id_tipo_mercancia=5 and CI.id_img = CMI.id_cat_imagen and M.id = CMI.id_mercancia and CGP.id_grupo = S.id_red and CGP.id_red = TR.id and C.Code = M.pais
							and M.id=".$idMembresia."");
	return $q->result();
}

function get_bonos(){
	$q=$this->db->query("SELECT b.id,b.nombre,b.descripcion,b.inicio,b.fin,b.frecuencia,b.estatus
						FROM bono b
						where b.plan='NO'
			");
	return $q->result();
}
function get_bono_id($id){
	$q=$this->db->query("SELECT b.id,b.nombre,b.descripcion,b.inicio,b.fin,b.frecuencia,b.estatus,b.mes_desde_afiliacion,b.mes_desde_activacion
						FROM bono b
						where b.plan='NO' and id=".$id."
			");
	return $q->result();
}

function get_valor_niveles(){
	$q=$this->db->query("SELECT * FROM cat_bono_valor_nivel order by nivel;
			");
	return $q->result();
}

function get_valor_niveles_id_bono($id){
	$q=$this->db->query("SELECT * FROM cat_bono_valor_nivel where id_bono=".$id." order by nivel;
			");
	return $q->result();
}


function get_condiciones_bonos(){
	$q=$this->db->query("SELECT CBC.id , CBC.id_bono as id_bono,CR.nombre as nombreRango,
							CTR.nombre as nombreTipoRango ,CBC.id_tipo_rango as id_tipo_rango,CBC.condicion_rango as condicionRango ,
							GROUP_CONCAT(DISTINCT TR.nombre) as nombreRedes ,
							GROUP_CONCAT(DISTINCT CBC.condicion1)as condicion1,
							GROUP_CONCAT(DISTINCT CBC.condicion2)as condicion2 FROM 
							bono B,cat_bono_condicion CBC,cat_bono_valor_nivel CBN ,
							cat_rango CR,cat_tipo_rango CTR,tipo_red TR
							where(B.id=CBC.id_bono)
							and(B.plan='NO')
							and(B.id=CBN.id_bono)
							and(CBC.id_rango=CR.id_rango)
							and(CBC.id_tipo_rango=CTR.id)
							group by CBC.id_bono,CBC.id_rango,CBC.id_tipo_rango");
	$condiciones_bono=$q->result();
	$resultado=array();
	
	foreach ($condiciones_bono as $condicion_bono){

		$bonoCondiciones = array(
				'id_bono' => $condicion_bono->id_bono,
				'nombreRango' => $condicion_bono->nombreRango,
				'tipoRango' => $condicion_bono->nombreTipoRango,
				'nombreRed' => $condicion_bono->nombreRedes,
				'condicionRango' => $condicion_bono->condicionRango,
				'condicion1' => $this->get_nombre_condicion_bono($condicion_bono->id_tipo_rango,$condicion_bono->condicion1,1),
				'condicion2' => $this->get_nombre_condicion_bono($condicion_bono->id_tipo_rango,$condicion_bono->condicion2,2),
		);
		
		array_push($resultado, $bonoCondiciones);
	}
	
	return $resultado ;
}

function get_condiciones_bonos_id_bono($id_bono){
	$q=$this->db->query("SELECT * FROM cat_bono_condicion where id_bono=".$id_bono." order by id_tipo_rango");
	return $q->result();
}

function get_red_condiciones_bonos_id_bono($id_bono){
	$q=$this->db->query("SELECT * FROM cat_bono_condicion where id_bono=".$id_bono." group by id_rango,id_tipo_rango,id_red order by id_red");
	return $q->result();
}

function get__condicioneses_bonos_id_bono($id_bono){
	$q=$this->db->query("SELECT * FROM cat_bono_condicion where id_bono=".$id_bono." group by id_rango,id_tipo_rango,id_red,condicion1,condicion2 order by id_red");
	return $q->result();
}

	private function get_nombre_rango($id_rango){
		$q=$this->db->query("SELECT nombre FROM cat_rango where id_rango='".$id_rango."'");
		$nombreRango=$q->result();
		return $nombreRango[0]->nombre;
	}
	
	private function get_nombre_red_bono($id_red) {
		$nombreRed="";
		if($id_red==0){
			$nombreRed="Todas";
		}else {
			$q1=$this->db->query("SELECT nombre FROM tipo_red where id='".$id_red."'");
			$nombreRed=$q1->result();
			$nombreRed=$nombreRed[0]->nombre;
		}
		return $nombreRed;
	}
	
	private function get_nombre_tipo_rango($id_tipo_rango) {
		$tipoRango="";
		
		if($id_tipo_rango==1)
			$tipoRango="Afiliaciones";
		if($id_tipo_rango==2)
			$tipoRango="Ventas";
		if($id_tipo_rango==3)
			$tipoRango="Compras";
		
		return $tipoRango;
	}

	private function get_nombre_condicion_bono($id_tipo_rango,$condiciones,$tipoCondicion){
		$condiciones = explode(',', $condiciones);
		
		$nombreCondicion=array();
		foreach ($condiciones as $condicion){
			if($id_tipo_rango==1){
					
				if ($condicion==0)
					array_push($nombreCondicion,"Todos");
				else 
					array_push($nombreCondicion,$condicion);
					
			}else{
				$con ="Todos";
				
				if (!$condicion=='0'){
					
					if($tipoCondicion==1){
					$con=$this->get_nombre_tipo_mercancia($condicion);
					}
					else{
					$con=$this->get_mercancia_por_id($condicion);
					}
				}
				array_push($nombreCondicion,$con);
			}
		}
		return $nombreCondicion;
	}
	
	private function get_nombre_tipo_mercancia($id_tipo_mercancia){
		$q=$this->db->query("SELECT * FROM cat_tipo_mercancia where id=".$id_tipo_mercancia."");
		$nombre=$q->result();
		if(isset($nombre[0]->descripcion))
			return $nombre[0]->descripcion;
		return "";
	}
	
	private function get_mercancia_por_id($id_mercancia){
		
		$q=$this->db->query("SELECT id_tipo_mercancia FROM mercancia where id=".$id_mercancia."");
		$idTipoMercancia=$q->result();
		$mercancia=array();
			if($idTipoMercancia[0]->id_tipo_mercancia==1){
				$mercancia=$this->get_producto_por_id($id_mercancia);
				
			}else if($idTipoMercancia[0]->id_tipo_mercancia==2){
				$mercancia=$this->get_servicio_por_id($id_mercancia);
				
			}else if($idTipoMercancia[0]->id_tipo_mercancia==3){
				$mercancia=$this->get_combinado_por_id($id_mercancia);
				
			}else if($idTipoMercancia[0]->id_tipo_mercancia==4){
				$mercancia=$this->get_paquete_por_id($id_mercancia);
			}else if($idTipoMercancia[0]->id_tipo_mercancia==5){
				$mercancia=$this->get_membresia_por_id($id_mercancia);
			}
		if(isset($mercancia[0]->nombre))
			return $mercancia[0]->nombre;
		return "";
	}
}