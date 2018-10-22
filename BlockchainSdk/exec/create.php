<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(!isset($api_key)){
    echo "MUST BE SET AN API KEY</pre>\n";
    exit();
}    

$api_code = trim($api_key);

require_once("WalletService.php");

$Blockchain = new \Blockchain\Blockchain($api_code);
/** REQUIRE nodejs, npm & root env features */
$Blockchain->setServiceUrl('http://localhost:3000');

$wallet = $Blockchain->Create->create('weakPassword01!');

var_dump($wallet);

print_r($Blockchain->log);

?></pre>
