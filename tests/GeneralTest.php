<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class GeneralTest extends TestCase
{
    private $utility = null;

    public function setUp()
    {
        $this->utility = new Utility();
    }

    public function testCanFetchLookup()
    {
        $response = $this->utility->getInstance()->whois->lookup(['to' => '79260000000']);
        $this->assertObjectHasAttribute('def', $response);
        $this->assertObjectHasAttribute('region', $response);
    }
}
