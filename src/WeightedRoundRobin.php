<?php

namespace Qmister\LoadBalancer;

/**
 * 平滑加权轮询调度算法
 * Class WeightedRoundRobin.
 */
class WeightedRoundRobin extends AbstractLoadBalancer
{
    /**
     * WeightedRoundRobin constructor.
     *
     * @param array $nodes
     */
    public function __construct(array $nodes)
    {
        foreach ($nodes as $node => $weight) {
            $this->nodes[] = [
                'node'           => $node,
                'weight'         => $weight,
                'current_weight' => $weight,
            ];
        }
        $this->total = count($this->nodes);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        // 获取最大当前有效权重的实例位置
        $this->currentPos = $this->getMaxCurrentWeightPos();

        // 当前权重减去权重和
        $currentWeight = intval($this->getCurrentWeight($this->currentPos)) - intval($this->getSumWeight());
        $this->setCurrentWeight($this->currentPos, $currentWeight);

        // 每个实例的当前有效权重加上配置权重
        $this->recoverCurrentWeight();

        return $this->nodes[$this->currentPos]['node'];
    }

    /**
     * 获取最大当前有效权重实例位置.
     *
     * @return int|string
     */
    protected function getMaxCurrentWeightPos()
    {
        $currentWeight = $pos = 0;
        foreach ($this->nodes as $index => $service) {
            if ($service['current_weight'] > $currentWeight) {
                $currentWeight = $service['current_weight'];
                $pos = $index;
            }
        }

        return $pos;
    }

    /**
     * 获取当前有效权重.
     *
     * @param $currentPos
     *
     * @return mixed
     */
    protected function getCurrentWeight($currentPos)
    {
        return $this->nodes[$currentPos]['current_weight'];
    }

    /**
     * 配置权重和，累加所有后端的effective_weight.
     *
     * @return int
     */
    protected function getSumWeight()
    {
        $sum = 0;
        foreach ($this->nodes as $service) {
            $sum += intval($service['weight']);
        }

        return $sum;
    }

    /**
     * 设置当前有效权重.
     *
     * @param $currentPos
     * @param $currentWeight
     */
    protected function setCurrentWeight($currentPos, $currentWeight)
    {
        $this->nodes[$currentPos]['current_weight'] = $currentWeight;
    }

    /**
     * 用配置权重调整当前有效权重.
     */
    protected function recoverCurrentWeight()
    {
        foreach ($this->nodes as $index => &$node) {
            $node['current_weight'] += intval($node['weight']);
        }
    }
}
