<?php
namespace Werkint\Bundle\StatsBundle\Service\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Werkint\Bundle\StatsBundle\Service\ProviderRow;

/**
 * StatsVoter.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatsVoter implements
    VoterInterface
{
    protected $roleHierarchy;
    protected $roles;

    /**
     * @param RoleHierarchyInterface $roleHierarchy
     * @param array                  $roles
     */
    public function __construct(
        RoleHierarchyInterface $roleHierarchy,
        array $roles
    ) {
        $this->roleHierarchy = $roleHierarchy;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(
        TokenInterface $token,
        $object,
        array $attributes
    ) {
        if ($object instanceof ProviderRow && count($this->roles)) {
            $roles = $this->unpackRoles($token);
            if (isset($this->roles[$object->getName()])) {
                if (in_array($this->roles[$object->getName()], $roles)) {
                    return static::ACCESS_GRANTED;
                }
                return static::ACCESS_DENIED;
            }
        }

        return static::ACCESS_GRANTED;
    }

    // -- Helpers ---------------------------------------

    /**
     * @param TokenInterface $token
     * @return array
     */
    protected function unpackRoles(
        TokenInterface $token
    ) {
        return array_map(function (RoleInterface $role) {
            return $role->getRole();
        }, $this->roleHierarchy->getReachableRoles($token->getRoles()));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return true;
    }
}