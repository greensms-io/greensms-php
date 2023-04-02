<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class EnvTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
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
