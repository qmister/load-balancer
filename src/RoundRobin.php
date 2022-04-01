<?php

namespace Qmister\LoadBalancer;

/**
 * 轮询（Round Robin）法
 * Class RoundRobin.
 */
class RoundRobin extends AbstractLoadBalancer
{
    /**
     * RoundRobin constructor.
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
        if ($this->total <= 0) {
            throw new \RuntimeException('Nodes missing.');
        }
        $this->currentPos = ($this->currentPos + 1) % $this->total;

        return $this->nodes[$this->currentPos];
    }
}
