<?php

declare(strict_types=1);

namespace Signalfire\TruePayments\Tests;

use PHPUnit\Framework\TestCase;
use Mockery;

use Signalfire\TruePayments\Auth;
use Signalfire\TruePayments\Credentials;
use Signalfire\TruePayments\Request;

final class AuthTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testAuthHasMethods()
    {
        $request = Mockery::mock(Request::class);

        $credentials = new Credentials('ABC', '123');

        $auth = new Auth($request, $credentials);
        
        $this->assertTrue(method_exists($auth, 'getAccessToken'));
    }

    public function testAuthGetAccessToken()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('makeRequest')
            ->withArgs([
                '/connect/token',
                'POST',
                [
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                    'form_params' => [
                        'client_id' => 'ABC',
                        'client_secret' => '123',
                        'scope' => 'payments',
                        'grant_type' => 'client_credentials'
                    ]
                ]
            ])
            ->times(1)
            ->andReturn([]);

        $credentials = new Credentials('ABC', '123');

        $auth = new Auth($request, $credentials);

        $response = $auth->getAccessToken();
        
        $this->assertIsArray($response);
    }
}
