<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class GeneralTest extends TestCase {

  private $utility = null;

  public function setUp() {
    $this->utility = new Utility();
  }

  public function testCanFetchLookup() {
    $token = $this->utility->getTestToken();

    $client = new GreenSms([
      'token' => $token
    ]);
    $response = $client->account->balance();
    $this->assertObjectHasAttribute('balance', $response);
  }

  public function testRaisesExceptionOnNoCredentials() {
      $tokenResponse = $this->utility->getInstance()->account->token([
        'expire' => 5
      ]);

      $invalidTokenClient = new GreenSms([
        'token' => $tokenResponse->access_token
      ]);

      sleep(6);

      try {
        $invalidTokenClient->account->balance();
      } catch(Exception $e)  {
        $this->assertEquals('Authorization declined', $e->getMessage());
      }

  }

  public function testRaisesExceptionOnTokenExpiry() {

  }

}