<?php

namespace Qmister\LoadBalancer\Test;

use PHPUnit\Framework\TestCase;
use Qmister\LoadBalancer\WeightedRoundRobin;

/**
 * Class WeightedRoundRobinTest.
 */
class WeightedRoundRobinTest extends TestCase
{
    public function testWeightedRoundRobin()
    {
        $ip1 = '127.0.0.1:80';
        $ip2 = '127.0.0.1:81';
        $ip3 = '127.0.0.1:82';
        $nodes = [
            $ip1 => 10,
            $ip2 => 20,
            $ip3 => 10,
        ];
        $weightedRoundRobin = new WeightedRoundRobin($nodes);
        $ips = [];
        for ($i = 0; $i < 4; $i++) {
            $node = $weightedRoundRobin->next();
            if (!isset($ips[$node])) {
                $ips[$node] = 1;
            } else {
                $ips[$node]++;
            }
        }
        $this->assertSame(1, $ips[$ip1]);
        $this->assertSame(2, $ips[$ip2]);
        $this->assertSame(1, $ips[$ip3]);
    }
}
