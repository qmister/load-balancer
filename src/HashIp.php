<?php

namespace Qmister\LoadBalancer;

/**
 * Class HashIp.
 */
class HashIp extends AbstractLoadBalancer
{
    /**
     * @var
     */
    protected $ip;

    /**
     * HashIp constructor.
     *
     * @param array $services
     */
    public function __construct(array $services)
    {
        $this->nodes = $services;
        $this->total = count($this->nodes);
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param null $ip
     *
     * @return mixed
     */
    public function next($ip = null)
    {
        $this->ip = $ip ?? $this->getRemoteIp();
        $this->currentPos = abs($this->hashCode($this->ip) % $this->total);

        return $this->nodes[$this->currentPos];
    }

    /**
     * 获得客户端真实的IP地址
     *
     * @return array|false|mixed|string
     */
    protected function getRemoteIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }

        return $ip;
    }

    /**
     * @param $ip
     *
     * @return int
     */
    protected function hashCode($ip)
    {
        $h = 0;
        $off = 0;
        $len = strlen($ip);
        for ($i = 0; $i < $len; $i++) {
            $h = $this->intval32($this->intval32(31 * $h) + ord($ip[$off++]));
        }

        return $h;
    }

    /**
     * @param $num
     *
     * @return bool|float|int
     */
    protected function intval32($num)
    {
        $num = $num & 0xFFFFFFFF; //消掉高32位
        $p = $num >> 31; //取第一位 判断是正数还是负数
        if ($p == 1) { //负数
            $num = $num - 1;
            $num = ~$num; //取反 会当成64位取反,算出来的数就去了,所以取反之后 要消掉 高32位
            $num = $num & 0xFFFFFFFF;

            return $num * -1;
        } else {
            return $num;
        }
    }
}
