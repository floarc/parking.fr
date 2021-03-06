<?php
// src/Floarc/User/Entity/Group.php

namespace Floarc\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="fos_group", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_4B019DDB5E237E06", columns={"name"})})
 * @ORM\Entity(repositoryClass="Floarc\UserBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    
    public function __construct()
    {
    	parent::__construct();
    	// your own logic
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
