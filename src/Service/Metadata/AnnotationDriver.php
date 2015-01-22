<?php
namespace Werkint\Bundle\StatsBundle\Service\Metadata;

use Doctrine\Common\Annotations\CachedReader;
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;
use Werkint\Bundle\StatsBundle\Model\CacheableStats;

/**
 * TODO: write "AnnotationDriver" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class AnnotationDriver implements
    DriverInterface
{
    const ANNOTATION_CLASS =
        'Werkint\\Bundle\\StatsBundle\\Model\\CacheableStats';

    protected $reader;

    /**
     * @param CachedReader $reader
     */
    public function __construct(CachedReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getMethods() as $method) {
            $annotation = $this->reader->getMethodAnnotation(
                $method,
                static::ANNOTATION_CLASS
            );

            if ($annotation instanceof CacheableStats) {
                $propertyMetadata = new MethodMetadata($class->getName(), $method->getName());
                $classMetadata->addMethodMetadata($propertyMetadata);
            }
        }

        return $classMetadata;
    }
} 