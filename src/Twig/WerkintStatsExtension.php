<?php
namespace Werkint\Bundle\StatsBundle\Twig;

use Werkint\Bundle\StatsBundle\Service\StatsDirector;
use Werkint\Bundle\WebappBundle\Twig\AbstractExtension;

/**
 * WerkintStatsExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintStatsExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_stats';

    /**
     * @param StatsDirector $stats
     */
    public function __construct(
        StatsDirector $stats
    ) {
        $this->addFunction('werkint_stat', false, function ($name, array $options = []) use (&$stats) {
            return $stats->getStat($name, $options);
        });
    }
}
