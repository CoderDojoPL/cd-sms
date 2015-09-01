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
 * @Table(name="role_logs")
 * @HasLifecycleCallbacks
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 **/
class RoleLog
{
    /**
     * @Id
     * @Column(type="integer")
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
//    private $functionalities;//FIXME: Dorobic obsluge logow
    /**
     * @Column(name="created_at",type="datetime")
     **/
    protected $createdAt;

    /**
     * @Id
     * @ManyToOne(targetEntity="Log")
     * @JoinColumn(name="log_left_id", referencedColumnName="id",onDelete="CASCADE")
     **/
    protected $logLeft;

    /**
     * @ManyToOne(targetEntity="Log")
     * @JoinColumn(name="log_right_id", referencedColumnName="id",onDelete="CASCADE")
     **/
    protected $logRight;

    /**
     * @Column(name="removed",type="boolean")
     **/
    protected $removed;

    function __construct()
    {
        $this->functionalities = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setRemoved(false);
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
     * @return mixed
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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getLogLeft()
    {
        return $this->logLeft;
    }

    /**
     * @param mixed $logLeft
     */
    public function setLogLeft($logLeft)
    {
        $this->logLeft = $logLeft;
    }

    /**
     * @return mixed
     */
    public function getLogRight()
    {
        return $this->logRight;
    }

    /**
     * @param mixed $logRight
     */
    public function setLogRight($logRight)
    {
        $this->logRight = $logRight;
    }

    /**
     * @return mixed
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * @param mixed $removed
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;
    }


}