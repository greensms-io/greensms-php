<?php
include_once('init.php');

$response = $client->status();
print_r($response->status);
