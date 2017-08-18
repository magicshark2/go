<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")})
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->roles = array('ROLE_USER','ROLE_ADMIN');
    }

    

    /**
    * Get all Roles added to the User
    * @return array
    */
    public function getRoles()
    {
        $roles = [];
        /** @var ArrayCollection $groups */
        $groups = $this->getGroups();

        foreach ($groups as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        $roles = array_unique($roles);

        # store the roles in the property to be serialized
        $this->roles = $roles;

        return $roles;
    }
}