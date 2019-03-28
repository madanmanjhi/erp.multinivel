<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class general extends CI_Model
{

	function __construct() {
		parent::__construct();
	
		$this->load->model('ov/model_perfil_red');
	}
	
	
	function isAValidUser($id,$modulo){
	
		$q=$this->db->query('SELECT cu.id_tipo_usuario as tipoId
							FROM users u , user_profiles up ,cat_tipo_usuario cu
							where(u.id=up.user_id)
							and (up.id_tipo_usuario=cu.id_tipo_usuario)
							and(u.id='.$id.')');
		$tipo=$q->result();
	
		$idTipoUsuario=$tipo[0]->tipoId;

		$perfiles = array(
			
				"OV" => $this->IsActivedPago($id),
				"comercial" => ($idTipoUsuario==4) ? true : false,
				"soporte" => ($idTipoUsuario==3) ? true : false,
				"logistica" => ($idTipoUsuario==5) ? true : false,
				"oficina" => ($idTipoUsuario==6) ? true : false,
				"administracion" => ($idTipoUsuario==7) ? true : false,
				"cedi" => ($idTipoUsuario==8) ? true : false,
				"almacen" => ($idTipoUsuario==9) ? true : false,
				
		);
		
		return ($idTipoUsuario==1) ? true :$perfiles[$modulo];
		
	}
	function get_status($id)
	{
		$q=$this->db->query('select id_estatus from user_profiles where user_id = '.$id);
		return $q->result();
	}

	function IsActivedPago($id){
		$q = $this->db->query('select estatus from user_profiles where user_id = '.$id);
		$estado = $q->result();
		
		if($estado[0]->estatus == 'ACT'){
			return true;
		}else{
			return false;
		}	
	}
	function get_tipo($id)
	{
		$q=$this->db->query('select id_tipo_usuario from user_profiles where user_id = '.$id);
		return $q->result();
	}
  	function get_password($id)
	{
		$q=$this->db->query('select password from users where id = '.$id);
		return $q->result();
	}
	function get_style($id)
	{
	  	$q=$this->db->query('select * from estilo_usuario where id_usuario = '.$id);
	 	return $q->result();
	}
	function get_username($id)
	{
		$q=$this->db->query('select * from user_profiles where user_id = '.$id);
		return $q->result();
	}
	function get_user($id)
	{
		$q=$this->db->query('select username from users where id = '.$id);
		return $q->result();
	}
	function get_last_id()
	{
		$q=$this->db->query("SELECT id from users order by id desc limit 1");
		return $q->result();
	}
	function dato_usuario($email)
	{
		$q=$this->db->query("
			SELECT profile.user_id, profile.nombre nombre, profile.apellido apellido,
			profile.fecha_nacimiento nacimiento, profile.id_estudio id_estudio,
			profile.id_ocupacion id_ocupacion,
			profile.id_tiempo_dedicado id_tiempo_dedicado,
			sexo.descripcion sexo,
			edo_civil.descripcion edo_civil,
			estilos.bg_color, estilos.btn_1_color, estilos.btn_2_color
			from user_profiles profile
			left join cat_sexo sexo
			on profile.id_sexo=sexo.id_sexo
			left join estilo_usuario estilos on
			profile.user_id=estilos.id_usuario
			left join cat_edo_civil edo_civil on
			profile.id_edo_civil=edo_civil.id_edo_civil
			left join users on profile.user_id=users.id
			where users.email=$email$");
		return $q->result();
	}
	function update_login($id)
	{
		if(isset($id)){
		$q=$this->db->query('select last_login from users where id = '.$id);
		$q=$q->result();

            $last_login = $q[0]->last_login;
            $this->db->query("update user_profiles set ultima_sesion=' $last_login ' where user_id=$id");
		}
	}

	function getRetenciones(){
		$q=$this->db->query('SELECT * FROM cat_retencion where estatus="ACT" and duracion !="UNI"');
		return $q=$q->result();
	}
	
	function getRetencionesMes(){
		$q=$this->db->query('SELECT * FROM cat_retenciones_historial where month(now())=mes and year(now())=ano and id_afiliado=0');
		return $q=$q->result();
	}
	
	function isBlocked(){
 
		if($this->isBlockedExpired())
			return false;

        $ip_address = $this->input->ip_address();
        $q=$this->db->query("SELECT blocked FROM users_attempts where ip = '$ip_address'");
		$blocked=$q->result();
		
		if(!isset($blocked[0]->blocked))
			return false;

		if($blocked[0]->blocked==1)
			return true;
		return false;
	}
	
	function isBlockedExpired(){
        $ip_address = $this->input->ip_address();
        $fecha = date('Y-m-d H:i:s');
        $q=$this->db->query("SELECT ip FROM users_attempts where ip = '$ip_address' and (attempts>=5) and ('$fecha') > (last_login + INTERVAL 30 MINUTE)");
		
		$intentos=$q->result();
		
		if(!isset($intentos[0]->ip))
			return false;
		
		if($intentos[0]->ip){
			$this->unlocked();
		}
		return false;
	}
	
	function addAttempts(){
        $ip_address = $this->input->ip_address();
        $q=$this->db->query("SELECT attempts , ip FROM users_attempts where ip = '$ip_address'");
		$intentos=$q->result();

        $fecha = date('Y-m-d H:i:s');
        if(!isset($intentos[0]->ip)){
			$datos = array(
					'ip' => $ip_address,
					'last_login'   => $fecha,
					'attempts'    => '1',
			);
			$this->db->insert('users_attempts',$datos);
			return "5";
		}else if($intentos[0]->attempts>=5){
			$this->locked(); 
			return "ninguno";
		}
		else {
            $intentos = ($intentos[0]->attempts) + 1;
            $this->db->query("update users_attempts set attempts ='$intentos' , last_login ='$fecha' where ip = '$ip_address'");
			return "".(6-($intentos));
		}
	}
	
	function locked(){
        $ip_address = $this->input->ip_address();
        $this->db->query("update users_attempts set blocked ='1' where ip = '$ip_address'");
	}
	
	function unlocked(){
        $ip_address = $this->input->ip_address();
        $this->db->query("update users_attempts set blocked ='0',attempts ='1' where ip = '$ip_address'");
		return true;
	}
	
	function get_temp_invitacion($token)
	{
		$q=$this->db->query("select * from temp_invitacion where token = '$token'");
		$token = $q->result();
		return $token;
	}
	
	function get_temp_invitacion_ACT($token)
	{
		$q=$this->db->query("select * from temp_invitacion where token = '$token' and estatus = 'ACT'");
		$token = $q->result();
		return $token;
	}
	
	function get_temp_invitacion_ACT_id($token)
	{
		$q=$this->db->query("select * from temp_invitacion where id = '$token' and estatus = 'ACT'");
		$token = $q->result();
		return $token;
	}
	
	function new_invitacion($email,$red,$sponsor,$debajo_de,$lado){
		
		//$time = time();
		$token = md5(/*$time."~".*/$red."~".$email."~".$sponsor."~".$debajo_de."~".$lado);	
		
			$dato=array(
					"token" =>	$token,
					"email" =>	$email,
					"red" =>	$red,
					"sponsor" =>	$sponsor,
					"padre" =>	$debajo_de,
					"lado" =>	$lado,
			);
			$this->db->insert("temp_invitacion",$dato);
		
		return ($this->get_temp_invitacion($token)) ?  $token : false;
		
	}
	
	function checkespacio ($temp){
		
		$exist = $this->model_perfil_red->exist_mail($_POST['email']);
		
		if ($exist){
			return  true;
		}
		$ocupado = $this->model_perfil_red->ocupado($temp);
		($ocupado) ? $this->model_perfil_red->trash_token($temp[0]->id) : ''; 
		return ($ocupado) ? true : false;
	}
	
        function issetVar($var, $type = false, $novar = false) {

            $result = isset($var) ? $var : $novar;

            if ($type)
                $result = isset($var[0]->$type) ? $var[0]->$type : $novar;

            if (!isset($var[0]->$type))
                log_message('DEV', "issetVar T:($type) :: " . json_encode($var));

            return $result;
        }
	
        function hex2rgb($hex,$str = false) {
            $hex = str_replace("#", "", $hex);
            $isShort = (strlen($hex) == 3);
            
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            
            if(!$isShort){
               $r = hexdec(substr($hex,0,2));
               $g = hexdec(substr($hex,2,2));
               $b = hexdec(substr($hex,4,2));
            }
            
            $rgb = array($r, $g, $b);
            
            if($str)
                $rgb = implode(",", $rgb);
            //return implode(",", $rgb); // returns the rgb values separated by commas
            return $rgb; // returns an array with the rgb values
         }
         
         function rgb2hex($rgb) {
            
            $trash = "rgb|rgba|(|)|;"; 
            $trash = explode("|", $trash);
            $fix = (gettype($rgb) == "string");
            if($fix){
                foreach ($trash as $t)
                    $rgb= str_replace ($t, "", $rgb);
                    
                $rgb = explode(",", $rgb);                
            } 
             
            $hex = "#";
            $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
            $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
            $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

            return $hex; // returns the hex value including the number sign (#)
         }

        function getAnyTime($date, $time = '1 month', $add = true)
        {
            $fecha_sub = new DateTime($date);
            if ($add)
                date_add($fecha_sub, date_interval_create_from_date_string("$time"));
            else
                date_sub($fecha_sub, date_interval_create_from_date_string("$time"));

            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

        function getNextTime($date, $time = 'month')
        {
            $fecha_sub = new DateTime($date);
            date_add($fecha_sub, date_interval_create_from_date_string("1 $time"));
            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

        function getLastTime($date, $time = 'month')
        {
            $fecha_sub = new DateTime($date);
            date_sub($fecha_sub, date_interval_create_from_date_string("1 $time"));
            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

    function setFechaformato($fecha=false,$formato=0)
    {
        $f = array('Y-m-d H:i:s','Y-m-d');

        if(!$fecha)
            $fecha = date($f[0]);

        $fecha = strtotime($fecha);

        if(isset($f[$formato]))
            return date($f[$formato],$fecha);

        try {
            return date($formato,$fecha);
        } catch (Exception $e) {
            log_message('DEV',"fail conversion date :: $formato");
            return date($f[1],$fecha);
        }
    }

}