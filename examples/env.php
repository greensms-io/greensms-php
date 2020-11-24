<?php

require_once("./vendor/autoload.php");

use GreenSMS\GreenSMS;

putenv('GREENSMS_USER=test');
putenv('GREENSMS_PASS=test');

$envClient = new GreenSMS();

$response = $envClient->account->balance();
echo "Balance : " . $response->balance. "\n";

// Unsetting the env variable after use
putenv('GREENSMS_USER');
putenv('GREENSMS_PASS');
