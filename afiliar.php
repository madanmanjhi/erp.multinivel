<?php
$secure = isset($val) ? $val : false;

if (! $secure) {
    echo '<div class="col-md-6">
 <h4>ROBOT DETECTADO</h4>
 </div><script>window.location.href="/";</script>';
    exit();
}

$datos = isset($_POST) && sizeof($_POST) > 7 ? $_POST : false;

if (! $datos) {
    echo '<div class="col-md-6">
  <h4>Intente de nuevo</h4>
  </div><script>window.location.href="/";</script>';
    exit();
}

function setDir_(){
   return str_replace("sites.clientes", "erp.clientes", getcwd());
}

require_once(setDir_()."/bk/dataset.php");

$query = "SELECT * FROM users where lower(email) = '".strtolower($datos["email"])."'";
$isRepeated = newQuery($db,$query);

if ($isRepeated) {
    echo '<div class="col-md-6">
  <h4>El Email ingresado ya esta registrado.</h4>
     <p>Si desea Iniciar Sesion con esta cuenta 
           <a href="http://oficinavirtual.[[website]]/">Haz Click Aqui</a>.</p>
  </div><script>setTimeout (\'window.location.href="/"\', 5000);</script>';
    terminar(1);
}

include(setDir_()."/bk/registro.php");
$registro = new registro($db,$datos);
$afilia = $registro->crearUsuario();

if(!$afilia){
        echo '<div class="col-md-6">
  <h4>ABORTADO, El proceso no pudo completarse.</h4>
  <p>Por favor, intente de nuevo y/o verifique la informacion proporcionada.</p>
  </div><script>setTimeout (\'window.location.href="/"\', 5000);</script>';
        terminar(1);
}

unlink(setDir_(). "/bk/db_access.php");

$userData = $registro->userData;