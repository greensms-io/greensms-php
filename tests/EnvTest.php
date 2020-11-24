<?php
namespace GreenSMS\Tests;

use PHPUnit\Framework\TestCase;

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;

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
