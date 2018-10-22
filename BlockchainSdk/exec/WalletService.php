<?php

echo "initialize Wallet service: \n";
$service = "blockchain-wallet-service";
$command = " start --port 3000 ";
$background = " > /dev/null &";

try{
	echo shell_exec($service.$command.$background);
}catch(Exception $e){
	echo "-> SERVICE UNAVAILABLE";
}
