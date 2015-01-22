<?php
namespace Werkint\Bundle\StatsBundle\Service\Provider;

/**
 * StatsProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StatsProviderInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function isStatPublic($name);

    /**
     * @return array|string[]
     */
    public function getStatsSupported();

    /**
     * @param string $name
     * @param array  $options
     * @return mixed
     */
    public function getStat($name, array $options);

    /**
     * @param string $name
     * @param array  $options
     * @return string|null
     */
    public function getStatCacheName($name, array $options);
}