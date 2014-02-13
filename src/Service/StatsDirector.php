<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
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
     * Returns stats by key
     *
     * @param string $name
     * @param array  $options
     * @return int
     */
    public function getStat($name, array $options = [])
    {
        $row = $this->getProvider($name);

        if ($row['cached'] === null) {
            $val = $row['provider']->getStat($name, $options);
        } else {
            $val = $row['cached'];
        }
        if (!$row['realtime']) {
            $this->setProviderCached($name, $val);
        }

        return $val;
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
            if ($provider['realtime']) {
                continue;
            }
            $this->setProviderCached($name, null);
            $this->getStat($name);
            $i++;
        }

        return $i;
    }

    // -- List ---------------------------------------

    /** @var StatsProviderInterface[] */
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
        $this->providers[$name] = [
            'realtime' => $isRealtime,
            'provider' => $provider,
        ];
    }

    /**
     * @param $name
     * @return StatsProviderInterface
     * @throws \InvalidArgumentException
     */
    protected function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \InvalidArgumentException('Wrong provider: ' . $name);
        }
        $ret = $this->providers[$name];
        $ret['cached'] = $ret['realtime'] ? null : $this->getProviderCached($name);

        return $ret;
    }

    protected $cachedValues = [];

    /**
     * @param string $name
     * @return mixed
     */
    protected function getProviderCached($name)
    {
        if (!array_key_exists($name, $this->cachedValues)) {
            $this->cachedValues[$name] = $this->cache->fetch(
                static::CACHE_PREFIX . $name
            );
        }

        return $this->cachedValues[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return bool
     */
    protected function setProviderCached($name, $value)
    {
        $this->cachedValues[$name] = $value;

        return $this->cache->save(
            static::CACHE_PREFIX . $name,
            $value
        );
    }
}