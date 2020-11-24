<?php
namespace GreenSMS\Tests;

use PHPUnit\Framework\TestCase;

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;

final class TokenTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanFetchLookup()
    {
        $tokenResponse = $this->utility->getInstance()->account->token(['expire' => 10]);

        $client = new GreenSMS([
          'token' => $tokenResponse->access_token
        ]);
        $response = $client->account->balance();
        $this->assertObjectHasAttribute('balance', $response);
    }

    public function testRaisesExceptionOnNoCredentials()
    {
        $tokenResponse = $this->utility->getInstance()->account->token([
          'expire' => 5
        ]);

        $invalidTokenClient = new GreenSMS([
          'token' => $tokenResponse->access_token
        ]);

        sleep(6);

        try {
            $invalidTokenClient->account->balance();
            $this->fail("Shouldn't allow operations on Expired Auth Token");
        } catch (Exception $e) {
            $this->assertEquals('Authorization declined', $e->getMessage());
        }
    }

    public function testRaisesExceptionOnTokenExpiry()
    {
    }
}
