<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

function pre_message($string,$exit = false){
    echo "<pre>$string</pre>\n";
    if($exit)
        exit();
}

$api_code = null;
if(!isset($api_key))
    pre_message("MUST BE SET AN API KEY",true);

require_once("wallet.php");
$myAPI = new mywallet($api_key);

return $myAPI->newWallet($pass,$email);

?>
