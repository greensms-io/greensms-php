<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class SmsTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
          'to' => $phoneNum,
          'txt' => 'Text Message Hampshire',
          'from' => 'PHPTest',
          'tag' => 'PHPTest',
          'hidden' => 'Hampshire'
        ];

        $response = $this->utility->getInstance()->sms->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->sms->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->sms->send([]);
            $this->fail("Shouldn't send SMS without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }

    public function testMinimalTo()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '0123456789',
            'txt' => 'message',
        ]);
    }

    public function testMaximalTo()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '012345678912345',
            'txt' => 'message',
        ]);
    }

    public function testMinimalTxt()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '01234567891',
            'txt' => str_repeat('s',0),
        ]);
    }

    public function testMaximalTxt()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '01234567891',
            'txt' => str_repeat('s',919),
        ]);
    }

    public function testMaximalFrom()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '01234567891',
            'txt' => 's',
            'from' => str_repeat('s',12),
        ]);
    }

    public function testMaximalTag()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '01234567891',
            'txt' => 's',
            'tag' => str_repeat('s',37),
        ]);
    }

    public function testMaximalHidden()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->send([
            'to' => '01234567891',
            'txt' => 's',
            'hidden' => str_repeat('s',919),
        ]);
    }

    public function testMinimalId()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->status([
            'id' => str_repeat('i', 35),
        ]);
    }

    public function testMaximalId()
    {
        $this->expectException(\GreenSMS\Http\RestException::class);
        $this->utility->getInstance()->sms->status([
            'id' => str_repeat('i', 37),
        ]);
    }

    public function testSendAndFetchWithDifferentArgSeparator()
    {
        ini_set('arg_separator.output', ';');
        $this->testCanFetchStatus($this->testCanSendMessage());
        ini_set('arg_separator.output', '&');
    }
}
