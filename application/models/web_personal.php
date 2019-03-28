<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class web_personal extends CI_Model{
    
    private $web_dir;
    private $sys_dir;
    private $content;
    
    function __construct() {
        parent::__construct();
        
        $this->web_dir = $this->setDir();
        
    }
    
    function setDir(){
        $webfolder = str_replace("erp.clientes","sites.clientes", getcwd());
        
        $this->sys_dir = (substr($webfolder, 1,1) == ":") ? "\\" : "/";
        
        $ruta =  explode($this->sys_dir , $webfolder);
                
        $empresa_web = $this->traer_acceso_web_personal();
        
        if($empresa_web)
            $ruta[sizeof($ruta)-1] = $empresa_web;
            
        $ruta = implode($this->sys_dir,$ruta);
        if($this->sys_dir =="/")
                $ruta = "/".$ruta;                  
        
        return $ruta ;
    }
    
    function traer_afiliado($id){
        $tipos = $this->db->get_where('users', array('id' => $id));
        
        return $tipos->result();
    }
    
    function traer_perfil($id){
        $tipos = $this->db->get_where('user_profiles', array('user_id' => $id));
        
        return $tipos->result();
    }
    
    function traer_afiliado_username($id){
        $tipos = $this->db->get_where('users', array('id' => $id));
        $q = $tipos->result();
        return $q ? $q[0]->username : false;
    }
    
    function traer_acceso_web_personal(){
        $q = $this->empresa();
        return $q && isset($q[0]->webfolder) ? $q[0]->webfolder : false;
    }
    
    private function empresa()
    {
        $tipos = $this->db->get('empresa_multinivel');        
        $q = $tipos->result();
        return $q;
    }

    
    function listar_por_afiliado($username){
        $dato_tipo = array('username' => $username);
        $tipos = $this->db->get_where('user_webs_personales', $dato_tipo);
        return $tipos->result();
    }
    
    function actualizar($username, $clave){
        $datos = array(
            'clave' => $clave);
        $this->db->where("username",$username);
        $this->db->update("user_webs_personales",$datos);
    }
    
    function insertar($username, $clave){
        $datos = array(
            'username' => $username,
            'clave' => $clave
        );
        $this->db->insert("user_webs_personales",$datos);
    }
    
    function val_web_personal($id){

        if($this->sys_dir=="\\")
            return true;
        
        if($id == 2)
            return true;
        
        $miWeb = $this->configDirPersonal($id);
        $miPagina = $miWeb . $this->sys_dir . "index.php";
        
        $miPaginaWeb = $this->configPagePersonal($id);      
        
        #echo $miPaginaWeb;exit();               
        
        $fp2 = fopen($miPagina, "w");
        fputs($fp2, $miPaginaWeb);
        fclose($fp2);                      
        
        $webempresa = $this->empresa();
        $webempresa = $webempresa[0]->web; # "http://[[website]]";
        
        $milink = explode($this->sys_dir, $miWeb);        
        $milink = $webempresa.$this->sys_dir.end($milink);
        
        return $milink;
    }
    
    private function configPagePersonal($id){                
        
        $form = $this->setFormBody($id);
        
        $this->setNoBody(); 
        
        $custom = '<script src="https://www.google.com/recaptcha/api.js">'
                .'</script>';
        
        $sumario = 'DESPUÉS DE LA CONFIRMACIÓN POR E-MAIL, 
                    USTED COMENZARÁ A RECIBIR INFORMACIONES
                    REFERENTE AL PROYECTO.';
        
        $this->setContentPagePersonal("SUMARIO", $sumario);
        $this->setContentPagePersonal("FORM", $form);
        $this->setContentPagePersonal("CUSTOM", $custom);
        
        $miPaginaWeb = $this->content;
        
        return $miPaginaWeb;
        
    }
    private function setNoBody()
    {
        $webempresa = $this->empresa();              
        $webempresa = $webempresa[0]->web; # "http://[[website]]";
        $webland = "";#TODO : "/[[website_form]].html";

        $urldef = 'http://' . $_SERVER['SERVER_NAME'] . "/";
        $contacto = file_get_contents($urldef);

        set_error_handler(
            create_function(
                '$severity, $message, $file, $line',
                'throw new ErrorException($message, $severity, $severity, $file, $line);'
            )
        );

        try{
            $contacto = file_get_contents($webempresa.$webland);
        }catch (Exception $e){
            log_message('DEV',"web personal ERROR :: $webempresa$webland");
        }

        if(!$contacto)
            $contacto = file_get_contents($urldef);

        $isFrame = stripos($contacto, "<frame");
        $notBody = !stripos($contacto, "<body");
        $notUTF = !stripos($contacto, "<meta charset");
        
        if($notBody){
           $contacto = str_replace("</head>","</head><body></body>", $contacto);
        }
        if($notUTF){
           $contacto = str_replace("<head>",
                   "<head><meta charset='utf-8'>", $contacto);
        }
        if($isFrame){
            $contacto = str_replace("frameset","frame", $contacto);
            $contacto = str_replace("</frame>","</frame>-->", $contacto);
            $contacto = str_replace("<frame","<!--<frame", $contacto);
        }
                
        $fa_fix = stripos($contacto, "/templates/shaper_helix3/css/template.css");  
        if($fa_fix)
            $contacto = str_replace('<link href="/templates/shaper_helix3/css/template.css" rel="stylesheet" type="text/css" />',"", $contacto);

        $setbody = str_replace("</body","<body", $contacto);
        $setbody = str_replace("<body","¬<body", $setbody);
        $setbody = explode("¬", $setbody);
        
        $head = $setbody[0];       
        $footer = str_replace("<body","</body",  $setbody[2]);
        $marca = '<a href="'.$webempresa.'"> 
                  <img alt="" src="'.site_url().'/logo.png" '
                        .'style="width: 100%" />
                  </a>';
        $fondo = site_url().'/template/img/login.jpg';
        
        $NoBody = array("head"=>$head,"head"=>$footer);
        
        $this->setContentPagePersonal("FONDO", $fondo);
        $this->setContentPagePersonal("MARCA", $marca); 
        $this->setContentPagePersonal("HEAD", $head); 
        $this->setContentPagePersonal("FOOTER", $footer);
         
    }

    private function setFormBody($id)
    {
        $profile = $this->traer_perfil($id);
        $nombre_completo = $profile[0]->nombre." ".$profile[0]->apellido;
        $nombre_completo = strtoupper($nombre_completo);
        $in_sponsor = "<input type='hidden' value='".$id."' name='sponsor' />";
        
        $template_file = "/application/views/auth/registration.php";
        $form = file_get_contents(getcwd() . $template_file);        
        
        $webempresa = $this->empresa();
        $captcha = $webempresa[0]->g_captcha;
        
        $form = str_replace("[[G_KEY]]", $captcha, $form);
        $form = str_replace("</form>", $in_sponsor."</form>", $form);
        $subtitle = "Patrocinador : $nombre_completo";
        $form = str_replace("Formulario de Afiliación:", $subtitle, $form);
        
        return $form;
        
    }

    
    private function setContentPagePersonal($let,$parte){
        
        $content_file = "/template/content_page.php";
        
        if(!$this->content)
            $this->content = file_get_contents(getcwd().$content_file);
        
        $this->content = str_replace("[[$".$let."]]","$parte", $this->content); 
        
    }
    
    private function configDirPersonal($id)
    {
        $mifolder = $this->traer_afiliado_username($id);
        $mifolder = strtolower($mifolder);
        
        $miWeb = $this->web_dir.$this->sys_dir.$mifolder;       
        
        if(!is_dir($miWeb))
        {
            mkdir($miWeb, 0777); #
            log_message('DEV',"Nueva web : $id > ".$miWeb);
        }
        return $miWeb;
    }

    
}
