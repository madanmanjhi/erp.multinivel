<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class korakbonos extends CI_Model
{

    private $afiliados = array();

    private $fechaInicio = '';

    private $fechaFin = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('/bo/bonos/afiliado');
    }

    function getAfiliados()
    {
        $val = $this->afiliados;
        $this->afiliados = array();
        return $val;
    }

    function setAfiliados($afiliados)
    {
        array_push($this->afiliados, $afiliados);
    }

    function setFechaInicio($value = '')
    {
        if (! $value)
            $value = date('Y-m-d');
        
        $this->fechaInicio = $value;
    }

    function setFechaFin($value = '')
    {
        if (! $value)
            $value = date('Y-m-d');
        
        $this->fechaFin = $value;
    }

    function calcularBonosVenta($id_usuario, $id_venta = false,$datos = false, $fecha = false)
    {
               
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $condiciones = array(
            "id_usuario" => $id_usuario,
            "valor" => ($datos) ? array($id_venta => $datos) : $id_venta,
            "fecha" => $fecha
        );
        
        $bonos = $this->getBonos("frecuencia = 'DIA'");
        
        foreach ($bonos as $bono) {
            $parametros = $this->getParametros($bono->id, $condiciones);
            
            if($parametros){
                foreach ($parametros as $param)
                $this->repartir($param); 
            }            
           
        }
        
        $id_dato = $id_usuario;
        while ($id_dato >=2){ 
            $afiliacion = $this->getAfiliacion($id_dato);
            $id_dato = $afiliacion->debajo_de;
            $this->getTituloAfiliado($id_dato);
        }
        
        return true;
    }
      
    private function getParametros($id_bono, $parametro)
    {
        $id_usuario = $parametro["id_usuario"];
        $id_venta = $parametro["valor"];
        $fecha = $parametro["fecha"];
        
        $valores = $this->getBonoValorNiveles($id_bono); 
        
        $valor_dato = $this->validarComision($id_usuario, $valores, $id_venta, $fecha);
       
        if (!$valor_dato)
            return false;
        
        $isCliente = $id_bono == 1;
        $isProducto = $valor_dato[0]->id_tipo_mercancia == 1;
        if ($isCliente && $isProducto) {
            $inscripcion = $this->getInscripcionUsuario($id_usuario, $fecha);
            if (! $inscripcion)
                return false;
            $isVIP = $inscripcion->id_mercancia == 4;
            if (! $isVIP)
                return false;
        }
        
        $parametros = array(
            "id_afiliado" => 0,
            "valor" => 0,
            "fecha" => $fecha,
            "id_venta" => $valor_dato[0]->id_venta,
            "id_bono" => $id_bono
        );
        
        $id_dato = $id_usuario;
        $ascendente = array();
        unset($valores[0]);
        foreach ($valores as $key => $value) {
            
            $afiliacion = $this->getAfiliacion($id_dato);
            $id_dato = ($isCliente) ? $afiliacion->directo : $afiliacion->debajo_de;
            
            if($id_dato <=2)
                break;
            
            $per = $value->valor;    
            $valor = $this->setCantidadIndirecta($valor_dato, $id_dato);
            $valor *= $per;                
                           
            $parametros["id_afiliado"] = $id_dato;
            $parametros["valor"] = $valor;
             
            log_message('DEV', "nivel ".$value->nivel." [$valor] ::> " . json_encode($parametros));    
            
            $isActived = $this->isActivedAfiliado($id_dato);
            
            $isValid = ($valor>0) && ($isActived);
            if($isValid)
                array_push($ascendente, $parametros);    
            
        }
            
        log_message('DEV', "Ascendente ::> " . json_encode($ascendente));
        
        return $ascendente;
    }
    
    private function validarComision($id_usuario,$valores,$id_venta = false,$fecha = false,$nivel = 0){
        
        if (! $fecha)
            $fecha = date("Y-m-d");
        
        $id_bono = $valores[0]->id_bono;
        $condiciones = $this->getBonoCondiciones($id_bono);
        
        $tipo = $this->setCondicionValores($condiciones, "condicion1");
        $item = $this->setCondicionValores($condiciones, "condicion2");
        $venta_id = 0;
        
        if (gettype($id_venta) != "array") {
            $venta_id = $id_venta;
            $where = "AND cvm.costo_unidad > 0";
            $ventas = $this->getVentaMercancia($id_usuario, $fecha, $fecha, $tipo, $item, $where);
            
            $items = array();
            foreach ($ventas as $venta) {
                $isData = $venta->id_venta == $id_venta;
                if ($isData)
                    array_push($items, $venta);
            }
            
            $id_venta = array(
                $items
            );
        }
        
        $ventas = array();
        foreach ($id_venta as $key => $venta) {
            $ventas = $venta;
            if($venta_id==0)
                $venta_id = $key;
        }
        
        $items = array();
        foreach ($ventas as $key => $venta) {
            
            $venta->id_venta = $venta_id;
            
            $isCosto = $venta->costo_unidad > 0;
            $isTipo = $venta->id_tipo_mercancia == $tipo;
            $isItem = $venta->id == $item;
            $isData = $isTipo && $isCosto;
           
            if ($item>0)
                $isData &=  $isItem;        

            if ($isData)
                array_push($items, $venta);
        }
        
        log_message('DEV', "items ($tipo, $item) ::> " . json_encode($items));
       
        if(sizeof($items)==0)
            return false;
        
        return $items;
                
    }
    
    private function repartir($parametro){
        
        if(!$parametro)
            return false;
            
            $isUpdate = $this->isPaidComision($parametro,"id_venta,id_bono");
            
            log_message('DEV',"isUpdate::{id_venta,id_bono} > ".$parametro["id_venta"]." | ".$parametro["id_bono"]." => ".intval($isUpdate));
            
            if($isUpdate){
                $this->db->where("id_venta",$parametro["id_venta"]);
                $this->db->where("id_bono",$parametro["id_bono"]);
                $this->db->update("comision",$parametro);
            }else{
                $this->db->insert("comision",$parametro);
                #TODO :$this->notificar($parametro);
            }
            
    }
    
    private function notificar ($parametro){
        
        $bono = $this->getBono($parametro["id_bono"]);
        
        $dato = array(
            "fecha_fin" => date('Y-m-t'),
            "nombre" => "BONO ".$bono[0]->nombre,
            "descripcion" => "Has ganado $ ".$parametro["valor"],
            "user" => $parametro["id_afiliado"],
            "link" => "/ov/billetera2/pedir_pago",
            "estatus" => "ACT"
        );
        
        $this->db->insert("notificacion",$dato);
    }
    
    
    private function isPaidComision($parametro=false,$values="id_venta"){
        
        $where = "";
        if($parametro){
            $where = array();
            $values = explode(",", $values);
            foreach ($values as $value){
                $attrib=" $value = ".$parametro[$value];
                array_push($where,$attrib);
            }
            $where = "WHERE ".implode(" AND ", $where);
        }
        
        $id_venta = $parametro['id_venta'];
        $q= $this->db->query("select * from comision ".$where);
        $q=$q->result();
        
        if(!$q)
            return false;
            
            return true;
    }
    
    function getTituloAfiliado($id_usuario, $red = 1, $fecha = '')
    {
               
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $inscripcion = $this->getInscripcionUsuario($id_usuario, $fecha, "AND m.id not in (4)");
        
        if (! $inscripcion)
            return false;
        
        $fechaInicial = $this->getFechaRango($id_usuario,$inscripcion,$fecha);
        
        $actual = $this->getRangoEntregado($id_usuario,true);
        $distribuidor = $inscripcion->id_mercancia == 1;
        
        if($actual==0 && !$distribuidor ){
            $fechaFin = $this->getPeriodoFecha("SEM", "FIN", $fecha);
            $fechaInicio = $this->getPeriodoFecha("SEM", "INI", $this->getAnyTime($fechaFin, '27 days',false));
        }else{
            $fechaInicio = $this->getPeriodoFecha("SEM", "INI", $fechaInicial);
            $fechaFin = $this->getPeriodoFecha("SEM", "FIN", $this->getAnyTime($fechaInicio, '27 days'));
        }            
            
        $cantidad = $this->getProduccionRango($id_usuario,$fechaInicio, $fechaFin);
        
        $alcanza = $this->evaluarTitulo($id_usuario,$fechaInicio, $fechaFin,$cantidad); 
           
        if ($alcanza==0)
            return false;       
        
        $where = "orden = " . $alcanza;
        $titulo = $this->getTitulo(0, $where);
        $titulo = $titulo ? $titulo->nombre : false;
        
        return $titulo;
    }
    
    private function getProduccionRango($id_usuario,$fechaInicio, $fechaFin)
    {
        $redAfiliados = $this->getRedTodo($id_usuario);
        $where = "AND cvm.costo_unidad > 0";
        $cantidad = 0;
        foreach ($redAfiliados as $datos) {
            $afiliado = implode(",", $datos);
            $venta = $this->getVentaMercancia($afiliado, $fechaInicio, $fechaFin, 1,false,$where);
            $valor = $this->setCantidadIndirecta($venta, $id_usuario);
            $cantidad += $valor;
        }
        return $cantidad;
    }

     
     private function getFechaRango($id_usuario,$inscripcion, $fecha)
    {   
        $fecha = date('Y-m-d', strtotime($fecha));             
        $fechaInicial = $inscripcion->fecha;
        $rango = $this->getRangoAfiliado($id_usuario);
        $fecha_ultima = $rango ? $rango->fecha : false;
        
        if ($fecha_ultima) {
            $fecha_ultima = date('Y-m-d', strtotime($fecha_ultima));
            $fechaInicial = date('Y-m-d', strtotime($fechaInicial));
            if ($fecha_ultima > $fechaInicial && $fecha_ultima < $fecha)
                $fechaInicial = $fecha_ultima;
        }
        
        return $fechaInicial;
    }


    private function getRedTodo($id_usuario)
    {
        $redAfiliados = array();
        $afiliados = true;
        $nivel = 1;
        while ($afiliados) {
            $this->getAfiliadosBy($id_usuario, $nivel, "RED");
            $afiliados = $this->getAfiliados();
            if ($afiliados)
                array_push($redAfiliados, $afiliados);
            $nivel ++;
        }
        return $redAfiliados;
    }

    private function evaluarTitulo($id_usuario,$fechaInicio, $fechaFin,$cantidad)
    {
       
        $titulos = $this->getTitulo(false, "estatus = 'ACT'");
        if (! $titulos)
            return 0;
        
        $actual = $this->getRangoEntregado($id_usuario);
            
        $alcanza = 0;   
        foreach ($titulos as $titulo) { 
            
            if($cantidad<$titulo->valor)
                break;   
            
            $alcanza = $titulo->id;                         
        }
        
        log_message('DEV', ":> $cantidad ");
        if ($alcanza == 0)
            return $actual;
        
        $entregado = $this->procesoNuevoRango($id_usuario,$fechaInicio, $fechaFin,  $actual, $alcanza);
        
        #$entregado = $this->entregarRango($id_usuario, $alcanza,$fecha);
        
        return  $entregado ?
        $alcanza : false;
    }
    
    private function procesoNuevoRango($id_usuario,$fechaInicio, $fechaFin,  $actual, $alcanza)
     {  
        $entregado = false;
        $miRango = $this->getRangoAfiliado($id_usuario);
        log_message('DEV', "1> $actual 2> $alcanza 3> ($fechaInicio - $fechaFin) 4> ".$miRango->fecha." ");
        
        $nuevo = $this->getTitulo(0, "id = $alcanza AND estatus = 'ACT'");
        
        $fecha_ultima = date('Y-m-d', strtotime($miRango->fecha));
        $fechaInicial = date('Y-m-d', strtotime($fechaInicio));
        $fechaFinal = date('Y-m-d', strtotime($fechaFin));
        
        $isNewWrite = $fecha_ultima < $fechaInicial;
        $isRewrite = $fecha_ultima<$fechaFinal && !$isNewWrite;        
        $isEqual = $actual >= $nuevo->id;        
        log_message('DEV',"_:::> ".intval($isNewWrite)." | ".intval($isRewrite)." | ".intval($isEqual)."");
        
        $isDuplicado = $miRango->ciclo==3 && $nuevo->porcentaje >=2;
        $isPeriodo = $miRango->ciclo<=$nuevo->porcentaje || $isDuplicado;
        log_message('DEV',"_::> ".intval($isDuplicado)." | ".intval($isPeriodo));
        
        if($isNewWrite){
            $ciclo = $miRango->ciclo+1 ;            
            $rango_id = (!$isEqual && $isPeriodo) ? $actual : $alcanza;
            $entregado = $this->entregarRango($id_usuario, $rango_id,$fechaFin,$ciclo,true);
        }else if(!$isRewrite){            
            $ciclo = 1;#TODO :($actual==1) ? 0 : 1;
            if(!$isEqual ) 
                $entregado = $this->entregarRango($id_usuario, $alcanza,false,$ciclo,true);
            
        }else if(!$isEqual){
            $entregado = $this->entregarRango($id_usuario, $alcanza);
        }else{
            $entregado = $this->entregarRango($id_usuario, $alcanza);
        }
        
        return $entregado;
    }


    private function entregarRango($id_usuario, $alcanza,$fecha = false,$ciclo = 0,$entregado = false)
    {
        $rango = $this->getRangoAfiliado($id_usuario); 
        
        $titulo = array(
            "id_user" => $id_usuario,
            "id_rango" => $alcanza,            
            "estatus" => "ACT"
        );
        
        if($fecha)
            $titulo["fecha"] = $fecha;
            
        if($ciclo>0)
            $titulo["ciclo"] = $ciclo; 
        
        if($entregado)
            $titulo["entregado"] = 0;                
        
        if (! $rango)
            $this->db->insert("cross_rango_user", $titulo);
        else { 
            $this->db->where('id_user', $id_usuario);
            $this->db->update('cross_rango_user', $titulo);
        }
        
        $rango = $this->getRangoEntregado($id_usuario,true) ;
        return $rango>0;
    }

    private function getRangoEntregado($id_usuario,$entregado =  false)
    {
        $rango = $this->getRangoAfiliado($id_usuario,$entregado);
        
        if (! $rango)
            return 0;
        
        $id = $rango->id_rango;
        
        if($entregado&&$rango->entregado ==0)
            return 0;
        
        return $id;
    }

    private function getRangoAfiliado($id_usuario)
    {
        $query = "SELECT
					    *
					FROM
					    cross_rango_user c
					WHERE
					    id_user = $id_usuario
					    AND estatus = 'ACT'";
        
        $q = $this->db->query($query);
        $q = $q->result();
        return $q ? $q[0] : false;
    }

    private function ordenarPatas($Patas)
    {
        $mayor = 0;
        $medio = 0;
        $menor = 0;
        foreach ($Patas as $pata) {
            
            $conteo = sizeof($pata);
            
            $isMayor = $conteo > $mayor;
            $isMedio = $conteo > $medio;
            
            if ($isMayor) {
                $menor = $medio;
                $medio = $mayor;
                $mayor = $conteo;
            } else if ($isMedio) {
                $menor = $medio;
                $medio = $conteo;
            } else {
                $medio = $menor;
                $menor = $conteo;
            }
            
            if ($medio == 0)
                $medio = $conteo;
        }
        
        $escala = array(
            $menor,
            $medio,
            $mayor
        );
        return $escala;
    }

    private function ordenarPuntosPatas($Patas)
    {
        $mayor = 0;
        $medio = 0;
        $menor = 0;
        foreach ($Patas as $puntos) {
            
            $isMayor = $puntos > $mayor;
            $isMedio = $puntos > $medio;
            
            if ($isMayor) {
                $menor = $medio;
                $medio = $mayor;
                $mayor = $puntos;
            } else if ($isMedio) {
                $menor = $medio;
                $medio = $puntos;
            } else {
                $medio = $menor;
                $menor = $puntos;
            }
            
            if ($medio == 0)
                $medio = $puntos;
        }
        
        $escala = array(
            $menor,
            $medio,
            $mayor
        );
        return $escala;
    }

    private function setQuincenaAnterior()
    {
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        
        $isSegunda = $fechaFin == date('Y-m-t', strtotime($fechaInicio));
        
        $fecha_sub = new DateTime($fechaInicio);
        
        date_sub($fecha_sub, date_interval_create_from_date_string('1 month'));
        
        if ($isSegunda) {
            $fechaInicio = $this->getPeriodoFecha("MES", "INI", $fechaInicio);
            $fechaFin = $this->getPeriodoFecha("QUI", "FIN", $fechaInicio);
        } else {
            $fechaFin = date_format($fecha_sub, 'Y-m-t');
            $fechaInicio = $this->getPeriodoFecha("QUI", "INI", $fechaFin);
        }
        
        $this->setFechaInicio($fechaInicio);
        $this->setFechaFin($fechaFin);
    }

    private function getPatasActivas($Patas, $fecha = false)
    {
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        foreach ($Patas as $key => $pata) {
            
            $afiliados_pata = explode(",", $pata);
            $afiliados_activos = array();
            foreach ($afiliados_pata as $afiliado) {
                $Activo = $this->isActivedAfiliado($afiliado, 1, $fecha);
                
                if ($Activo)
                    array_push($afiliados_activos, $afiliado);
            }
            $Patas[$key] = $afiliados_activos;
        }
        
        return $Patas;
    }

    private function getPatas($id_usuario, $red = 1)
    {
        $usuario = new $this->afiliado();
        
        $this->getAfiliadosBy($id_usuario, 1, "RED", "", $id_usuario);
        $afiliados = $this->getAfiliados();
        
        $Patas = array();
        
        foreach ($afiliados as $id_afiliado) {
            
            $usuario->getAfiliadosDebajoDe($id_afiliado, $red, "RED", 0, 0);
            
            $afiliados_id = $id_afiliado;
            
            if ($usuario->getIdAfiliadosRed())
                $afiliados_id .= "," . implode(",", $usuario->getIdAfiliadosRed());
            
            $Patas[$id_afiliado] = $afiliados_id;
            
            $usuario->setIdAfiliadosRed();
        }
        
        return $Patas;
    }

    private function getPuntosPatas($id_usuario, $red)
    {
        $usuario = new $this->afiliado();
        
        $this->getAfiliadosBy($id_usuario, 1, "RED", "", $id_usuario);
        $afiliados = $this->getAfiliados();
        
        $Patas = array();
        
        foreach ($afiliados as $id_afiliado) {
            $puntos = $usuario->getComprasPersonalesIntervaloDeTiempo($id_afiliado, $red, $this->fechaInicio, $this->fechaFin, "0", "0", "PUNTOS");
            
            $puntos += $usuario->getVentasTodaLaRed($id_afiliado, $red, "RED", "EQU", 0, $this->fechaInicio, $this->fechaFin, "0", "0", "PUNTOS");
            
            $Patas[$id_afiliado] = $puntos;
        }
        return $Patas;
    }

    private function setParametrosTitulos($titulos)
    {
        $parametros = array();
        
        foreach ($titulos as $titulo) {
            
            $multiplo = $titulo->porcentaje;
            $unidad = $titulo->valor;
            $variante = $titulo->ganancia;
            $id_titulo = $titulo->orden;
            
            $Patas = array();
            $valorPata = 0;
            
            for ($pata = 0; $pata < 3; $pata ++) {
                
                $isVariante = $multiplo == $pata; // TODO : &&$pata>0;
                
                if ($isVariante) {
                    $unidad = $variante;
                    $valorPata = 0;
                }
                
                $valorPata += $unidad;
                
                array_push($Patas, $valorPata);
                
                if ($pata == 0 && $isVariante) {
                    $valorPata = $titulo->valor;
                    $unidad = $titulo->valor;
                }
            }
            
            $parametros[$id_titulo] = $Patas;
        }
        
        return $parametros;
    }

    private function getTitulo($param = "", $where = "")
    {
        if ($where)
            $where = " WHERE " . $where;
        
        $query = "SELECT
					    *
					FROM
					    cat_titulo
					$where
					ORDER BY orden ASC";
        
        $q = $this->db->query($query);
        $result = $q->result();
        
        
        if (! $result)
            return false;              
        
        if ($param && isset($result->$param)) 
           $result =  $result[0]->$param ;
        else if ($param===0)
           $result = $result[0] ;  
        
        return $result;
    }

    function setFechaQuincena($fecha = false)
    {
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $fecha_sub = new DateTime($fecha);
        
        $dateAux = new DateTime();
        $dateAux->setDate(date('Y', strtotime($fecha)), date('m', strtotime($fecha)), 15);
        $quincena = date_format($dateAux, 'Y-m-d');
        
        $interval = $dateAux->diff($fecha_sub);
        $result = intval($interval->format('%a'));
        
        if ($result < 7)
            return $quincena;
        
        return $fecha;
    }

    function isActived($id_usuario, $id_bono = 0, $red = 1, $fecha = '')
    {
        $this->setFechaInicio($this->getPeriodoFecha("DIA", "INI", $fecha));
        $this->setFechaFin($this->getPeriodoFecha("DIA", "FIN", $fecha));
        
        // if($id_bono==1)
        // $fecha = $this->setFechaQuincena($fecha);
        $isPaid = $this->isPaid($id_usuario, $id_bono);
        
        if ($isPaid) {
            return false;
        }
        
        $isActived = $this->isActivedAfiliado($id_usuario, $red, $fecha, $id_bono);
        
        $isScheduled = $this->isScheduled($id_usuario, $id_bono, $this->fechaFin);
        
        log_message('DEV', "ID : $id_usuario -> " . intval($isPaid) . " | " . intval($isActived) . " | " . intval($isScheduled));
        
        if (! $isActived || ! $isScheduled) {
            return false;
        }
        
        return true;
    }

    function isCliente($id)
    {
        $q = $this->db->query("select id_red from afiliar where id_afiliado=" . $id . " order by id_red ASC");
        $q = $q->result();
        return $q ? ($q[0]->id_red == 2) : true;
    }

    function isActivedbyPuntos($id_usuario, $red = 1, $fecha = false, $bono = false)
    {
        if ($this->isCliente($id_usuario))
            return false;
        
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $isRecent = date('Y-m', strtotime($fecha)) == date('Y-m');
        if (! $isRecent)
            return $this->isActivedAfiliado_bk($id_usuario, $red, $fecha, $bono);
        
        $q = $this->db->query("select * from red where id_usuario = $id_usuario and estatus = 'ACT'");
        $q = $q->result();
        
        if (! $q)
            return false;
        
        return true;
    }

    function isActivedConsumidor($id_usuario = 2)
    {
        $fechaInicio = $this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin = $this->getPeriodoFecha("UNI", "FIN", '');
        
        $puntos = $this->getEmpresa("puntos_personales");
        $usuario = new $this->afiliado();
        $valor = $usuario->getComprasPersonalesIntervaloDeTiempo($id_usuario, 1, $fechaInicio, $fechaFin, "0", "0", "PUNTOS");
        
        $Pasa = ($puntos <= $valor) ? true : false;
        
        return $Pasa;
    }

    function activos_procedure($id_usuario = 2)
    {
        if ($this->isCliente($id_usuario))
            $this->isClienteMembresia($id_usuario);
        
        $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
        $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
        
        $condicion = $this->getEmpresa("puntos_personales");
        
        $puntos = $this->getValoresby($id_usuario, $fechainicio, $fechafin);
        $this->setCalculoDatos($id_usuario, $puntos, $fechainicio, $fechafin);
        
        if ($puntos < $condicion) {
            $condicion *= 2;
            $fechainicio = $this->getPeriodoFecha("MES", "INI", '');
            $fechafin = $this->getPeriodoFecha("MES", "FIN", '');
            $puntos = $this->getValoresby($id_usuario, $fechainicio, $fechafin);
        }
        
        $activo = $puntos < $condicion;
        
        $this->setRedActivo($id_usuario, $activo);
        $this->setCalculoBonos($id_usuario, $fechainicio, $fechafin);
    }

    function isClienteMembresia($id_usuario = 2)
    {
        $fechainicio = $this->getPeriodoFecha("UNI", "INI", '');
        $fechafin = $this->getPeriodoFecha("UNI", "FIN", '');
        
        $puntos = $this->getValoresby($id_usuario, $fechainicio, $fechafin, 5);
        
        if ($puntos > 0)
            $this->db->query("update afiliar set id_red = 1 , debajo_de = 0 where id_afiliado=" . $id_usuario);
        
        return true;
    }

    private function setCalculoBonos($id_usuario, $fechainicio, $fechafin)
    {
        $default = array(
            "tipo" => 1,
            "item" => 0,
            "condicion" => "PUNTOS"
        );
        $bonos = array(
            $default
        );
        
        foreach ($bonos as $bono) {
            $valor = $this->getValoresby($id_usuario, $fechainicio, $fechafin, $bono["tipo"], $bono["item"], $bono["condicion"]);
            $this->setCalculoDatos($id_usuario, $valor, $fechainicio, $fechafin, $bono["tipo"], $bono["item"], $bono["condicion"]);
        }
    }

    private function setCalculoDatos($id_usuario, $valor, $fechainicio, $fechafin, $tipo = 0, $item = 0, $set = "PUNTOS")
    {
        $this->db->query("DELETE FROM calculo_bonos
                        where id_afiliado = $id_usuario
                        and tipo = '$tipo' and item = '$item'
                        AND fecha BETWEEN $fechainicio AND CONCAT('$fechafin', ' 23:59:59')
                        AND condicion = '$set'");
        
        $dato = array(
            "id_afiliado" => $id_usuario,
            "condicion" => $set,
            "tipo" => $tipo,
            "item" => $item,
            "valor" => $valor
        );
        
        $this->db->insert("calculo_bonos", $dato);
    }

    private function setRedActivo($id_usuario, $estatus = false)
    {
        $estatus = ($estatus) ? "ACT" : "DES";
        
        $q = $this->db->query(" update red set estatus = '$estatus' where id_usuario = $id_usuario");
    }

    private function getValoresby($id_usuario, $fechainicio, $fechafin, $tipo = 0, $mercancia = 0, $set = "PUNTOS")
    {
        $set = ($set == "COSTO") ? "m.costo" : "m.puntos_comisionables";
        
        if (! $fechainicio || ! $fechafin) {
            $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
            $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
        }
        
        $where = "";
        
        if ($tipo != 0) {
            $in = (gettype($tipo) == "array") ? implode(",", $tipo) : $tipo;
            $where .= " AND m.id_tipo_mercancia in ($in)";
        }
        
        if ($mercancia != 0) {
            $in = (gettype($mercancia) == "array") ? implode(",", $mercancia) : $mercancia;
            $where .= " AND m.id in ($in)";
        }
        
        $query = "SELECT ( SELECT
						(CASE WHEN SUM($set * cvm.cantidad)
        				 THEN SUM($set * cvm.cantidad)
        				 ELSE 0 END) cart_val
        				FROM
        				    venta v,
        				    cross_venta_mercancia cvm,
                            mercancia m
        				WHERE
							m.id = cvm.id_mercancia
        				    AND v.id_venta = cvm.id_venta
        				    AND v.id_user in ($id_usuario)
        				    AND v.id_estatus = 'ACT'
        				    AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                            +
                            (SELECT
								 (CASE WHEN SUM($set * p.cantidad)
									THEN SUM($set * p.cantidad) ELSE 0 END)
                                 cedi_val
							FROM
								pos_venta o,
								venta v,
							    pos_venta_item p,
                                mercancia m
							WHERE
								p.id_venta = o.id_venta AND m.id = p.item
								AND o.id_venta = v.id_venta
								AND v.id_user in ($id_usuario)
								AND v.id_estatus = 'ACT'
								AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                                total ";
        
        $q = $this->db->query($query);
        
        $q = $q->result();
        
        if (! $q)
            return 0;
        
        return $q[0]->total;
    }

    private function isRedActivo($id_usuario = 2)
    {
        $q = $this->db->query("select * from red where id_usuario = $id_usuario");
        $q = $q->result();
        
        if (! $q)
            $this->db->query("insert into red values (1,$id_usuario,0,'DES',0)");
        
        return true;
    }

    function isActivedAfiliado($id_usuario, $red = 1, $fecha = '', $bono = false)
    {
        if ($id_usuario == 2)
            return true;
        
        $fechaInicio = ($this->fechaInicio) ? $this->fechaInicio : $this->getPeriodoFecha("MES", "FIN", $fecha);
        $fechaFin = ($this->fechaFin) ? $this->fechaFin : $this->getPeriodoFecha("DIA", "FIN", $fecha);
        
        $inscripcion = $this->getInscripcionUsuario($id_usuario, $fechaFin, "AND m.id not in (4)");
        
        if (! $inscripcion)
            return false;
        
        $membresia = $this->valDistribuidor($id_usuario, $inscripcion, $fechaInicio, $fechaFin);
        
        $Pasa = ($membresia) ? true : false;
        
        return $Pasa;
    }

    private function valDistribuidor($id_usuario, $inscripcion, $fechaInicio, $fechaFin, $set = false)
    {
        $usuario = new $this->afiliado();
        $membresia = $inscripcion->id_mercancia;
        $hoy = $fechaFin;
        
        if ($membresia == 1) {
            
            $fechaInicio = $this->getPeriodoFecha("SEM", "INI", $inscripcion->fecha);
            $fechaFin = $this->getPeriodoFecha("SEM", "FIN", $this->getAnyTime($fechaInicio, '27 days'));
            
            $puntos = 800;
            $productos = 2;
            
            $venta = $this->getVentaMercancia($id_usuario, $fechaInicio, $fechaFin, 1);
            $valor = $this->sumatoria($venta, "costo_total");
            
            log_message('DEV', "Directa :: $valor ");
            
            if ($valor < $puntos) {
                $productos -= $this->sumatoria($venta, "cantidad");
                $clientes = $this->getClientes($id_usuario, $fechaInicio, $fechaFin);
                $clientes = $clientes ? implode(",", $clientes) : 0;
                $venta = $this->getVentaMercancia($clientes, $fechaInicio, $fechaFin, 1);
                
                $valor = $this->sumatoria($venta, "costo_total");
                
                log_message('DEV', "Indirecta :: $valor | " . json_encode($venta));
                
                if ($valor < $puntos)
                    return false;
                
                $registro = $this->setIndirecto($productos, $venta);
                $infecha = date('Y-m-d', strtotime($registro[1]));
                
                $fechaInicio = $this->getPeriodoFecha("SEM", "INI", $infecha);
                $fechaFin = $this->getPeriodoFecha("SEM", "FIN", $this->getAnyTime($fechaInicio, '27 days'));
                log_message('DEV', "registro ($id_usuario) :: $fechaInicio -- $fechaFin | " . json_encode($registro));
                
                foreach ($registro[0] as $id_venta => $valor)
                    $this->loadIndirecto($id_usuario, $id_venta, $fechaInicio, $fechaFin, $valor);
                
                $fuera = $fechaInicio > $hoy || $fechaFin < $hoy;
                if ($fuera)
                    return false;
            }
        }
        
        return (! $set) ? $membresia : array($membresia,$fechaInicio,$fechaFin);
    }

    private function setIndirecto($puntos, $venta)
    {
        $monto = 0;
        $registro = array();
        $fecha = date('Y-m-d');
        foreach ($venta as $v) {
            if ($monto < $puntos) {
                $total = ($monto + $v->cantidad > $puntos) ? ($puntos - $monto) : $v->cantidad;
                $monto += $total;
                if (! isset($registro[$v->id_venta]))
                    $registro[$v->id_venta] = $total;
                else
                    $registro[$v->id_venta] += $total;
                $fecha = $v->fecha;
            } else {
                break;
            }
        }
        return array($registro,$fecha);
    }

    function grupo_array($datos, $atributo, $clave)
    {
        $grupo = array();
        foreach ($datos as $dato) {
            $total = $dato->$atributo;
            if (! isset($grupo[$dato->$clave]))
                $grupo[$dato->$clave] = $total;
            else
                $grupo[$dato->$clave] += $total;
        }
        return $grupo;
    }

    private function loadIndirecto($id_usuario, $id_venta, $fechaInicio, $fechaFin, $valor = 0)
    {
        $isRegistered = $this->isIndirecto($id_venta, $id_usuario, $fechaInicio, $fechaFin);
        
        $datos = array(
            "id_usuario" => $id_usuario,
            "fin" => $fechaFin,
            "inicio" => $fechaInicio,
            "id_venta" => $id_venta,
            "valor" => $valor
        );
        
        if (! $isRegistered)
            $this->db->insert("activo_indirecto", $datos);
        else {
            $this->db->where('id_venta', $id_venta);
            $this->db->update('activo_indirecto', $datos);
        }
    }

    private function getVenta($id_venta)
    {
        $q = $this->db->query("SELECT * FROM venta WHERE id_venta = $id_venta");
        
        $q = $q->result();
        return $q;
    }

    private function isIndirecto($id_venta, $id_usuario = false, $fechaInicio = false, $fechaFin = false)
    {
        $where = "";
        
        if ($id_usuario)
            $where .= " AND id_usuario = $id_usuario";
        
        if ($fechaInicio && $fechaFin)
            $where .= " AND inicio = '$fechaInicio' AND fin = '$fechaFin'";
        
        $q = $this->db->query("SELECT * FROM activo_indirecto
                             WHERE  id_venta = $id_venta $where");
        
        $q = $q->result();
        return $q;
    }

    function sumatoria($datos, $atributo)
    {
        $valor = 0;
        if (! $datos)
            return 0;
        foreach ($datos as $dato) {
            $valor += $dato->$atributo;
        }
        return $valor;
    }

    function getActivacion($id_usuario, $fecha = false)
    {
        $inscripcion = $this->getInscripcionUsuario($id_usuario, $fecha);
        if (! $inscripcion)
            return "Público";
        $mercancia = $this->getMercancia($inscripcion->id_mercancia);
        
        return $mercancia ? $mercancia->item : "Público";
    }

    private function getMercancia($id)
    {
        $q = $this->db->query("SELECT * FROM items WHERE id = $id");
        $q = $q->result();
        return $q ? $q[0] : false;
    }

    function getInscripcionUsuario($id_usuario, $fecha = false, $where = "")
    {
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $fechaInicio = $this->getPeriodoFecha("UNI", "INI", $fecha);
        
        $venta = $this->getVentaMercancia($id_usuario, $fechaInicio, $fecha, 5, false, $where, true);
        if ($venta)
            log_message('DEV', "inscripcion :: " . $venta[0]->id_mercancia);
        return $venta ? $venta[0] : false;
    }

    function setAfiliadosBono($usuarios, $historial)
    {
        foreach ($usuarios as $key => $usuario) {
            $id_usuario = $usuario->id_afiliado;
            $is_Paid = $this->isPaidHistorial($id_usuario, $historial);
            
            if ($is_Paid)
                unset($usuarios[$key]);
        }
        
        return $usuarios;
    }

    function getIndicadores($id_usuario, $periodo = "QUI", $fecha = '')
    {
        $indicadores = array();
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $fecha);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $fecha);
        
        $personal = $this->getIndicadorPersonal($id_usuario, $fechaInicio, $fechaFin);
        
        $red = $this->getIndicadorRed($id_usuario, $fechaInicio, $fechaFin);
        
        $indicadores = array($personal,$red);
        
        return $indicadores;
    }

    private function getIndicadorPersonal($id, $fechaInicio, $fechaFin)
    {
        $usuario = new $this->afiliado();
        $puntos = $usuario->getComprasPersonalesIntervaloDeTiempo($id, 1, $fechaInicio, $fechaFin, "0", "0", "PUNTOS");
        $valor = $usuario->getComprasPersonalesIntervaloDeTiempo($id, 1, $fechaInicio, $fechaFin, "0", "0", "COSTO");
        
        $personal = array($puntos,$valor);
        return $personal;
    }

    private function getIndicadorRed($id, $fechaInicio, $fechaFin)
    {
        $usuario = new $this->afiliado();
        $puntos = $usuario->getVentasTodaLaRed($id, 1, "RED", "EQU", 0, $fechaInicio, $fechaFin, "0", "0", "PUNTOS");
        $valor = $usuario->getVentasTodaLaRed($id, 1, "RED", "EQU", 0, $fechaInicio, $fechaFin, "0", "0", "COSTO");
        
        $red = array($puntos,$valor);
        return $red;
    }

    private function getVentaMercancia($id_usuario, $fechaInicio, $fechaFin, $tipo = false, $item = false, $where = "", $order = false, $group = false)
    {
        if ($tipo)
            $where .= " AND m.id_tipo_mercancia in ($tipo)";
        
        if ($item)
            $where .= " AND cvm.id_mercancia in ($item)";
        
        if ($group)
            $group = "GROUP BY cvm.id_mercancia";
        else
            $group = "";
        
        if ($order)
            $order = "ORDER BY v.fecha DESC,v.id_venta DESC";
        else
            $order = "";
        
        $query = "SELECT
									*
								FROM
									cross_venta_mercancia cvm,
									mercancia m,
                                    items i,
									venta v
								WHERE
                                    i.id = m.id
									AND m.id = cvm.id_mercancia
									AND cvm.id_venta = v.id_venta
									$where
									AND v.id_user in ($id_usuario)
									AND v.id_estatus = 'ACT'
									AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'
								$group
								$order";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        return $q;
    }

    private function getComprasUnidades($id_usuario = 2, $fechaInicio, $fechaFin, $tipo = 0, $mercancia = 0, $red = 1)
    {
        if (! $id_usuario)
            return 0;
        
        if (! $fechaInicio || ! $fechaFin) {
            $fechaInicio = $this->getPeriodoFecha("QUI", "INI", '');
            $fechaFin = $this->getPeriodoFecha("QUI", "FIN", '');
        }
        
        $where = "";
        
        if ($tipo != 0) {
            $in = (gettype($tipo) == "array") ? implode(",", $tipo) : $tipo;
            $where .= " AND i.id_tipo_mercancia in ($in)";
        }
        
        if ($mercancia != 0) {
            $in = (gettype($mercancia) == "array") ? implode(",", $mercancia) : $mercancia;
            $where .= " AND i.id in ($in)";
        }
        
        $cart = "SELECT
						    (CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
						FROM
						    venta v,
						    cross_venta_mercancia cvm,
						    items i
						WHERE
								i.id = cvm.id_mercancia
						        AND cvm.id_venta = v.id_venta
						        AND v.id_user in ($id_usuario)
						        AND i.red = $red
						        $where
						        AND v.id_estatus = 'ACT'
						        AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        
        $cedi = "SELECT
						    (CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
						FROM
						    venta v,
						    pos_venta_item cvm,
						    items i
						WHERE
								i.id = cvm.item
						        AND cvm.id_venta = v.id_venta
						        AND v.id_user in ($id_usuario)
						        AND i.red = $red
						        $where
						        AND v.id_estatus = 'ACT'
						        AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        
        $query = "SELECT ($cart)+($cedi) unidades";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return 0;
        
        return intval($q[0]->unidades);
    }

    private function isPaid($id_usuario, $id_bono)
    {
        $query = "SELECT
						*
					FROM
						comision_bono c,
						comision_bono_historial h
					WHERE
						c.id_bono_historial = h.id
						AND c.id_bono = h.id_bono
						AND h.id_bono = $id_bono
						AND c.id_usuario = $id_usuario
						AND h.fecha BETWEEN '$this->fechaInicio' AND '$this->fechaFin'
						#AND c.valor > 0";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return false;
        
        $valid = (sizeof($q) > 0) ? true : false;
        
        return $valid;
    }

    private function isPaidHistorial($id_usuario, $historial)
    {
        $query = "SELECT
						*
					FROM
						comision_bono c,
						comision_bono_historial h
					WHERE
						c.id_bono_historial = h.id
						AND c.id_bono = h.id_bono
						AND h.id = $historial
						AND c.id_usuario = $id_usuario
						#AND c.valor > 0";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return false;
        
        $valid = (sizeof($q) > 0) ? true : false;
        
        return $valid;
    }

    private function isValidDate($id_usuario, $id_bono, $fecha = false, $dia = false)
    {
        $bono = $this->getBono($id_bono);
        
        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;
        
        if ($mes_inicio <= 0) {
            return true;
        }
        
        if ($fecha)
            $fecha = "'" . $fecha . "'";
        else
            $fecha = "NOW()";
        
        $mes_inicio *= 2;
        
        $select = "DATE_FORMAT(created, '%Y-%m') < DATE_FORMAT(DATE_SUB($fecha, INTERVAL $mes_inicio WEEK),'%Y-%m')";
        
        if ($dia) {
            $select = "created < DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH)";
        }
        
        $query = "SELECT
					    $select 'valid'
					FROM
					    users
					WHERE
					    id = " . $id_usuario;
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return false;
        
        $valid = ($q[0]->valid == 1) ? true : false;
        
        return $valid;
    }

    private function isScheduled($id_usuario, $id_bono, $fecha = "")
    {
        $bono = $this->getBono($id_bono);
        
        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;
        $where = "";
        
        if (strlen($fecha) > 2) {
            $fecha = "'" . $fecha . "'";
        } else {
            $fecha = "NOW()";
        }
        
        $limiteInicio = "(CASE WHEN (DATE_FORMAT(fecha,'%d')<16) THEN CONCAT(DATE_FORMAT(fecha,'%Y-%m'),'-15') ELSE LAST_DAY(fecha) END)";
        
        if ($mes_inicio > 0) {
            $where .= "DATE_FORMAT($fecha, '%Y-%m-%d') > " . $limiteInicio;
        }
        
        if ($mes_fin > 0) {
            $mes_fin += $mes_inicio;
            $where .= "DATE_FORMAT($fecha, '%Y-%m-%d') <= " . $limiteInicio;
        }
        
        if ($where == "")
            return true;
        
        $query = "SELECT
					    $where 'valid'
					FROM
					    venta
					WHERE
                        id_estatus = 'ACT'
					    AND id_user = " . $id_usuario . " ORDER BY fecha asc
                    LIMIT 1";
        
        $q = $this->db->query($query);
        log_message('DEV', " >>:: $query");
        $q = $q->result();
        
        if (! $q)
            return false;
        
        $valid = ($q[0]->valid == 1) ? true : false;
        
        return $valid;
    }

    function getValorBonoBy($id_bono, $parametro)
    {
        switch ($id_bono) {
            
            case 1:
                
                return $this->getValorBonoClientes($parametro);
                
                break;
            
            case 2:
                
                return $this->getValorBonoInicioRapido($parametro);
                
                break;
            
            case 3:case 4:case 7:
                
                return $this->getValorBonoMatriz($parametro,$id_bono);
                
                break;
            
            case 5:
                
                return $this->getValorBonoNavidad($parametro);
                
                break;
            
            case 6:
                
                return $this->getValorBonoRangos($parametro);
                
                break;
            
            default:
                return 0;
                break;
        }
    }

    private function getValorBonoClientes($parametro)
    {
        $valores = $this->getBonoValorNiveles(1);
        
        $bono = $this->getBono(1);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $afiliados = $this->getClientes($id_usuario, $fechaInicio, $fechaFin);
        
        $monto = $this->getMontoClientes($id_usuario, $valores, $afiliados, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function getClientes($id, $fechaInicio, $fechaFin)
    {
        if (! $fechaFin)
            $fechaFin = date('Y-m-d');
        
        $this->getDirectosBy($id, 1);
        $afiliados = $this->getAfiliados();
        
        $inscritos = array();
        
        foreach ($afiliados as $afiliado) {
            $valor = 0;
            if ($afiliado > 0)
                $inscripcion = $this->getInscripcionUsuario($afiliado, $fechaFin, "AND m.id in (4)");
            // if($afiliado>0)
            if ($inscripcion) {
                $valor2 = $this->getComprasUnidades($afiliado, $inscripcion->fecha, $fechaFin, 5, "1,2,3");
                log_message('DEV', "clientes >> $afiliado : " . sizeof($inscripcion) . " | $valor2");
                if ($valor2 == 0)
                    array_push($inscritos, $afiliado);
            }
        }
        
        return $inscritos;
    }

    private function getClientes_bk($valores, $id)
    {
        $where = "";
        
        $afiliados = array();
        
        $this->getAfiliadosBy($id, 1, "RED", $where, $id, 2);
        $afiliados = $this->getAfiliados();
        $afiliados = (! $afiliados) ? array(array(1)) : array($this->setArrayNoNull($afiliados));
        
        foreach ($valores as $nivel) {
            
            if ($nivel->nivel > 0) {
                $this->getAfiliadosBy($id, $nivel->nivel, $nivel->condicion_red, $where, $id);
                
                $getAfiliados = $this->getAfiliados();
                $clientes = array();
                foreach ($getAfiliados as $afiliado) {
                    $this->getAfiliadosBy($afiliado, 1, "RED", $where, $afiliado, 2);
                    $getClientes = $this->getAfiliados();
                    $getClientes = $this->setArrayNoNull($getClientes);
                    if ($getClientes)
                        array_push($clientes, implode(",", $getClientes));
                }
                log_message('DEV', ":::>> " . $nivel->nivel . " : " . json_encode($clientes));
                $getAfiliados = $clientes ? explode(",", implode(",", $clientes)) : false;
                array_push($afiliados, $getAfiliados);
            }
        }
        
        return $afiliados;
    }

    private function setArrayNoNull($arr)
    {
        if (! $arr)
            $arr = false;
        else if (sizeof($arr) == 1)
            array_push($arr, 1);
        
        return $arr;
    }

    private function getMontoClientes($id_usuario, $valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        $usuario = new $this->afiliado();
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($afiliados) && ($i > 0); // ($i>0)&&[$lvl]
            if ($Corre) {
                log_message('DEV', "afiliados :: " . json_encode($afiliados));
                $per = $valores[$i]->valor; // /100;
                                            // foreach ($afiliados[$lvl] as $afiliado){[$lvl]
                $afiliado = implode(",", $afiliados);
                $venta = $this->getVentaMercancia($afiliado, $fechaInicio, $fechaFin, 1);
                
                $cantidad = $this->setCantidadIndirecta($venta, $id_usuario);
                
                $cantidad *= $per;
                // $activoAfiliado = $this->isActivedAfiliado($afiliado);
                log_message('DEV', "->> c $afiliado : $i | $per | " . $cantidad . " | " . $monto);
                $monto += $cantidad;
                // TODO:}
                $lvl ++;
            }
        }
        return $monto;
    }

    private function setCantidadIndirecta($venta, $id_usuario)
    {
        $grupo_venta = $this->grupo_array($venta, "cantidad", "id_venta");
        
        $cantidad = 0;
        foreach ($grupo_venta as $id_venta => $valor) {
            $cantidad += $valor;
            $Activacion = $this->isIndirecto($id_venta, $id_usuario);
            if ($Activacion) {
                $cantidad -= $Activacion[0]->valor;
                $grupo_venta[$id_venta] -= $Activacion[0]->valor;
            }
        }
        return $cantidad;
    }

    private function getValorBonoInicioRapido($parametro)
    {
        $valores = $this->getBonoValorNiveles(2);
        
        $bono = $this->getBono(2);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $afiliados = $this->getAfiliadosInicioRapido($id_usuario, 1, $fechaInicio, $fechaFin);
        
        $monto = $this->getMontoInicioRapido($id_usuario, $afiliados, $valores, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function getAfiliadosInicioRapido($id, $nivel, $fechaInicio, $fechaFin)
    {
        $where = ""; // " AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        
        $afiliados = array();
        for ($i = 1; $i <= $nivel; $i ++) {
            
            $this->getDirectosBy($id, $i, $where);
            $directos = $this->getAfiliados();
            // log_message('DEV',">> ".json_encode($directos));
            array_push($afiliados, implode(",", $directos));
        }
        
        $afiliados = implode(",", $afiliados);
        $afiliados = explode(",", $afiliados);
        
        // log_message('DEV',">>> ".json_encode($afiliados));
        return $afiliados;
    }

    private function getMontoInicioRapido($id_usuario, $afiliados, $valores, $fechaInicio, $fechaFin, $red = 1)
    {
        $inscritos = array();
        
        // log_message('DEV',"afiliados: ".json_encode($afiliados));
        
        foreach ($afiliados as $afiliado) {
            $valor = 0;
            if ($afiliado > 0)
                $valor = $this->getComprasUnidades($afiliado, $fechaInicio, $fechaFin, 5, 1);
            // log_message('DEV',">> $afiliado : ".$valor);
            if ($valor > 0)
                // if($afiliado>0)
                array_push($inscritos, $afiliado);
        }
        
        $monto = 0;
        $cantidad = sizeof($inscritos);
        
        $monto = $valores[1]->valor;
        
        if ($cantidad > 2)
            $monto *= 2;
        
        log_message('DEV', "Patrocinio ::>> " . json_encode($inscritos) . " : $monto X $cantidad ");
        
        $monto *= $cantidad;
        
        return $monto;
    }

    private function getValorBonoInicial($parametro)
    {
        $valores = $this->getBonoValorNiveles(2);
        
        $bono = $this->getBono(2);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $afiliados = $this->getAfiliadosInicial($valores, $id_usuario, $fechaInicio, $fechaFin);
        
        $monto = $this->getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function getAfiliadosInicial($valores, $id, $fechaInicio, $fechaFin)
    {
        $where = ""; // " AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        
        $afiliados = array();
        
        foreach ($valores as $nivel) {
            
            if ($nivel->nivel > 0) {
                
                $this->getDirectosBy($id, $nivel->nivel, $where);
                array_push($afiliados, $this->getAfiliados());
            }
        }
        
        return $afiliados;
    }

    private function getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        $usuario = new $this->afiliado();
        $afiliados = $this->setScheduled($valores, $afiliados, $fechaInicio, 2);
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                $per = $valores[$i]->valor / 100;
                // foreach ($afiliados[$lvl] as $afiliado){
                $afiliado = implode(",", $afiliados[$lvl]);
                $valor = $usuario->getCalculoPersonal($afiliado, $fechaInicio, $fechaFin, "0", "0", "PUNTOS");
                $valor *= $per;
                // $activoAfiliado = $this->isActivedAfiliado($afiliado);
                log_message('DEV', "->> $afiliado : $i | " . ($per * 100) . " % | " . $valor . " | " . $monto);
                $monto += $valor;
                // TODO:}
                $lvl ++;
            }
        }
        return $monto;
    }

    private function setScheduled($valores, $afiliados, $fechaInicio, $id_bono = 1)
    {
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $afiliados_scheduled = array();
            $Corre = isset($afiliados[$i]);
            if ($Corre) {
                foreach ($afiliados[$i] as $afiliado) {
                    $isScheduled = $this->isScheduled($afiliado, $id_bono, $fechaInicio);
                    if ($isScheduled) {
                        // log_message('DEV', " >>-> isScheduled [$afiliado] :: " . intval($isScheduled));
                        array_push($afiliados_scheduled, $afiliado);
                    }
                }
                $afiliados[$i] = $afiliados_scheduled;
            }
        }
        
        return $afiliados;
    }

    private function setActivedAfiliados($valores, $afiliados, $fecha, $id_bono = 1)
    {
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $afiliados_actived = array();
            $Corre = isset($afiliados[$i]);
            if ($Corre) {
                foreach ($afiliados[$i] as $afiliado) {
                    $activoAfiliado = $this->isActivedAfiliado($afiliado, 1, $fecha, $id_bono);
                    if ($activoAfiliado) {
                        // log_message('DEV', " >->> isActived [$afiliado] :: " . intval($activoAfiliado));
                        array_push($afiliados_actived, $afiliado);
                    }
                }
                $afiliados[$i] = $afiliados_actived;
            }
        }
        
        return $afiliados;
    }

    private function getValorBonoMatriz($parametro,$id_bono = 3)
    {
        $valores = $this->getBonoValorNiveles(3);
        
        $bono = $this->getBono(3);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $membresia = array(
            1 => 3,
            2 => 4,
            3 => 7
        );
        
        $monto = 0;
        
        foreach ($membresia as $key => $mem) {
         if($mem == $id_bono){
            $valores = $this->getBonoValorNiveles($mem);
            $valores = $this->getValoresMatriz($id_usuario, $valores, $fechaInicio, $fechaFin);
            $condiciones = $this->getBonoCondiciones($mem);
            $dist = ($mem == 3);
            $afiliados = $this->getAfiliadosMatriz($valores, $id_usuario, $dist);
            log_message('DEV', "___> Regalias :: $key");
            $monto += $this->getMontoMatriz($id_usuario, $valores, $condiciones, $afiliados, $fechaInicio, $fechaFin);
        }
        }
        
        return $monto;
    }

    private function getValoresMatriz($id, $valores, $fechaInicio, $fechaFin)
    {
        $ActivoMatriz = $this->isActivoMatriz($id, $fechaInicio, $fechaFin);
        
        for ($i = (sizeof($valores) - 1); $i > $ActivoMatriz; $i --) {
            unset($valores[$i]);
        }
        
        log_message('DEV', "niveles activo ($ActivoMatriz) :: " . json_encode($valores));
        return $valores;
    }

    private function isActivoMatriz($id, $fechaInicio, $fechaFin)
    {
        $this->getDirectosBy($id, 1);
        $afiliados = $this->getAfiliados();
        
        $puntos = $this->getEmpresa("puntos_personales");
        $usuario = new $this->afiliado();
        $inscritos = array();
        
        foreach ($afiliados as $afiliado) {
            $valor = 0;
            if ($afiliado > 0)
                // $valor=$this->isActivedAfiliado($afiliado,1,$fechaFin,3);
                $valor = $this->getInscripcionUsuario($afiliado, $fechaFin, "AND m.id not in (4)");
            if ($valor)
                // if($valor>0)
                array_push($inscritos, $afiliado);
        }
        
        $afiliados = $inscritos;
        log_message('DEV', "directos :: " . json_encode($afiliados));
        
        $isActivoMatriz = sizeof($afiliados);
        if ($isActivoMatriz > 5)
            $isActivoMatriz = 5;
        
        return $isActivoMatriz;
    }

    private function getAfiliadosMatriz($valores, $id, $clientes = false)
    {
        $where = "";
        
        $afiliados = array();
        
        foreach ($valores as $nivel) {
            
            if ($nivel->nivel > 0) {
                $this->getAfiliadosBy($id, $nivel->nivel, $nivel->condicion_red, $where, $id);
                
                $getAfiliados = $this->getAfiliados();
                $getAfiliados = $this->ClientesGroup($getAfiliados);
                
                $getAfiliados = $this->getClientesMatriz($clientes, $getAfiliados);
                
                array_push($afiliados, $getAfiliados);
            }
        }
        return $afiliados;
    }

    private function getClientesMatriz($clientes, $getAfiliados)
    {
        if ($clientes) {
            $getClientes = array();
            
            foreach ($getAfiliados as $dato) {
                $cliente = $this->getClientes($dato, false, false);
                $cliente = implode(",", $cliente);
                
                if ($cliente)
                    array_push($getClientes, $cliente);
            }
            
            if ($getClientes) {
                $getDatos = implode(",", $getAfiliados);
                $getClientes = implode(",", $getClientes);
                $getDatos = array(
                    $getDatos,
                    $getClientes
                );
                $getAfiliados = explode(",", implode(",", $getDatos));
            }
        }
        return $getAfiliados;
    }

    private function getMontoMatriz($id_usuario, $valores, $condiciones, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        
        $iscond = ($condiciones) && ($condiciones > 0);
        $tipo = $iscond ? $this->setCondicionValores($condiciones, "condicion1") : "0";
        $item = $iscond ? $this->setCondicionValores($condiciones, "condicion2") : "0";
        $where = "AND cvm.costo_unidad > 0";
        $usuario = new $this->afiliado();
        $afiliados = $this->setScheduled($valores, $afiliados, $fechaInicio, 3);
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && $afiliados[$lvl];
            if ($Corre) {
                $per = $valores[$i]->valor; // /100;
                                            // foreach ($afiliados[$lvl] as $afiliado){
                $afiliado = implode(",", $afiliados[$lvl]);
                $venta = $this->getVentaMercancia($afiliado, $fechaInicio, $fechaFin, $tipo, $item,$where);
                
                $valor = $this->setCantidadIndirecta($venta, $id_usuario);
                
                $valor *= $per;
                // $activoAfiliado = $this->isActivedAfiliado($afiliado);
                log_message('DEV', "->> $afiliado : $i | " . $per . " X " . ($valor / $per) . " + " . $monto);
                $monto += $valor;
                // TODO:}
                $lvl ++;
            }
        }
        return $monto;
    }

    function ClientesGroup($datos, $opt = false)
    {
        $group = array();
        $where = ($opt) ? "AND m.id in (4)" : "AND m.id not in (4)";
        
        foreach ($datos as $dato) {
            $inscripcion = $this->getInscripcionUsuario($dato, false, $where);
            
            if ($inscripcion)
                array_push($group, $dato);
        }
        
        return $group;
    }

    private function setCondicionValores($condicion = false, $nombre = "condicion1")
    {
        if (sizeof($condicion) > 1) {
            $condiciones = array();
            $valor_condicion = 0;
            foreach ($condicion as $cond) {
                $val = $cond->$nombre;
                if ($valor_condicion != $val) {
                    $valor = (gettype($val) == "integer") ? $val : "'" . $val . "'";
                    array_push($condiciones, $valor);
                    $valor_condicion = $val;
                }
            }
            return implode(",", $condiciones);
        }
        
        return $condicion[0]->$nombre;
    }

    private function getValorBonoIgualacion($parametro)
    {
        $valores = $this->getBonoValorNiveles(4);
        
        $bono = $this->getBono(4);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $isActivoMatriz = $this->isActivoMatriz($id_usuario, $fechaInicio, $fechaFin);
        
        if (! $isActivoMatriz)
            return 0;
        
        $afiliados = $this->getAfiliadosMatriz($valores, $id_usuario);
        
        $monto = $this->getMontoIgualacion($valores, $afiliados, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function getMontoIgualacion($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        $afiliados = $this->setScheduled($valores, $afiliados, $fechaInicio, 3);
        $afiliados = $this->setActivedAfiliados($valores, $afiliados, $fechaInicio, 3);
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                $per = $valores[$i]->valor / 100;
                foreach ($afiliados[$lvl] as $afiliado) {
                    $valor = $this->getMontoBono($afiliado, 3, $fechaInicio, $fechaFin);
                    $valor *= $per;
                    log_message('DEV', "->> $afiliado : $i | " . ($per * 100) . " % | " . $valor);
                    $monto += $valor;
                }
                $lvl ++;
            }
        }
        return $monto;
    }

    private function getValorBonoNavidad($parametro)
    {
        $valores = $this->getBonoValorNiveles(5);
        
        $bono = $this->getBono(5);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha("ANO", "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha("ANO", "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        $valores = $this->getValoresMatriz($id_usuario, $valores, $fechaInicio, $fechaFin);
        $afiliados = $this->getAfiliadosMatriz($valores, $id_usuario, true);
        
        $monto = $this->getMontoCompras($valores, $afiliados, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function duplicarRed($id_usuario, $red = 1)
    {
        $query = "UPDATE afiliar SET duplicado = 'ACT' WHERE id_red = $red AND id_afiliado = $id_usuario";
        $q = $this->db->query($query);
        return true;
    }

    private function getMontoCompras($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        $usuario = new $this->afiliado();
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                // foreach ($afiliados[$lvl] as $afiliado){
                $per = $valores[$i]->valor;
                $afiliado = ($afiliados[$lvl]) ? implode(",", $afiliados[$lvl]) : 0;
                $valor = $this->getComprasUnidades($afiliado, $fechaInicio, $fechaFin, 1);
                $valor *= $per;
                log_message('DEV', "Nv ->> $afiliado : $i | " . $per . " X " . ($valor / $per) . " + " . $monto);
                $monto += $valor;
                // TODO:}
                $lvl ++;
            }
        }
        return $monto;
    }

    private function isLlenadoRed($opcion_red, $afiliados)
    {
        unset($opcion_red[0]);
        
        $lvl = 0;
        foreach ($opcion_red as $red) {
            $frontales = sizeof($afiliados[$lvl]);
            if ($frontales < $red->valor) {
                $lvl = 0;
                break;
            }
            $lvl ++;
        }
        
        if ($lvl > 0)
            return true;
        
        return false;
    }

    private function getValorBonoPresentador($parametro)
    {
        $valores = $this->getBonoValorNiveles(6);
        
        $bono = $this->getBono(6);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $isLider = $this->isLiderUsuario($id_usuario);
        
        if (! $isLider)
            return 0;
        
        $afiliados = $this->getAfiliadosPresentacion($id_usuario);
        
        $monto = $this->getMontoPresentador($valores, $afiliados, $fechaInicio, $fechaFin);
        
        return $monto;
    }

    private function isLiderUsuario($id_usuario)
    {
        $rango = $this->getRangoAfiliado($id_usuario);
        
        if (! $rango)
            return 0;
         
        $id_rango = $rango->id_rango;
        $entregado = ($rango->entregado == 1);
        $isLider = ($id_rango > 3) || ($id_rango == 3 && $entregado);
        
        return $isLider;
    }

    private function getAfiliadosPresentacion($id_usuario, $red = 1)
    {
        $query = "SELECT
							a.id_afiliado id
						FROM
							afiliar a,
							users u
						WHERE
							u.id = a.id_afiliado
							AND a.id_red = $red
							AND a.presentador = $id_usuario";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return false;
        
        return $q;
    }

    private function getMontoPresentador($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $lvl = 0;
        $usuario = new $this->afiliado();
        $inscritos = array();
        
        if (! $afiliados)
            return 0;
        
        foreach ($afiliados as $afiliado) {
            $valor = 0;
            $afiliado = $afiliado->id;
            if ($afiliado > 0)
                $valor = $this->getComprasUnidades($afiliado, $fechaInicio, $fechaFin, 5);
            // log_message('DEV',">> $afiliado : ".$valor);
            if ($valor > 0)
                // if($afiliado>0)
                array_push($inscritos, $afiliado);
        }
        
        $monto = $valores[1]->valor;
        $cantidad = sizeof($inscritos);
        
        log_message('DEV', " presentador :: >> " . json_encode($inscritos) . " : $monto X $cantidad ");
        
        $monto *= $cantidad;
        
        return $monto;
    }

    private function getValorBonoRangos($parametro)
    {
        $valores = $this->getBonoValorNiveles(7);
        
        $bono = $this->getBono(7);
        $periodo = isset($bono[0]->frecuencia) ? $bono[0]->frecuencia : "UNI";
        
        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
        
        $id_usuario = $parametro["id_usuario"];
        
        log_message('DEV', "between: $fechaInicio - $fechaFin");
        
        $titulo = $this->getRangoAfiliado($id_usuario);
        
        $isCobro = $fechaFin == $$titulo->fecha;
        
        if (! $titulo || !$isCobro)
            return 0;
        
        $monto = $this->getMontoRangos($id_usuario, $valores, $titulo);
        
        return $monto;
    }

    private function getMontoRangos($id_usuario, $valores, $rango)
    {
        $monto = 0;
         
        $id_rango = $rango->id_rango;
        $acumulado = $rango->periodo;
        $valor = $rango->ganancia;
        
        $condicion = $this->getTitulo("porcentaje", "id = " . $id_rango . " OR orden = " . $id_rango);
        $constante = isset($valores[$id_rango]) ? $valores[$id_rango]->valor : $valores[1]->valor;
        
        
        $cumple = $condicion <= $acumulado;
        $duplica = $condicion*$constante;
        $getRangoPagado = $this->getRangoPagado($id_usuario,$id_rango,true);
        $mantenimiento = $acumulado > $duplica || ($getRangoPagado/2) > $duplica;
        $duplicado = $acumulado == $duplica;
        
        if ($duplicado)
            $cumple = true; 
        else if ($mantenimiento)
            $valor /= 3;
        
        log_message('DEV', "titulo ::> ($id_rango) $condicion <= $acumulado --> $valor");
        
        if ($cumple || $mantenimiento) {
            $monto = $valor;
            $this->entregar_rango($id_usuario);
        }
        
        return $monto;
    }

    private function entregar_rango($id_usuario)
    {
        $query = "UPDATE cross_rango_user SET entregado = 1 WHERE id_user = $id_usuario";
        $q = $this->db->query($query);
    }

    private function getBonoCondiciones($id)
    {
        $q = $this->db->query("SELECT * FROM cat_bono_condicion WHERE id_bono = $id");
        $q = $q->result();
        return $q;
    }
    
    private function getRangoPagado($id_usuario, $id_rango = 1,$count = false)
    {
        $id_bono =6;
        
        $rango = $this->getTitulo("ganancia","id = $id_rango");
        
        $query = "SELECT
                		*
                    FROM
                		comision_bono c,
                        comision_bono_historial h
                    WHERE
                		c.id_usuario = $id_usuario
                        AND h.id_bono = c.id_bono
                        AND c.id_bono = $id_bono
                        AND c.id_bono_historial = h.id
                        AND c.valor = $rango";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return ($count) ? 0 : false;
        
        if($count)
            $q= sizeof($q);
        
        return $q;    
        
    }

    private function getMontoBono($id_usuario, $id_bono, $fechaInicio, $fechaFin)
    {
        $query = "SELECT
                		max(c.valor) valor
                    FROM
                		comision_bono c,
                        comision_bono_historial h
                    WHERE
                		c.id_usuario = $id_usuario
                        AND h.id_bono = c.id_bono
                        AND c.id_bono = $id_bono
                        AND c.id_bono_historial = h.id
                        AND h.fecha between '$fechaInicio' and '$fechaFin'";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        if (! $q)
            return 0;
        
        return $q[0]->valor;
    }

    private function getBonoValorNiveles($id)
    {
        $q = $this->db->query("SELECT * FROM cat_bono_valor_nivel WHERE id_bono = $id ORDER BY nivel asc");
        $q = $q->result();
        return $q;
    }

    private function getBono($id)
    {
        $q = $this->db->query("SELECT * FROM bono WHERE id = $id");
        $q = $q->result();
        return $q;
    }

    private function getBonos($where= "") {
        if($where)$where="AND ".$where;
        $q = $this->db->query("SELECT * FROM bono WHERE estatus = 'ACT' $where");
        $q = $q->result();
        return $q;
    }
    
    private function getDirectosBy($id, $nivel, $where = "", $red = 1)
    {
        $query = "SELECT
							a.id_afiliado id,
							a.directo
						FROM
							afiliar a,
							users u
						WHERE
							u.id = a.id_afiliado
							AND a.id_red = $red
							AND a.directo = $id
							$where";
        
        $q = $this->db->query($query);
        
        $datos = $q->result();
        
        if (! $q) {
            return;
        }
        
        $nivel --;
        foreach ($datos as $dato) {
            
            if ($nivel <= 0) {
                
                $this->setAfiliados($dato->id);
            } else {
                $this->getDirectosBy($dato->id, $nivel, $where, $red);
            }
        }
    }

    private function getAfiliadosBy($id, $nivel, $tipo, $where = "", $padre = 2, $red = 1)
    {
        $is = array(
            "DIRECTOS" => "a.directo",
            "RED" => "a.debajo_de"
        );
        
        $query = "SELECT
							a.id_afiliado id,
							a.directo
						FROM
							afiliar a,
							users u
						WHERE
							u.id = a.id_afiliado
							AND a.id_red = $red
							AND a.debajo_de = $id
							$where";
        
        $q = $this->db->query($query);
        
        $datos = $q->result();
        
        if (! $q) {
            return;
        }
        
        $nivel --;
        foreach ($datos as $dato) {
            
            if ($nivel <= 0) {
                
                if ($tipo != "DIRECTOS" || $padre == $dato->directo) {
                    $this->setAfiliados($dato->id);
                }
            } else {
                $this->getAfiliadosBy($dato->id, $nivel, $tipo, $where, $padre, $red);
            }
        }
    }

    private function getEmpresa($attrib = 0)
    {
        $q = $this->db->query("SELECT * FROM empresa_multinivel GROUP BY id_tributaria");
        $q = $q->result();
        
        if (! $q) {
            return 0;
        }
        
        if ($attrib === 0) {
            return $q;
        }
        
        return $q[0]->$attrib;
    }
    
    private function getAfiliacion($id,$red =1,$uno = true) {
        $q = $this->db->query("SELECT * FROM afiliar WHERE id_afiliado = $id and id_red = $red");
        $q = $q->result();
        
        if (! $q)
            return false;
        elseif ($uno)
            $q = $q[0];
        
        return $q;
    }
    
    
    private function getPeriodoFecha($frecuencia, $tipo, $fecha = '')
    {
        if (! $fecha)
            $fecha = date('Y-m-d');
        
        $periodoFecha = array(
            "SEM" => "Semana",
            "QUI" => "Quincena",
            "MES" => "Mes",
            "ANO" => "Ano"
        );
        
        $tipoFecha = array(
            "INI" => "Inicio",
            "FIN" => "Fin"
        );
        
        if ($frecuencia == "UNI") {
            return ($tipo == "INI") ? $this->getInicioFecha() : date('Y-m-d');
        }
        
        if (! isset($periodoFecha[$frecuencia]) || ! isset($tipoFecha[$tipo])) {
            return $fecha;
        }
        
        $functionFecha = "get" . $tipoFecha[$tipo] . $periodoFecha[$frecuencia];
        
        return $this->$functionFecha($fecha);
    }

    function getInicioFecha()
    {
        $query = "SELECT
                        date_format(MIN(created),'%Y-%m-%d') fecha
                    FROM
                        users";
        
        $q = $this->db->query($query);
        $q = $q->result();
        
        $year = new DateTime();
        $year->setDate($year->format('Y'), 1, 1);
        
        if (! $q)
            date_format($year, 'Y-m-d');
        
        return $q[0]->fecha;
    }

    private function getFinSemana($date)
    {
        $offset = strtotime($date);
        
        $dayofweek = date('w', $offset);
        
        if ($dayofweek == 0) {
            return $date;
        } else {
            return date("Y-m-d", strtotime('Next Sunday', strtotime($date)));
        }
    }

    private function getInicioSemana($date)
    {
        $offset = strtotime($date);
        
        $dayofweek = date('w', $offset);
        
        if ($dayofweek == 1) {
            return $date;
        } else {
            return date("Y-m-d", strtotime('last Monday', strtotime($date)));
        }
    }

    private function getFinSemana_bk($date)
    {
        $offset = strtotime($date);
        
        $dayofweek = date('w', $offset);
        
        if ($dayofweek == 6) {
            return $date;
        } else {
            return date("Y-m-d", strtotime('last Saturday', strtotime($date)));
        }
    }

    private function getInicioSemana_bk($date)
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string('6 days'));
        $date = date_format($fecha_sub, 'Y-m-d');
        
        $offset = strtotime($date);
        
        $dayofweek = date('w', $offset);
        
        if ($dayofweek == 0) {
            return $date;
        } else {
            return date("Y-m-d", strtotime('last Sunday', strtotime($date)));
        }
    }

    private function getInicioQuincena($date)
    {
        $dateAux = new DateTime();
        
        if (date('d', strtotime($date)) <= 15) {
            $dateAux->setDate(date('Y', strtotime($date)), date('m', strtotime($date)), 1);
            return date_format($dateAux, 'Y-m-d');
        } else {
            $dateAux->setDate(date('Y', strtotime($date)), date('m', strtotime($date)), 16);
            return date_format($dateAux, 'Y-m-d');
        }
    }

    private function getFinQuincena($date)
    {
        $dateAux = new DateTime();
        
        if (date('d', strtotime($date)) <= 15) {
            $dateAux->setDate(date('Y', strtotime($date)), date('m', strtotime($date)), 15);
            return date_format($dateAux, 'Y-m-d');
        } else {
            return date('Y-m-t', strtotime($date));
        }
    }

    private function getInicioMes($date)
    {
        $dateAux = new DateTime();
        $dateAux->setDate(date('Y', strtotime($date)), date('m', strtotime($date)), 1);
        return date_format($dateAux, 'Y-m-d');
    }

    private function getFinMes($date)
    {
        return date('Y-m-t', strtotime($date));
    }

    private function getInicioAno($date)
    {
        $year = new DateTime($date);
        $year->setDate($year->format('Y'), 1, 1);
        return date_format($year, 'Y-m-d');
    }

    private function getFinAno($date)
    {
        $year = new DateTime($date);
        $year->setDate($year->format('Y'), 12, 31);
        return date_format($year, 'Y-m-d');
    }

    private function getAnyTime($date, $time = '1 month',$add= true)
    {
        $fecha_sub = new DateTime($date);
        if($add)
            date_add($fecha_sub, date_interval_create_from_date_string("$time"));
        else
            date_sub($fecha_sub, date_interval_create_from_date_string("$time"));
        
        $date = date_format($fecha_sub, 'Y-m-d');
        
        return $date;
    }

    private function getNextTime($date, $time = 'month')
    {
        $fecha_sub = new DateTime($date);
        date_add($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, 'Y-m-d');
        
        return $date;
    }

    private function getLastTime($date, $time = 'month')
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, 'Y-m-d');
        
        return $date;
    }

    function reporte_activos($fechaInicio = "", $fechaFin = "", $id = 2, $status = true)
    {
        $this->setFechaInicio($fechaInicio);
        $this->setFechaFin($fechaFin);
        
        $usuario = new $this->afiliado();
        $q = $this->db->query('select id,profundidad from tipo_red');
        $red = $q->result();
        
        $afiliadosEnLaRed = array();
        
        $usuario->getAfiliadosDebajoDe($id, $red[0]->id, "RED", 0, $red[0]->profundidad);
        $afiliadosEnLaRed = $usuario->getIdAfiliadosRed();
        
        $afiliadosActivos = array();
        
        foreach ($afiliadosEnLaRed as $afiliado) {
            
            $Activado = $this->isActivedAfiliado($afiliado);
            
            if ($Activado == $status) {
                $q = $this->db->query('SELECT
										 	a.id,
										 	a.username usuario,
										 	b.nombre nombre,
										 	b.apellido apellido,
										 	a.email
										FROM
											users a,
											user_profiles b
										WHERE
											a.id=b.user_id
											and b.id_tipo_usuario=2
											and a.id=' . $afiliado);
                
                $afiliado = $q->result();
                array_push($afiliadosActivos, $afiliado);
            }
        }
        
        return $afiliadosActivos;
    }

    /**
     * <? TEST ?>
     * last time : 2017-08-05
     * recent author : qcmarcel
     */
    private function test()
    { // <($parametro){
    }
}
