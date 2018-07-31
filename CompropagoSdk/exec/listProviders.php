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

$providers =  $client->api->listProviders();
