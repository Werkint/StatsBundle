<?php
namespace Werkint\Bundle\StatsBundle\Model;

/**
 * TODO: write "CachableStatsAware" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
trait CacheableStatsAware
{
    /**
     * @var CacheProxy|null
     */
    protected $_cacheablestatsaware_proxy;

    /**
     * @param string $name
     * @return mixed
     */
    protected function proxyCacheableStatsProperty($name)
    {
        return $this->_cacheablestatsaware_proxy->getStatsForObject($this, $name);
    }

    /**
     * @param CacheProxy $proxy
     */
    public function setCacheableStatsProxy(CacheProxy $proxy)
    {
        $this->_cacheablestatsaware_proxy = $proxy;
    }
}