<?php
/**
 * Template Name: Homepage
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package PAYBIT_Mail
 */
require 'email/PHPMailer_5.2.4/class.phpmailer.php';

if(!$_POST['g-recaptcha-response']){
	echo '
 <div class="col-md-6">
 <h4>Por favor, Completar la comprobación de seguridad.</h4>';
 #</div><script>window.location.href="/";</script>
 ;exit();
}

$val= isset($_POST['g-recaptcha-response'])&&strlen($_POST['g-recaptcha-response'])>10;
$ruta = str_replace("sites.clientes", "erp.clientes", getcwd());
$link = $ruta.'/afiliar.php';

$erplink = "http://oficinavirtual.[[website]]/auth/login";

require_once $link;

try {
    $mail = new PHPMailer(true); //New instance, with exceptions enabled

    //$body             = file_get_contents('contents.html');
	//$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
	$Destination = 'contacto@smart-owner.com';
	$Subject = 'BIENVENIDO A [[website]]';
	$Content = '
	
	<div style=\"background: #449; color: #fff\">
        <h3>Bienvenido, '.$userData['username'].' Ha sido registrado en nuestro sistema</h3></div>
	<p class="callout">
			<strong>Para ingresar al sitio de clic <a class="btn btn-primary" href="'.$erplink.'">Aqui!</a></strong>
						</p><!-- /Callout Panel -->						
						<p> o visite esta URL 
						<a href="'.$erplink.'"></a>'.$erplink.'</p>						
						<p>Username: '.$userData['username'].'<br /></p>
						<p>Correo: '.$userData['email'].'</p>'.
						'<p>Clave: '.$userData['recovery'].'<br /></p>'.
						'<p>Id del Usuario:  '.$afilia.'</p>
	
	';
	
$mail->IsSMTP();                           // tell the class to use SMTP
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port 26
	$mail->Host       = "a2plvcpnl41287.prod.iad2.secureserver.net"; // SMTP server mail.sistemadelealtad.com
	$mail->Username   = "dev@networksoft.mx";     // SMTP server username afiliaciones@sistemadelealtad.com
	$mail->Password   = "QCmarcel7622";            // SMTP server password SXNS2016!

	$mail->IsSendmail();  // tell the class to use Sendmail

	$mail->From       = 'contacto@[[website]]';
	$mail->FromName   = "[[website]]";
	
	$mail->AddReplyTo($_POST['email'],$_POST['nombres'].' '.$_POST['apellidos']);

	$to = $userData['email'];//"qc.marcel@gmail.com";

    $mail->AddAddress($to);	

	$mail->Subject  = $Subject;

	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($Content);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	
} catch (phpmailerException $e) {
	echo $e->errorMessage();exit();
}

echo '<div class="">
  <h4>CONFIRMADO, El Registro se ha completado satisfactoriamente.</h4>
  <p>Revise su correo electronico, acceda rapidamente haciendo
           <a href="'.$erplink.'">Click Aqui</a>.</p>
  </div>
	<script>setTimeout (\''.$erplink.'"\', 5000);</script>';
 
 
?>
