<?php
include_once('init.php');

$response = $client->account->balance();
echo "Balance : " . $response->balance. "\n";

$response = $client->account->token(['token' => 100]);
echo "Auth Token: " . $response->access_token;

$response = $client->account->tariff();
echo "Tariff Response: ";
print_r($response);