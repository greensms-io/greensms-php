<?php

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class PreSendHandlerTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testReplacement()
    {
        $client = new GreenSMS([
            'user' => 'test',
            'pass' => 'test',
            'preSendHandler' => function(&$data, $uri, $method) {
                $this->assertEquals('someVal', $data['someKey']);
                $this->assertEquals('sms/send', $uri);
                $this->assertEqualsIgnoringCase('post', $method);
                $data['to'] = $this->utility->getRandomPhone();
                $data['txt'] = 'txt';
            }
        ]);

        $response = $client->sms->send(['someKey' => 'someVal', 'to'=> 111]);

        $this->assertObjectHasProperty('request_id', $response);
    }

    public function testEmptyData()
    {
        $client = new GreenSMS([
            'user' => 'test',
            'pass' => 'test',
            'preSendHandler' => function($data, $uri, $method) {
                $this->assertEquals('account/balance', $uri);
                $this->assertEmpty($data);
                $this->assertEqualsIgnoringCase('get', $method);
            }
        ]);

        $response = $client->account->balance();

        $this->assertObjectHasProperty('balance', $response);
        $this->assertEquals(4, $this->getCount());
    }

    public function testInvocable()
    {
        $invocable = new class extends TestCase{
            public function __invoke($data, $uri, $method) {
                $this->assertEquals('account/balance', $uri);
                $this->assertEmpty($data);
                $this->assertEqualsIgnoringCase('get', $method);
            }
        };

        (new GreenSMS([
            'user' => 'test',
            'pass' => 'test',
            'preSendHandler' => $invocable,
        ]))->account->balance();
    }

    public function testV4()
    {
        $client = new GreenSMS([
            'user' => 'test',
            'pass' => 'test',
            'preSendHandler' => function($data, $uri, $method) {
                $this->assertEmpty($data);
                $this->assertEquals('account/webhook', $uri);
                $this->assertEqualsIgnoringCase('get', $method);
            }
        ]);

        $response = $client->account->webhook->get();

        $this->assertObjectHasProperty('webhook', $response);
        $this->assertEquals(4, $this->getCount());
    }

    public function testNotCallable()
    {
        $this->expectException(BadFunctionCallException::class);

        new GreenSMS([
            'user' => 'test',
            'pass' => 'test',
            'preSendHandler' => '',
        ]);
    }
}
