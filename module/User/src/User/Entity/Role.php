<?php

namespace User\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_roles")
 */
class Role implements HierarchicalRoleInterface
{
    #region Fields
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $id;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role")
     */
    protected $parent;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, name="is_default")
     */
    protected $isDefault;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role", mappedBy="userRoles")
     */
    protected $users;
    #endregion

    #region Getters / Setters
    /**
     * @param boolean $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = (string)$id;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Role $parent
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->id;
    }
    #endregion

    public function __toString()
    {
        return $this->getId();
    }
}
