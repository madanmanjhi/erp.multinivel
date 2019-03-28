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

$myAPI = new explorer($api_key);

class explorer{

    private $Blockchain = false;
    private $examples = array(
            '0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098',
            '1c12443203a48f42cdf7b1acee5b4b1c1fedc144cb909a3bf5edbffafb0cd204',
            '1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc',
            '1PfcDu4n11Dv7rNexM1AxrNWqkEgwCvYWD'
            );
    
    function __construct($api_key = 0000){
        
        $api_code = trim($api_key);
        $this->Blockchain = new \Blockchain\Blockchain($api_code);
    }
    
    function getBlocks($height = 1){
        // List all blocks at a certain height    
        try {
            $blocksAtHeight = $this->Blockchain->Explorer->getBlocksAtHeight($height);
            return $blocksAtHeight;
        } catch (\Blockchain\Exception\FormatError $e) {
            return pre_message($e->getMessage());
        }

    }
    
    function getBlockIndex($index = 100000){
        // Get the latest block : default
        $block = $this->Blockchain->Explorer->getLatestBlock();
        // Get block by index
        $isIndex = $index > 0;
        if($isIndex){
            try {
                $block = $this->Blockchain->Explorer->getBlockByIndex($index);
            } catch (\Blockchain\Exception\FormatError $e) {
                return pre_message($e->getMessage());
            }
        }
            
        return $block;
    }
    
    function getPreviousBlockIndex($index = 100000){
        $block = $this->getBlockIndex($index);
        // Get previous block by hash
        $hash = $block->previous_block;
        $block = $this->Blockchain->Explorer->getBlock($hash);
        return $block;
    }
    
    function getTransactionHash($hash = false){
        // First mining reward transaction
        if(!$hash)
            $hash = $this->examples[0];
        // Bitstamp audit (large) transaction
        $transaction=$this->Blockchain->Explorer->getTransaction($hash);
        return $transaction;
    }
    
    function getTransactionIndex($index = 14854){
        // Get the transaction from block 1, by index
        $transaction=$this->Blockchain->Explorer->getTransactionByIndex($index);
        return $transaction;
    }
    
    function getAddressHash($address = false){
        // Get details of a single address
        if(!$address)
            $address = $this->examples[2];
    
        $address=$this->Blockchain->Explorer->getAddress($address);
        return $address;
    }
    
    function getAddressOutputsHash($address = false,$address2 = false){
        
        if(!$address)
            $address = $this->examples[2];
        if(!$address2)
            $address2 = $this->examples[3];
        
        // Get unspent outputs for addresses
        $data=array($address, $address2);
        try {
            $unspentOutputs = $this->Blockchain->Explorer->getUnspentOutputs($data);
            return $unspentOutputs;
        } catch (\Blockchain\Exception\FormatError $e) {
            return pre_message($e->getMessage());
        }
    }
    
    function getBlocksTime($unix_time = 1262325600){
        // Get blocks from the past
        $blocks=$this->Blockchain->Explorer->getBlocksForDay($unix_time);
        return $blocks;
    }
    
    function getBlocksPool($pool = 'Eligius'){
        // Get blocks from a mining pool
        $blocks=$this->Blockchain->Explorer->getBlocksByPool($pool);
        return $blocks;
    }
    
    function getTransactionsUnconfirmed($index = false){
        // Get unconfirmed transactions
        $tx = $this->Blockchain->Explorer->getUnconfirmedTransactions();
    
        $isCount = (count($tx) > 0) && $index;
        // Get inventory data for an unconfirmed transaction in $tx
        if($isCount) {
            $hash = $tx[$index]->hash;
            $tx = json_decode($hash);
        }
        
        return $tx;
    }
    
    function getLog(){
        // Output log of activity
        $log = $this->Blockchain->log;
        return $log;
    }

}

?>
