<?php
/**
 * PHP TruePayments Package
 *
 * @copyright 2019 Robert Coster
 * @link      https://github.com/signalfire
 */

namespace Signalfire\TruePayments;

use GuzzleHttp\Client;

/**
 * @package TruePayments
 * @author  Robert Coster <rob@signalfire.co.uk>
 * @since   1.0.0
 */
class Request extends Client
{
    /**
     * Make remote HTTP request
     *
     * @param string $endpoint Endpoint for request
     * @param string $method   Method (GET, POST) to use for request
     * @param array  $headers  HTTP Headers
     * @param array  $payload  Payload to send in request
     *
     * @return array
     */
    public function makeRequest(
        string $endpoint,
        string $method,
        array $headers = [],
        array $payload = []
    ) : array {

        try {
            $response = $this->request(
                $method,
                $endpoint,
                $headers,
                $payload
            );

            $body = json_decode($response->getBody(), true);

            return [
                'statusCode' => $response->getStatusCode(),
                'reason' => $response->getReasonPhrase(),
                'body' => $body
            ];
        } catch (\Exception $ex) {
            return [
                'error' => true,
                'reason' => $ex->getMessage()
            ];
        }
    }
}
