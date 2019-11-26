<?php
declare(strict_types=1);

namespace Signalfire\TruePayments\Tests;

use PHPUnit\Framework\TestCase;

use Signalfire\TruePayments\Credentials;

final class CredentialsTest extends TestCase
{
    public function testCredentialsHasProperties()
    {
        $credentials = new Credentials('ABC', '123');
        $this->assertTrue(method_exists($credentials, 'getClientId'));
        $this->assertTrue(method_exists($credentials, 'getClientSecret'));
    }

    public function testCredentialsPropertyValues()
    {
        $credentials = new Credentials('ABC', '123');
        $this->assertEquals($credentials->getClientId(), 'ABC');
        $this->assertEquals($credentials->getClientSecret(), '123');
    }
}
