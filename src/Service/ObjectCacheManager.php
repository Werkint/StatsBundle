<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Управляет кешем полей объектов
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ObjectCacheManager
{
    protected $manager;
    protected $director;
    protected $normalizer;

    /**
     * @param EntityManagerInterface $manager
     * @param StatsDirectorInterface $director
     * @param Normalizer             $normalizer
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
     * @return string
     */
    public function getObjectStatsName($object, $name)
    {
        $metadata = $this->normalizer->getMetaData($object);

        return $metadata['class'] . '.' . $name;
    }

    /**
     * @param object $object
     * @param string $name
     * @param bool   $forceUpdate
     * @return mixed
     */
    public function getStatsForObject($object, $name, $forceUpdate = false)
    {
        $metadata = $this->normalizer->getMetaData($object);
        $class = $this->getObjectStatsName($object, $name);

        return $this->director->getStat($class, [
            'class'    => $class,
            'property' => $name,
            'object'   => $object,
            'metadata' => $metadata,
        ], null, $forceUpdate);
    }

    /**
     * @param object $object
     * @param string $name
     * @return mixed
     */
    public function updateObjectStats($object, $name)
    {
        return $this->getStatsForObject($object, $name, true);
    }
}