<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Signalfire\TruePayments\Auth;
use Signalfire\TruePayments\Credentials;
use Signalfire\TruePayments\Request;

final class AuthTest extends TestCase
{
    private $request;

    public function setUp() : void
    {
        $this->request = $this->createMock(Request::class);
    }

    public function testAuthReturnsToken()
    {
        //$request = $this->createMock(Request::class);

        $this->request->method('makeRequest')->willReturn([
            'statusCode' => 200,
            'reason' => 'OK',
            'body' => [
                'access_token' => 'jwt',
                'expires_in' => 3600,
                'token_type' => 'Bearer'                
            ]
        ]);

        $credentials = new Credentials('ABC', '123');

        $auth = new Auth($this->request, $credentials);

        $response = $auth->getAccessToken();

        $this->assertEquals($response['statusCode'], 200);
        $this->assertEquals($response['reason'], 'OK');
        $this->assertEquals($response['body']['access_token'], 'jwt');
        $this->assertEquals($response['body']['expires_in'], 3600);
        $this->assertEquals($response['body']['token_type'], 'Bearer');
    }

    public function testAuthReturnsError()
    {
        $this->request->method('makeRequest')->willReturn([
            'error' => true,
            'reason' => 'ABCD',
        ]);

        $credentials = new Credentials('ABC', '123');

        $auth = new Auth($this->request, $credentials);

        $response = $auth->getAccessToken();   
        
        $this->assertTrue((bool)$response['error']);
        $this->assertEquals($response['reason'], 'ABCD');
    }
}
