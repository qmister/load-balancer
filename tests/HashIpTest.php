<?php

namespace Qmister\LoadBalancer\Test;

use PHPUnit\Framework\TestCase;
use Qmister\LoadBalancer\HashIp;

class HashIpTest extends TestCase
{
    public function testHashIp()
    {
        $ip = '127.0.0.1';
        $nodes = [
            '127.0.0.1:80',
            '127.0.0.1:81',
            '127.0.0.1:82',
        ];

        $haship = new HashIp($nodes);

        $node = $haship->next($ip);
        $this->assertTrue(in_array($node, $nodes));
        $this->assertSame($nodes, $haship->getNodes());
    }
}
