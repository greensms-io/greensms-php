<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class EnvTest extends TestCase
{
    private $utility = null;

    public function setUp()
    {
        $this->utility = new Utility();
    }

    public function testCanFetchLookup()
    {
        $client = $this->utility->getInstanceWithEnv();
        $response = $client->account->balance();
        $this->assertObjectHasAttribute('balance', $response);
    }
}
