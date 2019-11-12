<?php

/*
 * This file is part of the Jusibe PHP library.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unicodeveloper\Jusibe\Test;

use StdClass;
use ReflectionClass;
use PHPUnit_Framework_TestCase;
use Unicodeveloper\Jusibe\Helper;
use Unicodeveloper\Jusibe\Jusibe;
use Unicodeveloper\Jusibe\Exceptions\IsNull;
use Unicodeveloper\Jusibe\Exceptions\IsEmpty;


class JusibeTest extends PHPUnit_Framework_TestCase
{
    use Helper;

    /**
     * Instance of Mock Jusibe Instance
     * @var object
     */
    protected $jusibe;

    /**
     * Create an instance of Jusibe
     */
    public function setUp()
    {
        $this->loadEnv();
        $this->jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([$this->getValidPublicKey(), $this->getValidAccessToken()])
                    ->getMock();
    }

    /**
     * Assert that the base url is correct
     */
    public function testBaseUrlIsLegit()
    {
        $this->assertEquals(Jusibe::baseURL, 'https://jusibe.com');
    }

    /**
     * Assert that the base url doesn't correspond
     */
    public function testBaseUrlIsNotCorrect()
    {
        $this->assertNotEquals(Jusibe::baseURL, 'https://jusibe.co');
    }

    /**
    * Assert that available credits can be returned successfully
    */
    public function testAvailableCreditsCanBeReturned()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkAvailableCredits')
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->checkAvailableCreditsResponse());

        $this->assertObjectHasAttribute('sms_credits', $this->jusibe->checkAvailableCredits()->getResponse());
    }

    /**
     * @throws IsEmpty
     * test that SendSMSWithoutPayload throws an exception
     */
    public function testSendSMSWithoutPayload()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendSMS')
            ->will($this->throwException(new IsEmpty));
    }

    /**
    * Assert that available credits can be returned successfully
    */
    public function testSendSMSWithPayload()
    {
       // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendSMS')
            ->with($this->correctPayload())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->sendSMSResponse());

        $result = $this->jusibe->sendSMS($this->correctPayload())->getResponse();

        $this->assertObjectHasAttribute('sms_credits_used', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('message_id', $result);
        $this->assertEquals($this->getValidMessageID(), $result->message_id);
    }

    /**
     * @throws IsEmpty
     * test that SendSMSWithoutPayload throws an exception
     */
    public function testSendBulkSMSWithoutPayload()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendBulkSMS')
            ->will($this->throwException(new IsEmpty));
    }

    /**
    * Assert that bulk sms can be sent successfully
    */
    public function testSendBulkSMSWithPayload()
    {
       // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendBulkSMS')
            ->with($this->correctPayload())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->sendBulkSMSResponse());

        $result = $this->jusibe->sendBulkSMS($this->correctPayload())->getResponse();

        $this->assertObjectHasAttribute('request_speed', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('bulk_message_id', $result);
        $this->assertEquals($this->getValidBulkMessageID(), $result->bulk_message_id);
    }

    /**
     * Assert that delivery status can be returned successfully
     */
    public function testDeliveryStatusCanBeReturned()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkDeliveryStatus')
            ->with($this->getValidMessageID())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->checkDeliveryStatusResponse());

        $result = $this->jusibe->checkDeliveryStatus($this->getValidMessageID())->getResponse();

        $this->assertObjectHasAttribute('message_id', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('date_sent', $result);
        $this->assertObjectHasAttribute('date_delivered', $result);
        $this->assertEquals($this->getValidMessageID(), $result->message_id);
    }

    /**
     * Assert that bulk delivery status can be returned successfully
     */
    public function testBulkDeliveryStatusCanBeReturned()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkBulkDeliveryStatus')
            ->with($this->getValidBulkMessageID())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->checkBulkDeliveryStatusResponse());

        $result = $this->jusibe->checkBulkDeliveryStatus($this->getValidBulkMessageID())->getResponse();

        $this->assertObjectHasAttribute('bulk_message_id', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('created', $result);
        $this->assertObjectHasAttribute('processed', $result);
        $this->assertObjectHasAttribute('total_numbers', $result);
        $this->assertObjectHasAttribute('total_unique_numbers', $result);
        $this->assertObjectHasAttribute('total_valid_numbers', $result);
        $this->assertObjectHasAttribute('total_invalid_numbers', $result);
        $this->assertEquals($this->getValidBulkMessageID(), $result->bulk_message_id);
    }

    /**
     * @throws IsNull
     * test that CheckDeliveryStatusWithoutMessageID throws an exception
     */
    public function testCheckDeliveryStatusWithoutMessageID()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkDeliveryStatus')
            ->will($this->throwException(new IsNull));
    }

    /**
     * @throws IsNull
     * test that CheckBulkDeliveryStatusWithoutMessageID throws an exception
     */
    public function testCheckBulkDeliveryStatusWithoutMessageID()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkBulkDeliveryStatus')
            ->will($this->throwException(new IsNull));
    }

    /**
     * @expectedException \Unicodeveloper\Jusibe\Exceptions\IsNull
     */
    public function testAccessTokenWasNotPassedToJusibeConstructor()
    {
         $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([$this->getValidPublicKey(), null])
                    ->getMock();
    }

    /**
     * @expectedException \Unicodeveloper\Jusibe\Exceptions\IsNull
     */
    public function testPublicKeyWasNotPassedToJusibeConstructor()
    {
         $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([null, $this->getValidAccessToken()])
                    ->getMock();
    }

    /**
     * @expectedException \Unicodeveloper\Jusibe\Exceptions\IsNull
     */
    public function testNothingWasNotPassedToJusibeConstructor()
    {
         $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([])
                    ->getMock();
    }

    /**
     * Assert that the appropriate response is returned when Invalid Keys are used in sending an SMS
     */
    public function testInvalidKeysArePassedWhenSendingSMS()
    {
        $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([$this->getInValidPublicKey(), $this->getInvalidAccessToken()])
                    ->getMock();

        // return a reference to the stubbed Jusibe object
        $jusibe->method('sendSMS')
            ->with($this->correctPayload())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $jusibe->method('getResponse')
            ->willReturn($this->invalidKeysResponse());

        $result = $jusibe->sendSMS($this->correctPayload())->getResponse();

        $this->assertObjectHasAttribute('error', $result);
        $this->assertEquals("Invalid API Key!", $result->error);
    }

    /**
    * Assert that the appropriate response is returned when Invalid Keys are used when checking Delivery Status
    */
    public function testInvalidKeysArePassedWhenCheckingDeliveryStatus()
    {
        $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([$this->getInValidPublicKey(), $this->getInvalidAccessToken()])
                    ->getMock();

        // return a reference to the stubbed Jusibe object
        $jusibe->method('checkDeliveryStatus')
            ->with($this->getValidMessageID())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $jusibe->method('getResponse')
            ->willReturn($this->invalidKeysResponse());

        $result = $jusibe->checkDeliveryStatus($this->getValidMessageID())->getResponse();

        $this->assertObjectHasAttribute('error', $result);
        $this->assertEquals("Invalid API Key!", $result->error);
    }

    /**
    * Assert that the appropriate response is returned when Invalid Keys are used when checking Available Credits
    */
    public function testInvalidKeysArePassedWhenCheckingAvailableCredits()
    {
        $jusibe = $this->getMockBuilder('\Unicodeveloper\Jusibe\Jusibe')
                    ->setConstructorArgs([$this->getInValidPublicKey(), $this->getInvalidAccessToken()])
                    ->getMock();

        // return a reference to the stubbed Jusibe object
        $jusibe->method('checkAvailableCredits')
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $jusibe->method('getResponse')
            ->willReturn($this->invalidKeysResponse());

        $result = $jusibe->checkAvailableCredits()->getResponse();

        $this->assertObjectHasAttribute('error', $result);
        $this->assertEquals("Invalid API Key!", $result->error);
    }

    /**
     * Assert that the appropriate response is returned when Invalid Message ID is used for checkDeliveryStatus
     */
    public function testInvalidMessageIDWhenCheckingDeliveryStatus()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkDeliveryStatus')
            ->with($this->getInValidMessageID())
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->invalidMessageIDResponse());

        $result = $this->jusibe->checkDeliveryStatus($this->getInValidMessageID())->getResponse();

        $this->assertObjectHasAttribute('invalid_message_id', $result);
        $this->assertEquals("Invalid message ID", $result->invalid_message_id);
    }

    /**
     * Invoke testPrepareRequest
     */
    public function testPrepareRequest()
    {
        $this->invokeMethod($this->jusibe, 'prepareRequest', []);
    }

    /**
     * Invoke testPerformGetRequest
     */
    public function testPerformGetRequest()
    {
        $this->invokeMethod($this->jusibe, 'performGetRequest', ['/smsapi/get_credits']);
    }

    /**
    * Call protected/private method of a class.
    *
    * @param object &$object    Instantiated object that we will run method on.
    * @param string $methodName Method name to call
    * @param array  $parameters Array of parameters to pass into method.
    *
    * @return mixed Method return.
    */
    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
