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
 * @Table(name="device_tag_logs")
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class DeviceTagLog{

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

    public function __construct(){
        $this->setCreatedAt(new \DateTime());
        $this->setRemoved(false);
    }

	public function getId(){
		return $this->id;
	}

    public function setId($id){
        return $this->id=$id;
    }

    public function setName($name){
        $this->name=$name;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function __toString(){
    	return $this->getName();
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

}