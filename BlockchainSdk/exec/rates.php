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

$myAPI = new rates($api_key);

class rates{

    private $Blockchain = false;
    private $examples = array();

    function __construct($api_key = 0000){

        $api_code = trim($api_key);
        $this->Blockchain = new \Blockchain\Blockchain($api_code);
    }

    function convertTo($units = 500,$currency = 'USD',$to = "BTC"){
        // Convert a fiat amount to BTC
        $funct = "to".$to;
        $amount = $this->Blockchain->Rates->$funct($units, $currency);
        return $amount;
    }

    function getRates($cur = false){
        // Get Exchanges Rates
        $rates = $this->Blockchain->Rates->get($cur);
        return $rates;
    }

    function getLog(){
        // Output log of activity
        $log = $this->Blockchain->log;
        return $log;
    }

}

?>