<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Werkint\Bundle\StatsBundle\Service\Provider\StatsProviderInterface;

/**
 * ProviderRow.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ProviderRow
{
    protected $provider;
    protected $name;
    protected $cached;
    protected $realtime;

    /**
     * @param StatsProviderInterface $provider
     * @param string                 $name
     * @param bool                   $realtime
     */
    public function __construct(
        StatsProviderInterface $provider,
        $name,
        $realtime
    ) {
        $this->provider = $provider;
        $this->name = $name;
        $this->realtime = $realtime;

        $this->cached = false;
    }

    // -- Methods ---------------------------------------

    /**
     * @param mixed $cached
     * @return $this
     */
    public function setCached($cached)
    {
        $this->cached = $cached;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRealtime()
    {
        return $this->realtime;
    }

    /**
     * @return mixed
     */
    public function getCached()
    {
        return $this->cached;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCacheName()
    {
        $cacheName = $this->getProvider()->getCacheName($this);
        return $cacheName ? $cacheName : $this->name;
    }

    /**
     * @return StatsProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}