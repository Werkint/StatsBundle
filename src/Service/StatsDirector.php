<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * StatsDirector.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsDirector
{
    const CACHE_PREFIX = 'provider_';

    protected $security;
    protected $cache;

    /**
     * @param SecurityContextInterface $security
     * @param CacheProvider            $cache
     */
    public function __construct(
        SecurityContextInterface $security,
        CacheProvider $cache
    ) {
        $this->security = $security;
        $this->cache = $cache;
    }

    /**
     * Returns stats by key.
     * if public is true - stat is checked for being available through controller
     * if public is null - the stat is checked for role (if needed)
     *
     * @param string    $name
     * @param array     $options
     * @param bool|null $public
     * @throws AccessDeniedException If acccess restricted
     * @return int
     */
    public function getStat(
        $name,
        array $options = [],
        $public = null
    ) {
        $provider = $this->getProvider($name);

        if ($public) {
            if (!$provider->getProvider()->isPublic($name)) {
                throw new AccessDeniedException('Public access to provider was restriced');
            }
        }

        if ($public === null && !$this->security->isGranted('view', $provider)) {
            throw new AccessDeniedException('Access to provider denied');
        }

        if ($provider->getCached() === false) {
            $provider->setCached($provider->getProvider()->getStat($name, $options));
            if (!$provider->isRealtime()) {
                $this->saveToCache($provider);
            }
        }

        return $provider->getCached();
    }

    /**
     * Updates cachable providers
     *
     * @return int
     */
    public function updateCache()
    {
        $i = 0;
        foreach ($this->providers as $name => $provider) {
            if ($provider->isRealtime()) {
                continue;
            }
            $provider->setCached(false);
            $this->saveToCache($provider);
            $this->getStat($name, [], false);
            $i++;
        }

        return $i;
    }

    // -- List ---------------------------------------

    /** @var ProviderRow[] */
    protected $providers = [];

    /**
     * @param string                 $name
     * @param StatsProviderInterface $provider
     * @param bool                   $isRealtime
     */
    public function addProvider(
        $name,
        StatsProviderInterface $provider,
        $isRealtime
    ) {
        $this->providers[$name] = new ProviderRow(
            $provider,
            $name,
            $isRealtime
        );
    }

    /**
     * @param string $name
     * @return ProviderRow
     * @throws \InvalidArgumentException
     */
    protected function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException('Wrong provider: ' . $name);
        }
        $provider = $this->providers[$name];
        if ($provider->getCached() === false && !$provider->isRealtime()) {
            $provider->setCached($this->cache->fetch(
                static::CACHE_PREFIX . $provider->getName()
            ));
        }

        return $provider;
    }

    /**
     * @param ProviderRow $provider
     * @return bool
     */
    protected function saveToCache(ProviderRow $provider)
    {
        return $this->cache->save(
            static::CACHE_PREFIX . $provider->getName(),
            $provider->getCached()
        );
    }
}