<?php
namespace Werkint\Bundle\StatsBundle\Model;

use Doctrine\ORM\EntityManagerInterface;
use Werkint\Bundle\StatsBundle\Service\StatsDirectorInterface;
use Werkint\Bundle\StatsBundle\Service\Normalizer;

/**
 * TODO: write "CacheProxy" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class CacheProxy
{
    protected $manager;
    protected $director;


    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * @param EntityManagerInterface $manager
     * @param StatsDirectorInterface $director
     * @param Normalizer $normalizer
     */
    public function __construct(
        EntityManagerInterface $manager,
        StatsDirectorInterface $director,
        Normalizer $normalizer
    ) {
        $this->manager = $manager;
        $this->director = $director;
        $this->normalizer = $normalizer;
    }

    /**
     * @param object $object
     * @param string $name
     * @return mixed
     */
    public function getStatsForObject($object, $name)
    {
        $metadata = $this->normalizer->getMetaData($object);

        $class = $metadata['class'];
        return $this->director->getStat($class . '.' . $name, [
            'class'    => $class,
            'property' => $name,
            'object'   => $object,
            'metadata' => $metadata,
        ]);
    }
}