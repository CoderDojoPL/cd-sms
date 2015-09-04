<?php
/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity()
 * @Table(name="roles")
 * @HasLifecycleCallbacks
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 **/
class Role
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @Column(type="string")
     **/
    protected $name;

    /**
     * @ManyToMany(targetEntity="Functionality")
     * @JoinTable(name="roles_functionalities",
     *      joinColumns={@JoinColumn(name="role_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="functionality_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     **/
    private $functionalities;

    function __construct()
    {
        $this->functionalities = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFunctionalities()
    {
        return $this->functionalities;
    }

    /**
     * @param mixed $functionalities
     */
    public function setFunctionalities($functionalities)
    {
        $this->functionalities = $functionalities;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->getName();
    }


}