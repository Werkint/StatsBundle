<?php
namespace Werkint\Bundle\StatsBundle\Service\Provider;

/**
 * Используется для статистики, у которой указывается
 * время жизни
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StatsTimeoutAwareProviderInterface extends
    StatsProviderInterface
{
    /**
     * @param string $stat
     * @return float Время в секундах
     */
    public function getStatTimeout($stat);
}