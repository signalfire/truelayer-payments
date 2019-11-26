<?php
/**
 * PHP TruePayments Package
 *
 * @copyright 2019 Robert Coster
 * @link      https://github.com/signalfire
 */

namespace Signalfire\TruePayments;

use Signalfire\TruePayments\Request;

/**
 * @package TruePayments
 * @author  Robert Coster <rob@signalfire.co.uk>
 * @since   1.0.0
 */
class Payment
{
    private $request;
    private $token;

    /**
     * Constructor for class
     *
     * @param Signalfire\TruePayments\Request $request HTTP Client
     * @param string                          $token   Auth token
     */
    public function __construct(Request $request, string $token)
    {
        $this->request = $request;
        $this->token = $token;
    }

    /**
     * Get authorization (bearer) header
     *
     * @return array
     */
    private function getAuthHeader() : array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->token)
        ];
    }

    /**
     * Get JSON content type header
     *
     * @return array
     */
    private function getJsonHeader() : array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Combine enpoint and paymentId to get url
     *
     * @param string $endpoint  Endpoint url including replacement placeholder
     * @param string $paymentId PaymentId to interpolate in Endpoint url
     * @return string
     */
    private function getPaymentIdEndpoint(string $endpoint, string $paymentId)
    {
        return sprintf($endpoint, $paymentId);
    }

    /**
     * Create and send a payment request
     *
     * @param array  $payload  Payment payload to send
     * @param string $endpoint API Endpoint
     * @param string $method   Method to use (GET, POST etc)
     *
     * @return array
     */
    public function createPayment(
        array $payload,
        string $endpoint = '/single-immediate-payments',
        string $method = 'POST'
    ) : array {
        $data = [
            'headers' => array_merge($this->getAuthHeader(), $this->getJsonHeader()),
            'body' => json_encode($payload)
        ];
        return $this->request->makeRequest($endpoint, $method, $data);
    }

    /**
     * Poll TrueLayer for payment status
     *
     * @param string $paymentId Payment ID
     * @param string $endpoint  API Endpoint with interpolation placeholder
     * @param string $method    Method to use (GET, POST etc)
     *
     * @return array
     */
    public function getPaymentStatus(
        string $paymentId,
        string $endpoint = '/single-immediate-payments/%s',
        string $method = 'GET'
    ) : array {
        return $this->request->makeRequest(
            $this->getPaymentIdEndpoint($endpoint, $paymentId),
            $method,
            $this->getAuthHeader(),
        );
    }
}
