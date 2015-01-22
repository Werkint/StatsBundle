<?php
namespace Werkint\Bundle\StatsBundle\Model;

/**
 * TODO: write "CacheableStatsAwareInterface" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface CacheableStatsAwareInterface
{
    /**
     * @param CacheProxy $proxy
     */
    public function setCacheableStatsProxy(CacheProxy $proxy);
}