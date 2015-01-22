<?php
namespace Werkint\Bundle\StatsBundle\Model;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Аннотация для свойств, которые мы кешируем
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @Annotation
 * @Target("METHOD")
 */
class CacheableStats
{
} 