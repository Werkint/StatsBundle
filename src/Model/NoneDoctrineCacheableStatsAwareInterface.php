<?php
namespace Werkint\Bundle\StatsBundle\Model;

/**
 * TODO: write "CacheableStatsAwareInterface" info
 *
 * @author Kate Shcherbak <katescherbak@gmail.com>
 */
interface NoneDoctrineCacheableStatsAwareInterface
{
    /**
     * @param CacheProxy $proxy
     */
    public function setCacheableStatsProxy(CacheProxy $proxy);
}