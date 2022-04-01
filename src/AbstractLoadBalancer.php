<?php

namespace Qmister\LoadBalancer;

/**
 * Class AbstractLoadBalancer.
 */
abstract class AbstractLoadBalancer implements LoadBalancerInterface
{
    /**
     * @var array
     */
    protected $nodes = [];
    /**
     * @var int
     */
    protected $total;
    /**
     * @var
     */
    protected $currentPos;
    /**
     * @var
     */
    protected $currentWeight;

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getCurrentPos()
    {
        return $this->currentPos;
    }
}
