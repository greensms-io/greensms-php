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

    public function testSendAndFetchWithDifferentArgSeparator()
    {
        ini_set('arg_separator.output', ';');
        $this->testCanFetchStatus($this->testCanSendMessage());
    }
}
