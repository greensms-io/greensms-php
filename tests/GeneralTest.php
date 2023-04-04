<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class GeneralTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanFetchLookup()
    {
        $response = $this->utility->getInstance()->whois->lookup(['to' => '79260000000']);
        $this->assertObjectHasAttribute('def', $response);
        $this->assertObjectHasAttribute('region', $response);
    }

    public function testCanFetchStatus()
    {
        $response = $this->utility->getInstance()->status();
        $this->assertObjectHasAttribute('status', $response);
    }
}
