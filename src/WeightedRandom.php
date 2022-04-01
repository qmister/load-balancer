<?php

namespace Qmister\LoadBalancer;

/**
 * 加权轮询（WeightRound Robin）法
 * Class WeightedRandom.
 */
class WeightedRandom extends AbstractLoadBalancer
{
    /**
     * WeightedRandom constructor.
     *
     * @param array $nodes
     */
    public function __construct(array $nodes)
    {
        foreach ($nodes as $node => $weight) {
            $this->nodes[] = [
                'node'   => $node,
                'weight' => $weight,
            ];
        }
        $this->total = count($this->nodes);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $i = $this->currentPos;
        while (true) {
            $i = ($i + 1) % $this->total;
            //已全部被遍历完一次
            if ($i === 0) {
                //减currentWeight
                $this->currentWeight -= $this->getGcd();
                //赋值currentWeight为0,回归到初始状态
                if ($this->currentWeight <= 0) {
                    $this->currentWeight = $this->getMaxWeight();
                }
            }
            //直到当前遍历实例的weight大于或等于currentWeight
            if ($this->nodes[$i]['weight'] >= $this->currentWeight) {
                $this->currentPos = $i;

                return $this->nodes[$this->currentPos]['node'];
            }
        }
    }

    /**
     * 获取最大公约数.
     *
     * @return mixed
     */
    private function getGcd()
    {
        $gcd = $this->nodes[0]['weight'];
        for ($i = 0; $i < $this->total; $i++) {
            $gcd = $this->gcd($gcd, $this->nodes[$i]['weight']);
        }

        return $gcd;
    }

    /**
     * 求两数的最大公约数(基于欧几里德算法,可使用gmp_gcd()).
     *
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    private function gcd($a, $b)
    {
        $rem = 0;
        while ($b) {
            $rem = $a % $b;
            $a = $b;
            $b = $rem;
        }

        return $a;
    }

    /**
     * 获取最大权重值
     *
     * @return int|mixed
     */
    private function getMaxWeight()
    {
        $maxWeight = 0;
        foreach ($this->nodes as $node) {
            if ($node['weight'] >= $maxWeight) {
                $maxWeight = $node['weight'];
            }
        }

        return $maxWeight;
    }
}
