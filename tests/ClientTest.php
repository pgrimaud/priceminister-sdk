<?php

namespace Priceminister\Tests;

use InvalidArgumentException;
use Priceminister\PriceministerClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testClientWithValidCredentials()
    {
        $client = new PriceministerClient('toto', 'p4ssw0rd');
        $this->assertSame('toto', $client->getLogin());
        $this->assertSame('p4ssw0rd', $client->getPassword());
    }

    public function testClientWithEmptyCredentials()
    {
        $this->expectException(InvalidArgumentException::class);
        new PriceministerClient();
    }
}

