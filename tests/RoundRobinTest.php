<?php

namespace Qmister\LoadBalancer\Test;

use PHPUnit\Framework\TestCase;
use Qmister\LoadBalancer\RoundRobin;

class RoundRobinTest extends TestCase
{
    public function testRoundRobin()
    {
        $nodes = [
            '127.0.0.1:80',
            '127.0.0.1:81',
            '127.0.0.1:82',
        ];
        $random = new RoundRobin($nodes);
        $node = $random->next();
        $this->assertTrue(in_array($node, $nodes));
        $this->assertSame($nodes, $random->getNodes());
    }
}
