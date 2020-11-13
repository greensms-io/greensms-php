<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class AccountTest extends TestCase {

  private $utility = null;

  public function setUp() {
    $this->utility = new Utility();
  }

  public function testCanFetchBalance() {
    $response = $this->utility->getInstance()->account->balance();
    $this->assertObjectHasAttribute('balance', $response);
  }

  public function testCanFetchToken() {
    $response = $this->utility->getInstance()->account->token();
    $this->assertObjectHasAttribute('access_token', $response);
  }

  public function testCanFetchTariff() {
    $response = $this->utility->getInstance()->account->tariff();
    $this->assertObjectHasAttribute('tariff', $response);
    $this->assertGreaterThan(0, count($response->tariff));
  }

  public function testRaisesExceptionOnNoCredentials() {
    try {
      $client = new GreenSms();
      $this->fail("Shouldn't create client without Credentials");
    } catch(Exception $e) {
      $this->assertObjectHasAttribute('message', $e);
    }
  }

  public function testRaisesExceptionOnInvalidCredentials() {
    try {
      $client = new GreenSms([
        'user' => 'randomusername',
        'pass' => 'pass'
      ]);
      $client->account->balance();
      $this->fail("Shouldn't allow operations on Invalid Credentials");
    } catch(Exception $e) {
      $this->assertObjectHasAttribute('message', $e);
      $this->assertEquals('Authorization declined', $e->getMessage());
    }
  }

  public function testRaisesExceptionOnInsufficientFunds() {
    try {
      $client = new GreenSms([
        'user' => 'test_block_user',
        'pass' => '183456'
      ]);
      $client->sms->send([
        'to' => $this->utility->getRandomPhone(),
        'txt' => 'Test134'
      ]);
      $this->fail("Shouldn't allow send operations when Insufficient Funds");
    } catch(Exception $e) {
      $this->assertObjectHasAttribute('message', $e);
      $this->assertEquals('Insufficient funds', $e->getMessage());
    }
  }

}