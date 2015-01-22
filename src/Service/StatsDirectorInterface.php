<?php
namespace Werkint\Bundle\StatsBundle\Service;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * TODO: write "StatsDirectorInterface" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface StatsDirectorInterface
{
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
    );
}