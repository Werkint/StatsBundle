<?php
namespace Werkint\Bundle\StatsBundle\Model;

use Werkint\Bundle\StatsBundle\Service\ObjectCacheManager;

/**
 * TODO: write "CacheableStatsAwareInterface" info
 *
 * @author Kate Shcherbak <katescherbak@gmail.com>
 */
interface NoneDoctrineCacheableStatsAwareInterface
{
    /**
     * @param ObjectCacheManager $proxy
     */
    public function setCacheableStatsProxy(ObjectCacheManager $proxy);
}