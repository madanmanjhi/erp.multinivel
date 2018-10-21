<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(!isset($api_key)){
    echo "MUST BE SET AN API KEY</pre>\n";
    exit();
}    

$api_code = trim($api_key);

$Blockchain = new \Blockchain\Blockchain($api_code);

// Get Statistics
$stats = $Blockchain->Stats->get();

?><table><?php

foreach ($stats as $key => $value) {
    echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>" . PHP_EOL;
}

?></table><?php

var_dump($stats);

// Output log of activity
var_dump($Blockchain->log);

?></pre>
