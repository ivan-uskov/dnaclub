<?php

namespace AppBundle\Entity;
use Symfony\Component\Security\Core\Role\RoleInterface;
use \AppBundle\Entity\User;

class Role implements RoleInterface
{
    /**
     * @var integer $roleId
     */
    private $roleId;

    /**
     * @var string $name
     */
    private $name;

    private $user_id;

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getRole()
    {
        return $this->getName();
    }

    /**
     * Get roleId
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set userId
     *
     * @param User $userId
     *
     * @return Role
     */
    public function setUserId(User $userId = null)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return User
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
