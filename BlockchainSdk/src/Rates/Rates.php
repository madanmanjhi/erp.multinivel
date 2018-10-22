<?php

namespace Blockchain\Rates;

use \Blockchain\Blockchain;

class Rates
{
    public function __construct(Blockchain $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    public function get($currency = false)
    {
        $rates = array();
        $isAll = ($currency == false);
        $json = $this->blockchain->get('ticker', array('format' => 'json'));
        foreach ($json as $cur => $data) {
            $isCur = ($currency == $cur);
            if($isCur||$isAll)
                $rates[$cur] = new Ticker($cur, $data);
        }

        return $rates;
    }

    public function toETHER($amount, $symbol)
    {
        $params = array(
            'currency' => $symbol,
            'value'    => $amount,
            'format'   => 'json'
        );

        return $this->blockchain->get('toethereum', $params);
    }

    public function fromETHER($amount, $symbol = '')
    {
        $params = array(
            'currency' => $symbol,
            'value' => $amount,
            'format' => 'json'
        );

        return $this->blockchain->get('fromethereum', $params);
    }

    public function toCASH($amount, $symbol)
    {
        $params = array(
            'currency' => $symbol,
            'value'    => $amount,
            'format'   => 'json'
        );

        return $this->blockchain->get('tocash', $params);
    }

    public function fromCASH($amount, $symbol = '')
    {
        $params = array(
            'currency' => $symbol,
            'value' => $amount,
            'format' => 'json'
        );

        return $this->blockchain->get('fromcash', $params);
    }

    public function toBTC($amount, $symbol)
    {
        $params = array(
            'currency' => $symbol,
            'value'    => $amount,
            'format'   => 'json'
        );

        return $this->blockchain->get('tobtc', $params);
    }

    public function fromBTC($amount, $symbol = '')
    {
        $params = array(
            'currency' => $symbol,
            'value' => $amount,
            'format' => 'json'
        );

        return $this->blockchain->get('frombtc', $params);
    }
}