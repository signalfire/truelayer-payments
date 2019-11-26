<?php

declare(strict_types=1);

namespace Signalfire\TruePayments\Tests;

use PHPUnit\Framework\TestCase;
use Mockery;

use Signalfire\TruePayments\Payment;
use Signalfire\TruePayments\Request;

final class PaymentTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testPaymentHasMethods()
    {
        $request = Mockery::mock(Request::class);

        $payment = new Payment($request, 'ABCD');
        
        $this->assertTrue(method_exists($payment, 'createPayment'));
        $this->assertTrue(method_exists($payment, 'getPaymentStatus'));
    }

    public function testCreatePayment()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('makeRequest')
            ->withArgs([
                '/single-immediate-payments',
                'POST',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ABCD',
                    ],
                    'body' => json_encode(['a' => 'b'])
                ]
            ])
            ->times(1)
            ->andReturn([]);
        $payment = new Payment($request, 'ABCD');
        $response = $payment->createPayment(['a' => 'b']);
        $this->assertIsArray($response);
    }

    public function testGetPaymentStatus()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('makeRequest')
            ->withArgs([
                '/single-immediate-payments/12345',
                'GET',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ABCD',
                    ],
                ]
            ])
            ->times(1)
            ->andReturn([]);
        $payment = new Payment($request, 'ABCD');
        $response = $payment->getPaymentStatus('12345');
        $this->assertIsArray($response);
    }
}
