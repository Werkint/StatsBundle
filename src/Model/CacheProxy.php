<?php
namespace Werkint\Bundle\StatsBundle\Model;

use Doctrine\ORM\EntityManagerInterface;
use Werkint\Bundle\StatsBundle\Service\StatsDirectorInterface;

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
     * @param EntityManagerInterface $manager
     * @param StatsDirectorInterface $director
     */
    public function __construct(
        EntityManagerInterface $manager,
        StatsDirectorInterface $director
    ) {
        $this->manager = $manager;
        $this->director = $director;
    }

    /**
     * @param object $object
     * @param string $name
     * @return mixed
     */
    public function getStatsForObject($object, $name)
    {
        $metadata = $this->manager->getClassMetadata(get_class($object));
        $class = $metadata->getName();
        return $this->director->getStat($class . '.' . $name, [
            'class'    => $class,
            'property' => $name,
            'object'   => $object,
            'metadata' => $metadata,
        ]);
    }
}