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
        return $this->request->makeRequest(
            $endpoint,
            $method,
            [
                'Authorization' => sprintf('Bearer %s', $this->token),
                'Content-Type' => 'application/json'
            ],
            $payload,
            $timeout
        );
    }

    /**
     * Poll TrueLayer for payment status
     *
     * @param string $paymentId Payment ID
     * @param string $endpoint  API Endpoint
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
            sprintf($endpoint, $paymentId),
            $method,
            ['Authorization' => sprintf('Bearer %s', $this->token)],
            [],
            $timeout
        );
    }
}
