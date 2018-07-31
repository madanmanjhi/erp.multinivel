<?php

require_once "CompropagoSdk/Client.php";

use CompropagoSdk\Client;
Client::register_autoload();
use CompropagoSdk\Factory\Factory;

$client = new Client(
    $v1,  # publickey
    $v2,  # privatekey
    $v3   # live
);

$order = Factory::getInstanceOf('PlaceOrderInfo', $order_info);

# Llamada al método 'place_order' del API para generar la orden
$neworder = $client->api->placeOrder($order);
