<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Werkint\Bundle\StatsBundle\Model\NoneDoctrineCacheableStatsAwareInterface as NoneDoctrine;


/**
 * TODO: write "Normalizer" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Normalizer
{
    protected $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        EntityManagerInterface $manager
    ) {
        $this->manager = $manager;
    }


    /**
     * @param object $object
     * @return mixed
     */
    public function getMetaData($object)
    {
        if ($object instanceof NoneDoctrine) {
            $metadata = [
                'class' => get_class($object),
            ];
        } else {
            $meta = $this->manager->getClassMetadata(get_class($object));

            $metadata = [
                'class' => $meta->getName(),
            ];
        }

        return $metadata;
    }
}