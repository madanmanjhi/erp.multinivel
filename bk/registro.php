<?php
date_default_timezone_set('America/Mexico_City');
require_once(setDir().'/application/libraries/phpass-0.1/PasswordHash.php');

class registro {
    
    public $db = array();
    public $datos = array();
    public $userData = array();
    public $afiliados = array();
    
    function __construct($db,$datos){
        $this->db = $db;
        $this->datos = $datos;
    }
    
    private function createUser ()
    {               
            $username = isset($this->datos["username"]) ? $this->datos["username"] : $this->setUsername();
            $password = isset($this->datos["password"]) ? $this->datos["password"] : $this->datos["clave"];
    		
            // Hash password using phpass
            $hasher = new PasswordHash(8,false);
            $hashed_password = $hasher->HashPassword($password);
            
            $email_activacion = strtolower($this->datos["email"]);
            
            $data = array(
                'username'	=> $username,
                'password'	=> $hashed_password,
                'recovery'	=> $password,
                'email'		=> $email_activacion,
                'last_ip'	=> $_SERVER['REMOTE_ADDR'],
            ); 
            
            $this->insertUser($data);
            
            $this->insertPassword ( $hashed_password, $email_activacion );
                        
            return $this->obtenrIdUser($email_activacion);
    }
	
	private function insertPassword($hashed_password, $email_activacion) {
		$query = "update users set password = '$hashed_password' where email = '$email_activacion'";            
		
		return newStatement ( $this->db,$query);

	}		
    
    function insertUser($data, $activated = TRUE)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;
        
        $fields = array("username","password","email","activated","last_ip","created","recovery");
        
        $values = array();
        foreach ($fields as $f){
            $valor="'".$data[$f]."'";
            array_push($values, $valor);
        }            
        
        $query = "insert into users
                                        (".implode(",", $fields).")
                                            values (".implode(",", $values).")";
                
       
        newQuery($this->db, $query);   
        
        unset($data['password']);
        unset($data['last_ip']);
        
        $this->userData = $data;
        
        return true;
    }
    
    function setUsername()
    {
        $lastid = $this->obtenerLastID();
        $lastid = (intval($lastid)+1);
        
        $email = $this->datos["email"];
        $email = explode("@", $email);        
        $username = strtolower($email[0]);
        
        $isUsed = $this->obtenrIdUsername($username);
        
        if($isUsed)
            $username.=$lastid;
        
        return $username;
    }
    
    
    private function EstiloUsuario($id){
        
        $estilo = $this->getEstiloUsuario();
        
        $fields = array(
            "id_usuario",
            "bg_color",
            "btn_1_color",
            "btn_2_color"
        );
        
        $dato_style=array(
            $id,
            $estilo[1]["bg_color"],
            $estilo[1]["btn_1_color"],
            $estilo[1]["btn_2_color"]
        );
        
        return $this->insertDatos("estilo_usuario",$fields, $dato_style);
        
        
    }
    
    function getEstiloUsuario() {
        
        $q = newQuery($this->db,"SELECT * FROM estilo_usuario where id_usuario = 1");
        return $q;
    }
    
    
    private function Perfil($id){ 
            
            $fiscal =  $this->datos['keyword'];
            if(!$fiscal) $fiscal = rand(100000,999999);
            
            $sexo = explode(" ", $this->datos['nombre']);
            $sexo = strtolower(substr(strrev($sexo[0]), 0,1)) == "a" ? 2 : 1;
            
            $nacimiento = $this->datos['nacimiento'];
            
            if(!$nacimiento){
            $fecha_sub = new DateTime();
            $edad = $this->datos['edad'].' years';
            date_sub($fecha_sub, date_interval_create_from_date_string($edad));
            $nacimiento = date_format($fecha_sub, 'Y-m-d');
            }
            
            $fields = array(
                "user_id",
                "id_sexo",
                "id_edo_civil",
                "id_tipo_usuario",
                "id_estudio",
                "id_ocupacion",
                "id_tiempo_dedicado",
                'id_estatus',
                "id_fiscal",
                "keyword",
                "paquete",
                "nombre",
                "apellido",
                "fecha_nacimiento" 
            );
            
            $dato_profile = array(
                $id,
                $sexo,
                1,
                2,
                2,
                1,
                1,
                1,
                1,
                $fiscal,
                0,
                $this->datos['nombre'],
                $this->datos['apellido'],
                $nacimiento
            );
            
            return $this->insertDatos("user_profiles",$fields, $dato_profile);
    }
    
    
    private function Permiso($id){
         
         $fields = array(
                "id_user",
                "id_perfil"
            );

        $dato_permiso=array(
            $id,
            2
        );
         
        return $this->insertDatos("cross_perfil_usuario",$fields, $dato_permiso);
    }
    
    private function Coaplicante($id){
        
        $fields = array(
            "id_user",
            "nombre",
            "apellido",
            "keyword"
        );
        
            $dato_coaplicante=array(
                $id,
                $this->datos['nombre_co'],
                $this->datos['apellido_co'],
                $this->datos['keyword_co']
            );
            
            return $this->insertDatos("coaplicante",$fields, $dato_coaplicante);
            
        
    }
    
    function afiliar_procedure(
        $id,
        $dato_perfil,
        $dato_afiliar,
        $dato_estilo,
        $dato_coaplicante,
        #$dato_red,
        #$dato_tels,
        $dato_dir,
        $dato_billetera,
        $dato_rango,
        $dato_img
        ){
            
            
            $dato_perfil = $this->setArrayVarchar($dato_perfil);
            $dato_afiliar = $this->setArrayVarchar($dato_afiliar);
            $dato_estilo = $this->setArrayVarchar($dato_estilo);
            $dato_coaplicante = $this->setArrayVarchar($dato_coaplicante);
            #$dato_tels = $this->setArrayVarchar($dato_tels);
            $dato_dir = $this->setArrayVarchar($dato_dir);
            $dato_billetera = $this->setArrayVarchar($dato_billetera);
            $dato_rango = $this->setArrayVarchar($dato_rango);
            $dato_img = $this->setArrayVarchar($dato_img);
            
            
            $query = newQuery($this->db,'CALL afiliar('.$id.',"'.$dato_perfil.'","'.$dato_afiliar.'","'.
                $dato_estilo.'","'.$dato_coaplicante./*'","'.$dato_tels.*/'","'.
                $dato_dir.'","'.$dato_billetera.'","'.$dato_rango.'","'.$dato_img.'")');
            
            
            $res = $query; 
            
            //$query->next_result(); // Dump the extra resultset.
            //$query->free_result(); //Does what it says.
            
            return  true;# '1|'.$dato_perfil.'2|'.$dato_afiliar.'3|'.
            # $dato_estilo.'4|'.$dato_coaplicante.'5|'./*$dato_tels.'6|'.*/
            # $dato_dir.'7|'.$dato_billetera.'8|'.$dato_rango.'9|'.$dato_img;#$q->result();
    }
    
    function crearUsuario(){
        
        $id = $this->createUser(); 
        
        if (!$id){
            return false;
        }
        
        $mi_red=1; 
        $directo = isset($this->datos['sponsor']) ? $this->datos['sponsor'] : 2;
        $id_debajo = $directo;
        
        if(isset($this->datos["profundidad"]))            
            $this->definir_debajo ($directo) ;
        
        $lado = $this->definir_lado ($id_debajo,$mi_red);        
        
        $fijos = isset($this->datos["fijo"]) ? $this->datos["fijo"] : false;
        $moviles = isset($this->datos["movil"]) ? $this->datos["movil"] : false;
        
       /* echo "red : ".$mi_red
         ." 	afiliado: ".$id
         ."	padre: ".$id_debajo
         ."	sponsor: ".$directo
         ."	lado: ".$lado;
          */
         #return true;
        
        $existe_perfil = $this->perfil_existe($id);
        if($existe_perfil){
            return true;
        }
        
        if($fijos&&$moviles) 
            $this->insert_dato_tels($id,$fijos,$moviles);
        
        $dato_perfil=$this->Perfil($id); # USER_PROFILES
        $dato_afiliar=$this->dato_afiliar ( $id, $mi_red, $id_debajo, $lado, $directo ); # AFILIAR
        $dato_permiso=$this->Permiso($id); # USER_PERMISSIONS
        $dato_estilo=$this->EstiloUsuario($id);	# ESTILO_USUARIO
        $dato_coaplicante=$this->Coaplicante($id);# COAPLICANTE
        #$dato_red=$this->dato_red ( $id );# RED	#!DEPRECATED
        $dato_dir=$this->dato_dir ( $id );# DIRECCION
        $dato_billetera=$this->dato_billetera ( $id ); # BILLETERA
        $dato_rango=$this->dato_rango ( $id ); # RANGO
        $dato_img=$this->dato_img ( $id ); # IMAGEN
        
        #foreach (array($dato_perfil,$dato_afiliar,$dato_estilo,$dato_dir,$dato_coaplicante,$dato_billetera,$dato_rango,$dato_img) as $rest){
            #echo "<br/><br/>";var_dump($rest);
        #}               
                       
        # TELEFONOS $dato_tels dato_tels($id)
        
        return $id ? $id /*var_dump()."|".var_dump($_POST["movil"])*/ : null;#; #;
    }
    
    private function dato_img($id) {
        
        $fields = array(
            "url",
            "nombre_completo",
            "nombre",
            "extencion",
            "estatus"
        );
        
        $dato_img=array(
            "/template/img/avatars/male.png",
            "male.png",
            "male",
            "png",
            "ACT"
            
        );
        
        $this->insertDatos("cat_img",$fields, $dato_img);
        
        $query = "SELECT last(id_img) id from cat_img";              
        $lastid = newQuery($this->db, $query);
        $id_img = $lastid[1]["id"];
        
        $fields=array(
            "id_user",
            "id_img"
        );
        
        $dato_cross=array(
            $id,
            $id_img
        );
        
        return $this->insertDatos("cross_img_user",$fields, $dato_cross);
    }
    
    
    private function dato_rango($id) {
        
        $fields = array(
            "id_user",
            "id_rango",
            "entregado",
            "estatus"
        );
        
        $dato_rango=array(
           $id,
           0,
           1,
           "ACT"
        );
        
        return $this->insertDatos("cross_rango_user",$fields, $dato_rango);
    }
    
    
    private function dato_billetera($id) {
        
        $fields = array(
            "id_user",
            "estatus",
            "activo",
            "unico"	
        );
        
        $descontar = isset($this->datos['descontar']) ? 'Si' : 'No';
        $noescalar = isset($this->datos['escalar']) ? 'Si' : 'No';
        
        $dato_billetera=array(
            $id,
            "DES",
            $descontar,
            $noescalar
        );
        
        return $this->insertDatos("billetera",$fields, $dato_billetera);

    }
    
    private function insertDatos($tablename,$fields, $datos)
    {
        $values = array();
        foreach ($datos as $f){
            $valor="'".$f."'";
            array_push($values, $valor);
        }
        
        $query = "insert into $tablename
                                        (".implode(",", $fields).")
                                            values (".implode(",", $values).")";
        
        
        newQuery($this->db, $query);
        
        return true;
    }        
    
    private function dato_dir($id) {
        
        $fields = array(
            "id_user",
            "cp",
            "calle",
            "colonia",
            "municipio",
            "estado",
            "pais"
        );
        
        $dato_dir=array(
            $id,
            $this->datos['cp'],
            $this->datos['calle'],
            $this->datos['colonia'],
            $this->datos['municipio'],
            $this->datos['estado'],
            $this->datos['pais']
        );
        
        return $this->insertDatos("cross_dir_user",$fields, $dato_dir);
    }
    
    
    private function insert_dato_tels($id,$fijos,$moviles) {
         
        foreach ($fijos as $fijo){
            $fijo .= "";
        newQuery($this->db, "insert into cross_tel_user 
                                (id_user,id_tipo_tel,numero,estatus) 
                                    values ($id,1,'$fijo','ACT')");
        }
        foreach ($moviles as $movil){
            $movil .= "";
        newQuery($this->db, "insert into cross_tel_user
                   (id_user,id_tipo_tel,numero,estatus)
                     values ($id,2,'$movil','ACT')");
        }
    
        return true;
    }
    
    
    private function dato_afiliar($id, $mi_red, $id_debajo, $lado, $directo) { 
        
        $fields = array(
            "id_red",
            "id_afiliado",
            "debajo_de",
            "directo",
            "lado"
        );
        
        $dato_afiliar =array(
            $mi_red,
            $id,
            $id_debajo,
            $directo,
            $lado
        );
        
        return $this->insertDatos("afiliar",$fields, $dato_afiliar);
        
    }
    
    private function insert_dato_afiliar($id, $mi_red, $id_debajo, $lado, $directo) { #insert_dato_afiliar
        $dato_afiliar =array(
            "id_red"      => $mi_red,
            "id_afiliado" => $id,
            "debajo_de"   => $id_debajo,
            "directo"     => $directo,
            "lado"        => $lado
        );
        
        //var_dump($dato_afiliar); exit;
        $this->db->insert("afiliar",$dato_afiliar);
        return true;
        #echo "afiliar si|";
    }
    
    private function definir_sponsor($id_debajo) {
        if(isset($_POST['sponsor']))
        {
            $directo=intval($this->tank_auth->get_user_id());
            return ($directo==1) ? 2 : $directo;
        }else{
            return intval(isset($_POST['directo']) ? $_POST['directo'] : $id_debajo);
        }
        echo "sponsor si|";
    }
    
    private function definir_lado($id_debajo,$mi_red) {
        
       return $this->consultarFrontalDisponible($id_debajo, $mi_red);
        
    }
    
    private function dato_red($id) { #insert_dato_red
        
        $redes = $this->db->get('tipo_red');
        $redes = $redes->result();
        $dato_red = array();
        foreach ($redes as $red){
            $dato=array(
                /*'id_red'        => */$red->id,
                /*"id_usuario"	=> */$id,
                /*"profundidad"	=> */"0",
                /*"estatus"		=> */"ACT",
                /*"premium"		=> */'2'
            );
            array_push($dato_red, $dato);
            #$this->db->insert("red",$dato_red);
        }
        return $dato_red;#true;
        #echo "red si|";
    }
    
    
    private function activar_user($id) {
        newQuery($this->db,'update users set activated="1" where id="'.$id.'"');
        echo "activar si|";
    }
    
    private function perfil_existe($id) {
        $q = newQuery($this->db,"select * from user_profiles where user_id=".$id);
        $perfil = $q;
        return ($perfil) ? $perfil[1]["user_id"] : null;
        echo "perfil si|";
    }
    
    private function definir_debajo($id = 2){
        
        $hijos = $this->getRedAfiliado($id);
        
        $debajo = $hijos[1]["id_afiliado"];
        $menor = false;
        
        foreach ($hijos as $hijo){
            $id_hijo = $hijo["id_afiliado"];
            $afiliados = $this->getRedAfiliado($id_hijo);   
            $cantidad = sizeof($afiliados);
            if($cantidad<$menor || $menor === false){
                $debajo = $id_hijo;
                $menor = $cantidad;
            }                
        }
        
        #TODO: Ajustar segun los casos de derrame   
            
        return $debajo;
        
    }
    
    function getDebajoConsecutivo($afiliado, $red = 1)
    {            
        $afiliados = array(
            $afiliado
        );
        $isDisponible = false;
        $debajo = $afiliado;
        
        $i = 1;
        while (! $isDisponible) {
            
            foreach ($afiliados as $uid) {
                
                $isDisponible = $this->isFrontalDisponible($uid, $red);
                
                if ($isDisponible) {
                    $debajo = $uid;
                    break;
                }
            }
            
            $this->getAfiliadosNivel($afiliado, $red, $i);
            $afiliados = $this->getAfiliados();
            $i ++;
        }
        
        return $debajo;
    }

    private function getAfiliadosNivel($id, $red = 1, $nivel)
    {
        $q =newQuery($this->db,"select * from afiliar
										where debajo_de =$id
												 and id_red = $red");
        $linea = $q;
        $afiliados = array();
        
        if ($q) {
            
            $nivel --;
            
            foreach ($linea as $dato) {
                
                if ($nivel == 0)
                    $this->setAfiliados($dato["id_afiliado"]);
                else
                    $this->getAfiliadosNivel($dato["id_afiliado"], $red, $nivel);
            }
        }
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

    function get_cantidad_de_frontales($id_afiliado,$id_red)
    {
        
        $q=newQuery($this->db,"SELECT count(*) as frontales FROM afiliar where debajo_de=".$id_afiliado." and id_red=".$id_red." order by lado");
        return $q;
    }
    
    private function isFrontalDisponible($afiliado, $red = 1)
    {
        $limite = $this->getFrontalidadRed($red);
        
        $q = $this->get_cantidad_de_frontales($afiliado, $red);
        
        if (! $q)
            return true;
        
        $frontales = $q[1]["frontales"];
        $isDisponible = ($limite > $frontales);
        
        return $isDisponible;
    }

    private function getFrontalidadRed($red)
    {
        $q =newQuery($this->db,"select frontal from tipo_red where id = " . $red);
        $q = $q;
        
        if (! $q)
            return false;
        
        return $q[1]["frontal"];
    }	
    
    function obtenerLadoAfiliar($id = 2){
        $id_afiliador= newQuery($this->db,"select rama from afiliar where id_afiliado = $id");
        return $id_afiliador[1]["rama"] == 2 ? 0 : $id_afiliador[1]["rama"] ;
    }
    
    function obtenerLastID(){
        $id_afiliador= newQuery($this->db,'select last(id) id from users ');
        return $id_afiliador[1]["id"];
    }
    
    function obtenrIdUser($email){
        $query = "select id from users where email = '$email'";                
        $id_afiliador= newQuery($this->db,$query);
        
        return $id_afiliador[1]["id"];
    }
    
    function obtenrIdUsername($username){
        $id_afiliador= newQuery($this->db,'select id from users where username like "'.$username.'"');
        
      
        return $id_afiliador[1]["id"];
    }
    
    function obtenrIdUserby($usuario){
        $id_afiliador= newQuery($this->db,'select id from users where username ="'.$usuario.'"');
        
        return $id_afiliador[1]["id"];
    }
    
    function consultarFrontalDisponible($id_debajo, $red){
        
        $lados = $this->getRedAfiliado($id_debajo, $red);
        $lado_disponible=0;
        
        if(isset($lados[1]["id"])){
            $aux=0;
            foreach ($lados as $filaLado){
                if($filaLado["lado"]!=$aux){
                    $lado_disponible = $aux;
                    return $lado_disponible;
                }
                $aux++;
                $lado_disponible++;
            }
        }
        return $lado_disponible;
    }
    
    private function getRedAfiliado($id_debajo = 2, $red = 1)
    {
        $query = "select * from afiliar where debajo_de = $id_debajo and id_red = $red order by lado";       
        $query = newQuery($this->db,$query);
        
        $lados = $query;
        return $lados;
    }

    function setArrayVarchar($array){
        $ArrayVarchar = array();
        foreach ($array as $key){
            if(!preg_match('/^[0-9]{1,}$/', $key)){
                $key = '\''.$key.'\'';
            }
            array_push($ArrayVarchar, $key);
        }
        return implode(',',$ArrayVarchar);
    }    
    
    function ObtenerRetencioFase(){
        $q = newQuery($this->db,"select porcentaje from cat_retencion where duracion= 'UNI'");
        $retencion = $q;
        return $retencion[1]["porcentaje"];
    }
    
    function CambiarFase($id, $red, $fase){
        if($id == 0 || $id == null){
            return false;
        }
        if($fase == '2'){
            $mes = date('m');
            $año = date('Y');
            $valor = $this->ObtenerRetencioFase();
            $datos = array(
                'descripcion' => 'Cambio Fase a B',
                'valor'       => $valor,
                'mes'		  =>$mes,
                'ano'		  => $año,
                'id_afiliado' => $id
            );
            $this->db->insert('cat_retenciones_historial', $datos);
            
        }
        
        $query = newQuery($this->db,'select * from red where id_usuario = '.$id.' and id_red = '.$red.' ');
        
        $red = $query;
        
        if($red[1]["premium"] == 0){
            newQuery($this->db,"update red set premium = '".$fase."' where id_red =".$red[1]["id_red"]." and id_usuario=".$id);
            return true;
        }
        
        
    }
    
    function crearUsuarioAdmin($id_debajo){
        
        $id = $this->obtenrIdUser($_POST['mail_important']);
        
        newQuery($this->db,'update users set activated="1" where id="'.$id.'"');
        $this->EstiloUsuaio($id);
        $directo=1;
        $q = newQuery($this->db,"select * from user_profiles where user_id=".$id);
        $perfil = $q;
        if(isset($perfil[1]["user_id"])){
            return true;
        }else
            $this->CrearPerfil($id);
            
            $this->CrearCoaplicante($id);
            
            $mi_red=$_POST['red'];
            
            /*################### DATO RED #########################*/
            
            $redes = $this->db->get('tipo_red');
            $redes = $redes->result();
            foreach ($redes as $red){
                $dato_red=array(
                    'id_red'        => $red->id,
                    "id_usuario"	=> $id,
                    "profundidad"	=> "0",
                    "estatus"		=> "ACT",
                    "premium"			=> '2'
                );
                $this->db->insert("red",$dato_red);
            }
            
            /*################### FIN DATO RED #########################*/
            
            /*################### DATO AFILIAR #########################*/
            
            $directo = 1;
            if(isset($_POST['sponsor']))
            {
                $directo = 0;
            }
            
            $lado = $this->consultarFrontalDisponible($id_debajo, $mi_red);
            
            $dato_afiliar=array(
                "id_red"      => $mi_red,
                "id_afiliado" => $id,
                "debajo_de"   => $id_debajo,
                "directo"     => $directo,
                "lado"        => $lado
            );
            
            
            $this->db->insert("afiliar",$dato_afiliar);
            
            
            /*################### DATO TELEFONOS #########################*/
            //tipo_tel 1=fijo 2=movil
            if($_POST["fijo"])
            {
                foreach ($_POST["fijo"] as $fijo)
                {
                    $dato_tel=array(
                        "id_user"		=> $id,
                        "id_tipo_tel"	=> 1,
                        "numero"		=> $fijo,
                        "estatus"		=> "ACT"
                    );
                    
                    $this->db->insert("cross_tel_user",$dato_tel);
                }
                
            }
            if($_POST["movil"])
            {
                foreach ($_POST["movil"] as $movil)
                {
                    $dato_tel=array(
                        "id_user"		=> $id,
                        "id_tipo_tel"	=> 2,
                        "numero"		=> $movil,
                        "estatus"		=> "ACT"
                    );
                    $this->db->insert("cross_tel_user",$dato_tel);
                }
            }
            
            /*################### FIN DATO TELEFONOS #########################*/
            /*################### DATO DIRECCION #########################*/
            $dato_dir=array(
                "id_user"   => $id,
                "cp"        => $_POST['cp'],
                "calle"     => $_POST['calle'],
                "colonia"   => $_POST['colonia'],
                "municipio" => $_POST['municipio'],
                "estado"    => $_POST['estado'],
                "pais"      =>$_POST['pais']
            );
            $this->db->insert("cross_dir_user",$dato_dir);
            /*################### FIN DATO DIRECCION #########################*/
            
            /*################### DATO BILLETERA #########################*/
            $dato_billetera=array(
                "id_user"	=> $id,
                "estatus"		=> "DES",
                "activo"		=> "No"
            );
            $this->db->insert("billetera",$dato_billetera);
            /*################### FIN DATO BILLETERA #########################*/
            
            /*################### FIN DATO COBRO #########################*/
            $plan = 1;
            if(!isset($_POST['tipo_plan'])){
                $plan = $_POST['tipo_plan'];
            }
            $query = newQuery($this->db,"select * from paquete_inscripcion where id_paquete=".$plan);
            $plan = $query;
            
            
            
            /*################### DATO RANGO #########################*/
            $dato_rango=array(
                "id_user"	=> $id,
                "id_rango"		=> 1,
                "entregado"		=> 1,
                "estatus"		=> "ACT"
            );
            $this->db->insert("cross_rango_user",$dato_rango);
            /*################### FIN DATO RANGO #########################*/
            $dato_rango=array(
                "url"		=> "/template/img/empresario.jpg",
                "nombre_completo"		=> "empresario.jpg",
                "nombre"		=> "empresario",
                "extencion"		=> "jpg",
                "estatus"		=> "ACT"
            );
            $this->db->insert("cat_img",$dato_rango);
            $id_img = $this->db->insert_id();
            $dato_rango=array(
                "id_user"	=> $id,
                "id_img"	=> $id_img
            );
            $this->db->insert("cross_img_user",$dato_rango);
            return true;
    }
    
    function crearUsuarioProveedor($id_debajo){
        
        $id = $this->obtenrIdUser($_POST['mail_important']);
        
        newQuery($this->db,'update users set activated="1" where id="'.$id.'"');
        $this->EstiloUsuaio($id);
        $directo=1;
        
        $this->CrearPerfil($id);
        
        $this->CrearCoaplicante($id);
        
        $mi_red=$_POST['red'];
        
        /*################### DATO RED #########################*/
        
        $redes = $this->db->get('tipo_red');
        $redes = $redes->result();
        foreach ($redes as $red){
            $dato_red=array(
                'id_red'        => $red->id,
                "id_usuario"	=> $id,
                "profundidad"	=> "0",
                "estatus"		=> "ACT",
                "premium"			=> '2'
            );
            $this->db->insert("red",$dato_red);
        }
        
        /*################### FIN DATO RED #########################*/
        
        /*################### DATO AFILIAR #########################*/
        
        $directo = 1;
        if(isset($_POST['sponsor']))
        {
            $directo = 0;
        }
        
        $lado = $this->consultarFrontalDisponible($id_debajo, $mi_red);
        
        $dato_afiliar=array(
            "id_red"      => $mi_red,
            "id_afiliado" => $id,
            "debajo_de"   => $id_debajo,
            "directo"     => $directo,
            "lado"        => $lado
        );
        
        
        $this->db->insert("afiliar",$dato_afiliar);
        
        
        /*################### DATO TELEFONOS #########################*/
        //tipo_tel 1=fijo 2=movil
        if($_POST["fijo"])
        {
            foreach ($_POST["fijo"] as $fijo)
            {
                $dato_tel=array(
                    "id_user"		=> $id,
                    "id_tipo_tel"	=> 1,
                    "numero"		=> $fijo,
                    "estatus"		=> "ACT"
                );
                
                $this->db->insert("cross_tel_user",$dato_tel);
            }
            
        }
        if($_POST["movil"])
        {
            foreach ($_POST["movil"] as $movil)
            {
                $dato_tel=array(
                    "id_user"		=> $id,
                    "id_tipo_tel"	=> 2,
                    "numero"		=> $movil,
                    "estatus"		=> "ACT"
                );
                $this->db->insert("cross_tel_user",$dato_tel);
            }
        }
        
        /*################### FIN DATO TELEFONOS #########################*/
        /*################### DATO DIRECCION #########################*/
        $dato_dir=array(
            "id_user"   => $id,
            "cp"        => $_POST['cp'],
            "calle"     => $_POST['calle'],
            "colonia"   => $_POST['colonia'],
            "municipio" => $_POST['municipio'],
            "estado"    => $_POST['estado'],
            "pais"      =>$_POST['pais']
        );
        $this->db->insert("cross_dir_user",$dato_dir);
        /*################### FIN DATO DIRECCION #########################*/
        
        /*################### DATO BILLETERA #########################*/
        $dato_billetera=array(
            "id_user"	=> $id,
            "estatus"		=> "DES",
            "activo"		=> "No"
        );
        $this->db->insert("billetera",$dato_billetera);
        /*################### FIN DATO BILLETERA #########################*/
        
        
        /*################### DATO RANGO #########################*/
        $dato_rango=array(
            "id_user"	=> $id,
            "id_rango"		=> 1,
            "entregado"		=> 1,
            "estatus"		=> "ACT"
        );
        $this->db->insert("cross_rango_user",$dato_rango);
        /*################### FIN DATO RANGO #########################*/
        
        return true;
    }
    
    function RedAfiliado($id, $red){
        $query = newQuery($this->db,'select * from afiliar where id_red = "'.$red.'" and id_afiliado = "'.$id.'" ');
        return $query;
    }
    
    function ComprasUsuario($id){
        $q = newQuery($this->db,"SELECT sum(cvm.costo_unidad*cvm.cantidad) as compras
								FROM cross_venta_mercancia cvm , venta v
								where v.id_user=".$id."
								and cvm.id_venta=v.id_venta
								and v.id_estatus='ACT'");
        $costos = $q;
        return $costos[1]["compras"];
    }
    
    function PuntosUsuario($id){
        $q = newQuery($this->db,"SELECT sum(c.puntos) as puntos FROM comision c, venta v where c.id_venta = v.id_venta and v.id_user = ".$id.";");
        $puntos = $q;
        return $puntos[1]["puntos"];
    }
    
    function ComisionUsuario($id){
        $q = newQuery($this->db,"SELECT sum(valor) as comision FROM comision where id_afiliado = ".$id.";");
        $comision = $q;
        return $comision[1]["comision"];
    }
    
    function AgregarAfiliadoRed($id_debajo, $red, $usuario){
        $mi_red= $red;
        $id = $this->obtenrIdUserby($usuario);
        
        if(!$id){
            echo "No se pudo hacer la afiliacion.";
            return false;
        }
        
        $lado = 1;
        if(!isset($_POST['lado']))
            $lado = $this->consultarFrontalDisponible($id_debajo, $mi_red);
            else{
                $lado = $_POST['lado'];
            }
            
            $dato_afiliar =array(
                "id_red"      => $mi_red,
                "id_afiliado" => $id,
                "debajo_de"   => $id_debajo,
                "directo"     => $this->tank_auth->get_user_id(),
                "lado"        => $lado
            );
            $this->db->insert("afiliar",$dato_afiliar);
            
            $q = newQuery($this->db,"select estatus from red where id_red = ".$mi_red." and id_usuario = ".$id);
            $red = $q;
            
            if(isset($red[1]["estatus"])){
                newQuery($this->db,"update red set estatus = 'ACT' where id_red = ".$mi_red." and id_usuario = ".$id);
            }else{
                $dato_red=array(
                    'id_red'        => $mi_red,
                    "id_usuario"	=> $id,
                    "profundidad"	=> "0",
                    "estatus"		=> "ACT",
                    "premium"			=> '2'
                );
                $this->db->insert("red",$dato_red);
            }
            return true;
    }
    
    function ConprobarUsuario($username,$email,$red, $id){
        $q = newQuery($this->db,"select id_afiliado from afiliar where id_afiliado = ".$id." and id_red = ".$red);
        $padre = $q;
        
        if(isset($padre[1]["id_afiliado"])){
            $q = newQuery($this->db,"select id from users where username = '".$username."' and email = '".$email."'");
            $afiliado = $q;
            
            if(isset($afiliado[1]["id"])){
                $q = newQuery($this->db,"select id_red from afiliar where id_afiliado = ".$afiliado[1]["id"]." and id_red = ".$red);
                $afiliado1 = $q;
                if(!isset($afiliado1[1]["id_red"])){
                    return true;
                }else{
                    echo "<div id='msg_usuario' class='alert alert-danger fade in'>
							 UPS¡ lo sentimos, los datos ingresados pertenecen a un afiliado que ya pertenece a esta red
						</div>";
                    return false;
                }
            }else{
                echo "<div id='msg_usuario' class='alert alert-danger fade in'>
						!UPS¡ lo sentimos, los datos ingresados no pertenecen al afiliado, comprueba que el email y username esten correctos
					</div>";
                return false;
            }
        }else{
            echo "<div id='msg_usuario' class='alert alert-danger fade in'>
					!UPS¡ lo sentimos, no podemos afiliar al usuario a esta red
				</div>";
            return false;
        }
    }
}
