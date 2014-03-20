<?php
namespace Werkint\Bundle\StatsBundle\Service\Twig;

use Werkint\Bundle\FrameworkExtraBundle\Twig\AbstractExtension;
use Werkint\Bundle\StatsBundle\Service\StatsDirector;

/**
 * WerkintStatsExtension.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintStatsExtension extends AbstractExtension
{
    const EXT_NAME = 'werkint_stats';

    protected $statsDirector;

    public function __construct(
        StatsDirector $statsDirector
    ) {
        $this->statsDirector = $statsDirector;
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->addFunction('werkint_stat', false, function (
            $name,
            array $options = []
        ) {
            return $this->statsDirector->getStat($name, $options);
        });
    }
}
