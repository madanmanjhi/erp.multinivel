<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(file_exists('code.txt')) {
    $api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);

$Blockchain->setServiceUrl('http://localhost:3000');

$wallet = $Blockchain->Create->create('weakPassword01!');

var_dump($wallet);

print_r($Blockchain->log);

?></pre>
