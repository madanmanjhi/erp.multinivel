<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//header('Content-Type: text/html; charset-utf-8');
//header('Content-Type: text/html; charset=ISO-8859-1');
class model_inventario extends CI_Model
{
	function setDocumento()
	{
		$dato_documento=array(
				"nombre"     => $_POST['nombre'],
				"estatus"     => 'ACT',
            );
             $this->db->insert("documento_inventario",$dato_documento);
	}
	function Obtener_Almacen($almacen){
		$q=$this->db->query('select * from cedi where tipo  ="'.$almacen.'"');
		return $q->result();
	}
	function Obtener_Productos_Almacen($almacen){
		$q=$this->db->query('SELECT * FROM producto p,inventario i where p.id = i.id_mercancia and i.id_almacen="'.$almacen.'" group by p.id,p.nombre ');
		return $q->result();
	}
	function getAlldocumento(){
		$q=$this->db->query('select * from documento_inventario ');
		return $q->result();
	}
	function getAlmacenesCedi(){
		$q=$this->db->query('select * from cedi ');
		return $q->result();
	}
	function updateEstatusdocumento(){
		$datos = array(
				'estatus' => $_POST['estado'],
				
		);
		$this->db->where('id_doc', $_POST['id']);
		$this->db->update('documento_inventario', $datos);
	}
   function kill_Documento(){
      	$q=$this->db->query('delete from documento_inventario where id_doc ='.$_POST['id']);
   }
   
   function getDocumento($id){
       	$q=$this->db->query('select * from documento_inventario where id_doc ='.$_POST['id']);
     	return $q->result();
   }
   
   function getProductos(){
   	$q=$this->db->query('select * from producto  ');
   	return $q->result();
   }
   
   function update_Documento(){
   	$datos = array(
   			'nombre' => $_POST['nombre'],
   	
   	);
   	$this->db->where('id_doc', $_POST['id']);
   	$this->db->update('documento_inventario', $datos);
   }
   function ingresar_inventario($datos){
   	$this->db->insert("inventario",$datos);
   	return mysql_insert_id();
   }
   function ingresar_inventario_historial($datos){
   	$this->db->insert("inventario_historial",$datos);
  }
  
  function consultar_en_inventario($mercancia,$destino){
  	$q=$this->db->query('select * from inventario where  id_almacen ="'.$destino.'" and id_mercancia ='.$mercancia);
  	return $q->result();
  }
<<<<<<< HEAD
  function getProductos_en_inventario(){
  	$q=$this->db->query('select p.id,p.nombre from producto p ,inventario n where p.id=n.id_mercancia group by p.id,p.nombre ');
  	return $q->result();
  }
  function getAlmacen_en_inventario(){
  	$q=$this->db->query('SELECT c.id_cedi,c.nombre,c.descripcion,c.estatus,c.tipo FROM inventario n,cedi c where c.id_cedi=n.id_almacen group by c.id_cedi,c.nombre');
  	return $q->result();
  }
=======
	function update_bloqueados(){
	   	$this->db->query('update inventario set bloqueados = '.$_POST['bloqueados'].' where id_almacen = '.$_POST['id_cedi'].' and id_mercancia = '.$_POST['mercancia'].'');
  	}
>>>>>>> 1c7cf9386aad62db1136caf9182cc13b32f3ed1f
}
