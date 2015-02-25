<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Werkint\Bundle\StatsBundle\Service\Provider\StatsProviderInterface;

/**
 * StatsDirector.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsDirector implements
    StatsDirectorInterface
{
    protected $isDebug;
    protected $security;
    protected $cache;

    /**
     * @param SecurityContextInterface $security
     * @param bool                     $isDebug
     * @param CacheProvider            $cache
     */
    public function __construct(
        SecurityContextInterface $security,
        $isDebug,
        CacheProvider $cache
    ) {
        $this->security = $security;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
    }

    /**
     * {@inheritdoc}
     */
    public function getStat(
        $name,
        array $options = [],
        $public = null
    ) {
        $provider = $this->getProvider($name);

        if ($public) {
            if (!$provider->isStatPublic($name)) {
                throw new AccessDeniedException('Public access to provider was restriced');
            }
        }

        if (!$provider->isStatPublic($name) && $this->security->isGranted('view', $provider)) {
            throw new AccessDeniedException('Access to provider denied');
        }

        $cacheName = $provider->getStatCacheName($name, $options);
        $cacheName = $cacheName ?: $name;
        $value = $this->cache->fetch($cacheName);
        if ($this->isDebug || !$value) {
            $value = $provider->getStat($name, $options);
            $this->cache->save($cacheName, $value);
        }

        return $value;
    }

    /**
     * Updates cachable providers
     *
     * @return int
     */
    public function updateCache()
    {
        $i = 0;
        $this->cache->deleteAll();
        foreach ($this->providers as $name => $provider) {
            try {
                $cacheName = $provider->getStatCacheName($name, []);
            } catch (\Exception $e) {
                continue;
            }
            $cacheName = $cacheName ?: $name;
            $value = $provider->getStat($name, []);
            $this->cache->save($cacheName, $value);
            $i++;
        }

        return $i;
    }

    // -- List ---------------------------------------

    /** @var StatsProviderInterface[] */
    protected $providers = [];

    /**
     * @param StatsProviderInterface $provider
     */
    public function addProvider(
        StatsProviderInterface $provider
    ) {
        foreach ($provider->getStatsSupported() as $name) {
            $this->providers[$name] = $provider;
        }
    }

    /**
     * @param string $name
     * @return StatsProviderInterface
     * @throws \InvalidArgumentException
     */
    protected function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException('Wrong provider: ' . $name);
        }
        $provider = $this->providers[$name];

        return $provider;
    }
}