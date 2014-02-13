<?php
namespace Werkint\Bundle\StatsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Werkint\Bundle\StatsBundle\DependencyInjection\Compiler\StatsProviderPass;

/**
 * WerkintStatsBundle.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class WerkintStatsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Stats
        $container->addCompilerPass(new StatsProviderPass);
    }
}
