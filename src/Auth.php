<?php
/**
 * PHP TruePayments Package
 *
 * @copyright 2019 Robert Coster
 * @link      https://github.com/signalfire
 */

namespace Signalfire\TruePayments;

/**
 * @package TruePayments
 * @author  Robert Coster <rob@signalfire.co.uk>
 * @since   1.0.0
 */
class Auth
{
    private $request;
    private $credentials;

    /**
     * Constructor for class
     *
     * @param Signalfire\TruePayments\Request     $request     HTTP Client
     * @param Signalfire\TruePayments\Credentials $credentials TrueLayer credentials
     */
    public function __construct(Request $request, Credentials $credentials)
    {
        $this->request = $request;
        $this->credentials = $credentials;
    }

    /**
     * Return an access token from TrueLayer
     *
     * @param string $endpoint  API Endpoint
     * @param string $method    Method to use (GET, POST etc)
     * @param string $scope     API request scope
     * @param string $grantType API grant type
     *
     * @return array
     */
    public function getAccessToken(
        $endpoint = '/connect/token',
        $method = 'POST',
        $scope = 'payment',
        $grantType = 'client_credentials'
    ) : array {
        return $this->request->makeRequest(
            $endpoint,
            $method,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            ['form_params' => [
                'client_id' => $this->credentials->getClientId(),
                'client_secret' => $this->credentials->getClientSecret(),
                'scope' => $scope,
                'grant_type' => $grantType
            ]]
        );
    }
}
