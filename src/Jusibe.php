<?php

namespace Unicodeveloper\Jusibe;

use Exception;
use GuzzleHttp\Client;

class Jusibe {

    /**
     * Jusibe API Base Url
     */
    const baseURL = 'https://jusibe.com';

    /**
     * Public key
     * @var string
     */
    protected $publicKey;

    /**
     * Access token
     * @var string
     */
    protected $accessToken;

    /**
     *  Response from requests made to Jusibe
     * @var mixed
     */
    protected $response;

    /**
     * Instance of Guzzle Client
     * @var object
     */
    protected $client;

    /**
     * Constructor
     * @param $publicKey   string
     * @param $accessToken string
     */
    public function __construct($publicKey = null, $accessToken = null)
    {
        $this->publicKey = $publicKey;
        $this->accessToken = $accessToken;
        $this->prepareRequest();
    }

    /**
     * Instantiate Guzzle Client and prepare request for http operations
     * @return none
     */
    private function prepareRequest()
    {
        $this->client = new Client(['base_uri' => self::baseURL]);
    }

    /**
     * Perform a GET request
     * @param  string $relativeUrl
     * @return none
     */
    private function performGetRequest($relativeUrl)
    {
        $this->response = $this->client->request('GET', $relativeUrl, [
            'auth' => [$this->publicKey, $this->accessToken]
        ]);
    }

    /**
     * Perform a POST request
     * @param  string $relativeUrl
     * @param  array $data
     * @return none
     */
    private function performPostRequest($relativeUrl, $data)
    {
        $this->response = $this->client->request('POST', $relativeUrl, [
            'auth' => [$this->publicKey, $this->accessToken],
            'form_params' => $data
        ]);
    }

    /**
     * Send SMS using the Jusibe API
     * @param  array $payload
     * @return $this
     */
    public function sendSMS($payload = [])
    {
        if (empty($payload)) {
            throw new Exception('Message Payload can not be empty. Please fill the appropriate details');
        }

        $this->performPostRequest('/smsapi/send_sms', $payload);

        return $this;
    }

    /**
     * Check the available SMS credits left in your JUSIBE account
     * @return $this
     */
    public function checkAvailableCredits()
    {
        $this->performGetRequest('/smsapi/get_credits');

        return $this;
    }

    /**
     * Check the delivery status of a sent SMS
     * @param  string $messageID
     * @return $this
     */
    public function checkDeliveryStatus($messageID = null)
    {
        if (is_null($messageID)) {
            throw new Exception('Message ID can not be empty.');
        }

        $this->performGetRequest("/smsapi/delivery_status?message_id={$messageID}");

        return $this;
    }

    /**
     * Return the response object of any operation
     * @return object
     */
    public function andReturnResult()
    {
        print_r(json_decode($this->response->getBody()));
    }
}