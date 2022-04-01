<?php

namespace Qmister\LoadBalancer\Test;

use PHPUnit\Framework\TestCase;
use Qmister\LoadBalancer\WeightedRandom;

/**
 * Class WeightedRandomTest.
 */
class WeightedRandomTest extends TestCase
{
    public function testWeightedRandom()
    {
        $nodes = [
            '127.0.0.1:80' => 10,
            '127.0.0.1:81' => 20,
            '127.0.0.1:82' => 10,
        ];
        $weightedRandom = new WeightedRandom($nodes);
        $node = $weightedRandom->next();
        $this->assertTrue(in_array($node, array_keys($nodes)));
    }
}
