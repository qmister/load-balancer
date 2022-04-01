<?php

namespace Qmister\LoadBalancer;

/**
 * Interface LoadBalancerInterface.
 */
interface LoadBalancerInterface
{
    /**
     * @return mixed
     */
    public function next();
}
