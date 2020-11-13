<?php
include_once('init.php');

$response = $client->whois->lookup([
  'to' => '79260000000',
]);

echo "Lookup Response";
print_r($response);
echo "\n\n";
