<?php
namespace Werkint\Bundle\StatsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * StatsProviderPass.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsProviderPass implements
    CompilerPassInterface
{
    const CLASS_SRV = 'werkint_stats.statsdirector';
    const CLASS_TAG = 'werkint_stats.provider';

    /**
     * {@inheritdoc}
     */
    public function process(
        ContainerBuilder $container
    ) {
        if (!$container->hasDefinition(static::CLASS_SRV)) {
            return;
        }
        $definition = $container->getDefinition(
            static::CLASS_SRV
        );

        $list = $container->findTaggedServiceIds(static::CLASS_TAG);
        foreach ($list as $id => $attributes) {
            foreach ($attributes as $a) {
                $definition->addMethodCall(
                    'addProvider', [
                        new Reference($id)
                    ]
                );
            }
        }
    }

}
