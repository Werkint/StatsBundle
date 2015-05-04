<?php
namespace Werkint\Bundle\StatsBundle\Model;

use JMS\Serializer\Annotation as Serializer;
use Werkint\Bundle\StatsBundle\Service\ObjectCacheManager;

/**
 * Проксирует запросы закешированных значений к менеджеру кеша
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
trait CacheableStatsAware
{
    /**
     * @Serializer\Exclude()
     * @var ObjectCacheManager|null
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
     * @param ObjectCacheManager $proxy
     */
    public function setCacheableStatsProxy(ObjectCacheManager $proxy)
    {
        $this->_cacheablestatsaware_proxy = $proxy;
    }
}