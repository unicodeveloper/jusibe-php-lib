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
use PHPUnit_Framework_TestCase;
use Unicodeveloper\Jusibe\Helper;
use Unicodeveloper\Jusibe\Jusibe;

class JusibeTest extends PHPUnit_Framework_TestCase
{
    use Helper;

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
     * @throws  IsEmpty Exception
     * @setExpectedException \Unicodeveloper\Jusibe\Exceptions\IsEmpty
     */
    public function testSendSMSWithoutPayload()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendSMS')
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->sendSMSResponse());
    }

    /**
    * Assert that available credits can be returned successfully
    */
    public function testSendSMSWithPayload()
    {
       // return a reference to the stubbed Jusibe object
        $this->jusibe->method('sendSMS')
            ->with([])
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->sendSMSResponse());

        $result = $this->jusibe->sendSMS()->getResponse();

        $this->assertObjectHasAttribute('sms_credits_used', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('message_id', $result);
        $this->assertEquals($this->getValidMessageID(), $result->message_id);
    }

    /**
     * Assert that delivery status can be returned successfully
     */
    public function testDeliveryStatusCanBeReturned()
    {
        // return a reference to the stubbed Jusibe object
        $this->jusibe->method('checkDeliveryStatus')
            ->will($this->returnSelf());

        // now stub out the getResponse method
        $this->jusibe->method('getResponse')
            ->willReturn($this->checkDeliveryStatusResponse());

        $result = $this->jusibe->checkDeliveryStatus()->getResponse();

        $this->assertObjectHasAttribute('message_id', $result);
        $this->assertObjectHasAttribute('status', $result);
        $this->assertObjectHasAttribute('date_sent', $result);
        $this->assertObjectHasAttribute('date_delivered', $result);
        $this->assertEquals($this->getValidMessageID(), $result->message_id);
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
}