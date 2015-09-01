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

/**
 * @Entity()
 * @Table(name="user_logs")
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class UserLog
{

    /**
     * @Id
     * @Column(type="integer")
     **/
    protected $id;

    /**
     * @Column(type="string")
     **/
    protected $email;

    /**
     * @Column(name="first_name",type="string")
     **/
    protected $firstName;

    /**
     * @Column(name="last_name",type="string")
     **/
    protected $lastName;

    /**
     * @Column(name="location_id",type="integer")
     **/
    protected $location;

    /**
     * @Column(name="role_id",type="integer",nullable=true)
     **/
    protected $role;
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

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setRemoved(false);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        if ($location) {
            $location = $location->getId();
        }

        $this->location = $location;
        return $this;

    }

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setLogLeft($logLeft)
    {
        $this->logLeft = $logLeft;
        return $this;
    }

    public function getLogLeft()
    {
        return $this->logLeft;
    }

    public function setLogRight($logRight)
    {
        $this->logRight = $logRight;
        return $this;
    }

    public function getLogRight()
    {
        return $this->logRight;
    }

    public function setRemoved($removed)
    {
        $this->removed = $removed;
        return $this;
    }

    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        if ($role) {
            $role = $role->getId();
        }
        $this->role = $role;
    }

}