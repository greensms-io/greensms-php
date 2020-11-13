<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use \Exception;
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
}