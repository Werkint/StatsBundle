<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Werkint\Bundle\CacheBundle\Service\Annotation\CacheAware;
use Werkint\Bundle\CacheBundle\Service\Contract\CacheAwareInterface;
use Werkint\Bundle\CacheBundle\Service\Contract\CacheAwareTrait;
use Werkint\Bundle\StatsBundle\Service\Provider\StatsProviderInterface;
use Werkint\Bundle\StatsBundle\Service\Provider\StatsTimeoutAwareProviderInterface;

/**
 * @see    StatsDirectorInterface
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @CacheAware(namespace="werkint_stats")
 */
class StatsDirector implements
    StatsDirectorInterface,
    CacheAwareInterface
{
    use CacheAwareTrait;

    protected $isDebug;
    protected $security;

    /**
     * @param SecurityContextInterface $security
     * @param bool                     $isDebug
     */
    public function __construct(
        SecurityContextInterface $security,
        $isDebug
    ) {
        $this->security = $security;
        $this->isDebug = $isDebug;
    }

    /**
     * {@inheritdoc}
     */
    public function getStat(
        $name,
        array $options = [],
        $public = null,
        $forceUpdate = false
    ) {
        $provider = $this->getProvider($name);

        if ($public) {
            if (!$provider->isStatPublic($name)) {
                throw new AccessDeniedException('Public access to provider was restricted');
            }
        }

        if (!$provider->isStatPublic($name) && $this->security->isGranted('view', $provider)) {
            throw new AccessDeniedException('Access to provider denied');
        }

        $getStat = function () use ($provider, $name, $options) {
            $value = $provider->getStat($name, $options);
            return [
                'value'     => $value,
                'timestamp' => microtime(true),
            ];
        };

        $cacheName = $provider->getStatCacheName($name, $options);
        $cacheName = $cacheName ?: $name;

        $value = $this->cacheProvider->fetch($cacheName);
        if ($this->isDebug || $forceUpdate || !$value) {
            $this->cacheProvider->save($cacheName, $value = $getStat());
        } else {
            if ($provider instanceof StatsTimeoutAwareProviderInterface) {
                if (microtime(true) - $value['timestamp'] > $provider->getStatTimeout($name)) {
                    $this->cacheProvider->save($cacheName, $value = $getStat());
                }
            }
        }

        return $value['value'];
    }

    /**
     * Updates cachable providers
     *
     * @return int
     */
    public function updateCache(array $array = [])
    {
        $i = 0;
        $this->cacheProvider->deleteAll();
        foreach ($this->providers as $name => $provider) {
            try {
                $cacheName = $provider->getStatCacheName($name, $array);
            } catch (\Exception $e) {
                continue;
            }
            $cacheName = $cacheName ?: $name;
            $value = $provider->getStat($name, $array);
            $this->cacheProvider->save($cacheName, [
                'value'     => $value,
                'timestamp' => microtime(true),
            ]);
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