<?php
namespace Werkint\Bundle\StatsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Werkint\Bundle\StatsBundle\Model\CacheableStatsAwareInterface;
use Werkint\Bundle\StatsBundle\Model\CacheProxy;

/**
 * TODO: write "CacheProxyListener" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CacheProxyListener implements
    EventSubscriber
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            //ORM\Events::loadClassMetadata,
            ORM\Events::postLoad,
        ];
    }

    /**
     * @param ORM\Event\LifecycleEventArgs $args
     */
    public function postLoad(ORM\Event\LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof CacheableStatsAwareInterface) {
            $entity->setCacheableStatsProxy($this->serviceCacheProxy());
        }
    }

    /**
     * @return CacheProxy
     */
    protected function serviceCacheProxy()
    {
        return $this->container->get('werkint_stats.cacheproxy');
    }
}