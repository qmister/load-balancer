<?php

namespace Qmister\LoadBalancer\Test;

use PHPUnit\Framework\TestCase;
use Qmister\LoadBalancer\Random;

/**
 * Class LoadBalancerTest.
 */
class RandomTest extends TestCase
{
    public function testRandom()
    {
        $nodes = [
            '127.0.0.1:80',
            '127.0.0.1:81',
            '127.0.0.1:82',
        ];
        $random = new Random($nodes);
        $node = $random->next();
        $this->assertTrue(in_array($node, $nodes));
        $this->assertSame($nodes, $random->getNodes());
    }
}
