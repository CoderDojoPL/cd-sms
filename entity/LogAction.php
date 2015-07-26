<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @Entity()
 * @Table(name="log_actions")
 */
class LogAction
{
    /** @Id @Column(type="integer") 
     */
    private $id;

    /** @Column(type="string") */
    private $name;

    public function __construct($id){
        $this->id=$id;
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

    /**
     * Set name
     *
     * @param text $name
     * @return names
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return text 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Entity to string.
     *
     * @return string
     */    
    public function __toString()
    {
        return $this->getName();
    }

}