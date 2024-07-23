<?php


use GreenSMS\Http\RestException;
use GreenSMS\Tests\Utility;
use GreenSMS\Tests\TestCase;

final class CallTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanReceive()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
            'to' => $phoneNum,
            'toll_free' => 'true'
        ];

        $response = $this->utility->getInstance()->call->receive($params);
        $this->assertObjectHasAttribute('request_id', $response);
        $this->assertObjectHasAttribute('number', $response);
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
            'to' => $phoneNum,
        ];

        $response = $this->utility->getInstance()->call->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        $this->assertObjectHasAttribute('code', $response);
        $requestId = $response->request_id;
        return $requestId;
    }

    /**
     * @depends testCanSendMessage
     * */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->call->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->call->send([]);
            $this->fail("Shouldn't send Call without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }

    /** @dataProvider receiveParamsDataProvider*/
    public function testReceiveParams($params)
    {
        $this->expectException(RestException::class);
        $this->utility->getInstance()->call->receive($params);
    }

    public static function receiveParamsDataProvider(): iterable
    {
        return [
            'minTo' => [[
                'to' => str_repeat('1', 10),
            ]],
            'maxTo' => [[
                'to' => str_repeat('1', 15),
            ]],
            'maxTag' => [[
                'to' => '01234567890',
                'tag' => str_repeat('1', 37),
            ]],
        ];
    }

    /** @dataProvider sendParamsDataProvider*/
    public function testSendParams($params)
    {
        $this->expectException(RestException::class);
        $this->utility->getInstance()->call->send($params);
    }

    public static function sendParamsDataProvider(): iterable
    {
        return [
            'minTo' => [[
                'to' => str_repeat('1', 10),
            ]],
            'maxTo' => [[
                'to' => str_repeat('1', 15),
            ]],
            'maxTag' => [[
                'to' => '01234567890',
                'tag' => str_repeat('s', 37),
            ]],
        ];
    }

    /** @dataProvider statusParamsDataProvider*/
    public function testStatusParams($params)
    {
        $this->expectException(RestException::class);
        $this->utility->getInstance()->call->status($params);
    }

    public static function statusParamsDataProvider(): iterable
    {
        return [
            'minId' => [[
                'id' => str_repeat('s', 35),
            ]],
            'maxId' => [[
                'id' => str_repeat('s', 37),
            ]],
        ];
    }
}
