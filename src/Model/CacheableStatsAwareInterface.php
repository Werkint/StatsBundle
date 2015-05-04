<?php
namespace Werkint\Bundle\StatsBundle\Model;

use Werkint\Bundle\StatsBundle\Service\ObjectCacheManager;

/**
 * Объект с кеширующими полями
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface CacheableStatsAwareInterface
{
    /**
     * @param ObjectCacheManager $proxy
     */
    public function setCacheableStatsProxy(ObjectCacheManager $proxy);
}