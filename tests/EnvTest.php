<?php
use GreenSMS\Http\RestException;
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

    public function testBaseUrl()
    {
        $subDomain = (string) bin2hex(random_bytes(10));
        putenv('GREENSMS_BASE_URL=https://'. $subDomain .'.greensms.io:');
        $client = $this->utility->getInstance();
        $response = $client->account->balance();
        putenv('GREENSMS_BASE_URL');

        $this->assertInstanceOf(RestException::class, $response);
        $this->assertStringContainsString($subDomain, $response->getMessage());
    }
}
