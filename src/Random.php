<?php

namespace Qmister\LoadBalancer;

/**
 * 随机（Random）法
 * Class Random.
 */
class Random extends AbstractLoadBalancer
{
    /**
     * Random constructor.
     *
     * @param array $nodes
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
        $this->total = count($this->nodes);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        if (empty($this->nodes)) {
            throw new \RuntimeException('Cannot select any node from load balancer.');
        }
        $this->currentPos = array_rand($this->nodes);

        return $this->nodes[$this->currentPos];
    }
}
