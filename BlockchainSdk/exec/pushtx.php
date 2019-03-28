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

$myAPI = new pushtx($api_key);

class pushtx{

    private $Blockchain = false;
    private $examples = array(0);

    function __construct($api_key = 0000){

        $api_code = trim($api_key);
        $this->Blockchain = new \Blockchain\Blockchain($api_code);
    }

    function setDefaultTx(){
        // The raw transaction hex for a valid transaction, will not
        // send though, since it's an existing transaction
        $data_example = '0100000001adba98f4ddfb9183ded2'.
            'fddd8b07fb45089a11851b4f97377c'.
            'b740e0aade147c020000008a473044'.
            '02203b75e3b8b05bdcaba1c2fa2f64'.
            'c2b98b7e128fc2c11e160fe354870e'.
            '52404a3902201cec2031b23acc2b9d'.
            'f18544af8334a05306853d081b3c14'.
            'ef9ebb373886d9c80141049bd94545'.
            '1cb4e4b5e0c93fd69b34ec9fc0cded'.
            '94b13ca5d4b3674a1b01e44660c64e'.
            '5e01195253dfd9648ce9e8fcca91ad'.
            '20a036a0ec75b4006355e00813b03d'.
            'ffffffff0130390a00000000001976'.
            'a9142bb82f7eaf5942e6bf3a826bb8'.
            'a285946d9ad5ca88ac00000000';
        return $data_example;
    }

    function processTX($tx = false){
        try {
            return $this->Blockchain->Push->TX($tx);
        } catch (Exception $e) {
            // Something went wrong
            pre_message($e->getMessage());
            return false;
        }
    }

    function getLog(){
        // Output log of activity
        $log = $this->Blockchain->log;
        return $log;
    }

}

?>