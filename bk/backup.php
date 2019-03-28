#!/usr/bin/php
<?php
date_default_timezone_set('America/Mexico_City');#city
echo "Leyendo datos..."; 
	
	#function setDir($base="/var/www"){	
	function setDir_($base="/home/startupns/www/"){	
		$project="erp.networksoft"; #"erp.clientes" 
		$project.="/rtm";#"erp.multinivel" 
		return $base.$project;
	}
	
	function setCommand_ ($db,$file,$data = ""){
		$hostname = $db['default']['hostname'];
		$username = $db['default']['username'];
		$password = $db['default']['password'];
		$database = $db['default']['database'];
        #echo setDir()."/bk/".$file." ".$hostname." ".$username." ".$password." ".$database." \"$data\"";
		return setDir()."/bk/".$file." ".$hostname." ".$username." ".$password." ".$database." \"$data\"";
	}
	
$code = "->networksoft";#code
$val = md5(date('Y-m-d')."^".date('H:i:s').$code);    
	 require_once(setDir_()."/bk/dataset.php");
echo "
>OK
Cargando base de datos...";
		
	$db_config=setDir()."/application/config/database.php";
	$linea="";
	$file = fopen($db_config, "r");
	while(!feof($file)){
		$linea.=fgets($file)."\n";
	}
	fclose($file);
			
	$val="<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
	$texto=str_replace($val, "<?php ", $linea);
		  
	$fp2 = fopen(setDir()."/bk/db_access.php", "w"); 
	fputs($fp2, $texto);
	fclose($fp2);
		
	include(setDir()."/bk/db_access.php");
echo "
>OK
Creando dump...";
	$command = setCommand($db,"bk_daily.sh");
	exec($command);
echo "
>OK
!Dump creado con exito!
";

echo "\n\n>PROCESOS 1> AUTOBONO DIARIO\n";
#include(setDir()."/bk/autobono.php");
echo "\n\nCargando Datos\n";
#$autobono = new autobono($db);
echo "\n>OK\nProcesando Datos\n";
#$afiliados = $autobono->calcular();
echo "\n>OK\n\n!PROCESO COMPLETADO!\n";	

echo "

PROCESOS 2> Compresion dinamica:
";
#include(setDir()."/bk/autored.php");

echo "
>OK
Procesando Datos
";
#$autored = new autored($db);
echo "
>OK
Realizando Acciones
";
#$autored->procesar();	
echo "
>OK
!Proceso realizado con exito
";
	
