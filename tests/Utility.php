<?php

namespace GreenSms\Tests;

use GreenSms\GreenSms;

class Utility
{
    public function getInstance()
    {
        $client = new GreenSms([
          'user' => 'test',
          'pass' => 'test'
        ]);

        return $client;
    }

    public function getRandomPhone($min = 70000000111, $max = 70009999999)
    {
        $phoneNum = $this->getRandomNumber($min, $max);
        return $phoneNum;
    }

    public function getRandomNumber($min, $max)
    {
        return strval(rand($min, $max));
    }

    public function getTestToken()
    {
        return 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoibWFuYW4yNCIsImlhdCI6MTYwMzU2OTgzMCwiaXNzIjoiYXBpLmdyZWVuc21zLnJ1In0.OKiv5itdirS_PuPJj5kgGcR2_9DsC9ALW9c8FvvHSF4';
    }

    public function getInstanceWithEnv()
    {
        putenv('GREENSMS_USER=test');
        putenv('GREENSMS_PASS=test');
        $envClient = new GreenSms();

        // Unsetting the env variable after use
        putenv('GREENSMS_USER');
        putenv('GREENSMS_PASS');

        return $envClient;
    }
}
