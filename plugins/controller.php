<?php // if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Mexico_City');

$ruta = str_replace("sites.clientes", "erp.clientes", getcwd());
$link = $ruta.'/bk/dataset.php';
$val = md5(date('Y-m-d')."^".date('H:i:s')."->".$_SERVER['REMOTE_ADDR']);
require_once $link;

$function = isset($_POST['fnct']) ? $_POST['fnct'] : false;

if (! $function || ! function_exists($function)) {
    echo "Proceso no definido";
    exit();
}

$function($db);

function validate_user_data($db)
{    
    
    if(!$_POST['username']||!$_POST['mail']||!$_POST['password']||!$_POST['confirm_password']){
        echo "<script>
				  $('.btn-next').attr('disabled','disabled');
				  </script>
				";exit();
    }
    
    $use_username=use_username($db);
    $use_mail=use_mail($db);
    $use_pass=confirm_password();
    
   if($use_username||$use_mail||$use_pass){
        echo "<script>
				  $('.btn-next').attr('disabled','disabled');
				  </script>
				";
    } else {
        echo "<script>
				  $('.btn-next').removeAttr('disabled');
				  </script>
				";
    }
}

function use_username($db)
{
    $username = isset($_POST['username']) ? strtolower($_POST['username']) : "";
    $query = "select * from users where lower(username) = '".$username."'";
    $use_username =  newQuery($db,$query);
    
    if($use_username){
        echo "<script>
					$('.btn-next').attr('disabled','disabled');
				  </script>
				<p style='color: red;'>El Usuario no está disponible</p>";return true;
    }else{
        echo "";return false;
    }
}

function use_mail($db)
{
    $email = preg_match(
        '/^[A-z0-9_\-.]+[A-z0-9]{1,}+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,}$/', $_POST['mail']
        );
    
    $mail = isset($_POST['mail']) ? strtolower($_POST['mail']) : "";
    $query = "select * from users where lower(email) like '".$mail."'";
    $use_mail=newQuery($db,$query); 
    
    if($use_mail){
        echo "<p style='color: red;'>El email no está disponible.</p>";return true;
    }else if(!$email){
        echo "<p style='color: red;'>No es un email valido.</p>";return true;
    }else {
        echo "";return false;
    }
}

function confirm_password()
{
    if($_POST['password']!=$_POST['confirm_password']){
        echo "<p style='color: red;' >Las contraseñas no coinciden. </p>";return true;
    }else{
        echo "";return false;
    }
}

function use_keyword($db)
{
    $word = isset($_POST['keyword']) ? strtolower($_POST['keyword']) : "";
    $query = "select * from user_profiles where lower(keyword) = '".$word."'";
    $use_keyword=newQuery($db,$query); 
    
    if($use_keyword)
    {
        echo "La identificación no está disponible";
    }
}