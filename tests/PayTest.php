<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class PayTest extends TestCase
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
          'amount' => 10,
          'tag' => 'PHPTest'
        ];

        $response = $this->utility->getInstance()->pay->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->pay->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->pay->send([]);
            $this->fail("Shouldn't send Pay without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }

    /**
     * @depends testCanSendMessage
     */
    public function testFetchError()
    {
        try {
            $response = $this->utility->getInstance()->pay->status(['id' => '12345', 'extended' => true ]);
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
        };
    }
}
