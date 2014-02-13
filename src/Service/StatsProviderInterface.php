<?php
namespace Werkint\Bundle\StatsBundle\Service;

/**
 * StatsProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StatsProviderInterface
{
    /**
     * @param string $name
     * @param array  $options
     * @return int
     */
    public function getStat($name, array $options);
} 