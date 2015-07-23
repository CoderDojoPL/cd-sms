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
 * @Table(name="order_logs")
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class OrderLog{

	/** 
	 * @Id
	 * @Column(type="integer") 
	 **/
	protected $id;

	/** 
	 * @Column(name="owner_id",type="integer",nullable=false)
	 **/
	protected $owner;

	/** 
	 * @Column(name="device_id",type="integer",nullable=false)
	 **/
	protected $device;

	/**
	 * @ManyToOne(targetEntity="OrderState")
	 * @JoinColumn(name="state_id", referencedColumnName="id",nullable=false)
	 **/
	protected $state;

	/** 
	 * @Column(name="performer_id",type="integer",nullable=true)
	 **/
	private $performer;

	/** 
	 * @Column(name="fetched_at",type="datetime",nullable=true)
	 **/
	protected $fetchedAt;

	/** 
	 * @Column(name="closed_at",type="datetime",nullable=true)
	 **/
	protected $closedAt;

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

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		return $this->id=$id;
	}

	public function setOwner($owner){
        if($owner){
            $owner=$owner->getId();
        }

		$this->owner=$owner;
		return $this;
	}

	public function getOwner(){
		return $this->owner;
	}

	public function setDevice($device){
        if($device){
            $device=$device->getId();
        }

		$this->device=$device;
		return $this;
	}

	public function getDevice(){
		return $this->device;
	}

	public function setState($state){
		$this->state=$state;
		return $this;
	}

	public function getState(){
		return $this->state;
	}

	public function setPerformer($performer){
        if($performer){
            $performer=$performer->getId();
        }

		$this->performer=$performer;
		return $this;
	}

	public function getPerformer(){
		return $this->performer;
	}

	public function setFetchedAt($fetchedAt){
		$this->fetchedAt=$fetchedAt;
		return $this;
	}

	public function getFetchedAt(){
		return $this->fetchedAt;
	}

	public function setClosedAt($closedAt){
		$this->closedAt=$closedAt;
		return $this;
	}

	public function getClosedAt(){
		return $this->closedAt;
	}

	public function setCreatedAt($createdAt){
		$this->createdAt=$createdAt;
		return $this;
	}

	public function getCreatedAt(){
		return $this->createdAt;
	}


	public function __toString(){
		return $this->getName();
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