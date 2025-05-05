<?php

use GreenSMS\Http\RestException;
use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class AccountTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanFetchBalance(): void
    {
        $response = $this->utility->getInstance()->account->balance();
        $this->assertObjectHasAttribute('balance', $response);
    }

    public function testCanFetchToken(): void
    {
        $response = $this->utility->getInstance()->account->token(['expire' => 10]);
        $this->assertObjectHasAttribute('access_token', $response);
    }

    public function testCanFetchTariff()
    {
        $response = $this->utility->getInstance()->account->tariff();
        $this->assertObjectHasAttribute('tariff', $response);
        $this->assertGreaterThan(0, count($response->tariff));
    }

    public function testRaisesExceptionOnNoCredentials()
    {
        try {
            $client = new GreenSMS();
            $this->fail("Shouldn't create client without Credentials");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
        }
    }

    public function testRaisesExceptionOnInvalidCredentials()
    {
        $client = new GreenSMS([
          'user' => 'randomusername',
          'pass' => 'pass'
        ]);
        $this->expectException(RestException::class);
        $this->expectExceptionMessage('Authorization declined');
        $this->expectExceptionCode(0);

        $client->account->balance();
    }

    public function testRaisesExceptionOnInsufficientFunds()
    {
        $client = new GreenSMS([
            'user' => 'test_block_user',
            'pass' => '183456'
        ]);

        $this->expectException(RestException::class);
        $this->expectExceptionMessage('Insufficient funds');
        $this->expectExceptionCode(-1);

        $client->sms->send([
            'to' => $this->utility->getRandomPhone(),
            'txt' => 'Test134'
        ]);
    }

    public function testBlackList()
    {
        $response = $this->utility->getInstance()->account->blacklist->delete(['to' => '70000000000']);
        $this->assertEquals((object)['success' => 1], $response);
        $response = $this->utility->getInstance()->account->blacklist->add(['to' => '70000000000', 'module' => 'ALL', 'comment' => 'test']);
        $this->assertEquals((object)['success' => 1], $response);
        $this->utility->getInstance()->account->blacklist->get();
    }

    public function testLimits()
    {
        $response = $this->utility->getInstance()->account->limits->delete(['type' => 'IP']);
        $this->assertEquals((object)['success' => 1], $response);
        $this->utility->getInstance()->account->limits->get();
        $response = $this->utility->getInstance()->account->limits->set(['type' => 'IP', 'module' => 'ALL', 'value' => '1.1.1.1,8.8.8.8', 'comment' => 'test']);
        $this->assertEquals((object)['success' => 1], $response);
        $response = $this->utility->getInstance()->account->limits->set(['type' => 'REQ_PER_DAY', 'module' => 'ALL', 'value' => '6000', 'comment' => 'test']);
        $this->assertEquals((object)['success' => 1], $response);
    }

    public function testWebhook()
    {
        $response = $this->utility->getInstance()->account->webhook->delete();
        $this->assertEquals((object)['success' => 1], $response);
        $this->utility->getInstance()->account->webhook->get();
        $response = $this->utility->getInstance()->account->webhook->set(['url' => 'http://localhost', 'token' => 'test']);
        $this->assertEquals((object)['success' => 1], $response);
    }

    public function testWhitelist()
    {
        $response = $this->utility->getInstance()->account->whitelist->delete(['to' => '70000000000']);
        $this->assertEquals((object)['success' => 1], $response);
        $this->utility->getInstance()->account->whitelist->get();
        $response = $this->utility->getInstance()->account->whitelist->add(['to' => '70000000000', 'module' => 'ALL', 'comment' => 'test']);
        $this->assertEquals((object)['success' => 1], $response);
    }

    public function testWhitelistUseRules()
    {
        $this->expectException(RestException::class);

        $this->utility->getInstance()->account->whitelist->add([
            'to' => '70000000000', 
            'module' => 'ALL', 
            'comment' => str_repeat('s', 51),
        ]);
    }

    public function testPasswordResetCode()
    {
        try {
            $response = $this->utility->getInstance()->account->password->resetCode();
            $this->assertObjectHasAttribute('success', $response);
            $this->assertTrue($response->success);
        } catch (RestException $t) {
            $this->assertEquals(3, $t->getCode());
        }
    }

    public function testPasswordResetValidation()
    {
        $this->expectException(RestException::class);
        $this->expectExceptionMessage('Validation Error');
        
        $this->utility->getInstance()->account->password->reset();
    }

    public function testPasswordReset()
    {
        $this->expectException(RestException::class);
        $this->expectExceptionMessage('Invalid or expired code');
        $this->expectExceptionCode(2);
        
        $this->utility->getInstance()->account->password->reset(['code' => '123456']);
    }
}
