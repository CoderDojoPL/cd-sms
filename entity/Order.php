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
 * @Table(name="orders")
 * @HasLifecycleCallbacks
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class Order{

	/** 
	 * @Id
	 * @Column(type="integer") 
	 * @GeneratedValue
	 **/
	protected $id;

	/**
	 * @ManyToOne(targetEntity="User")
	 * @JoinColumn(name="owner_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
	 **/
	protected $owner;

	/**
	 * @ManyToOne(targetEntity="DeviceSpecimen")
	 * @JoinColumn(name="device_specimen_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
	 **/
	protected $deviceSpecimen;

	/**
	 * @ManyToOne(targetEntity="OrderState")
	 * @JoinColumn(name="state_id", referencedColumnName="id",nullable=false)
	 **/
	protected $state;

	/**
	 * @ManyToOne(targetEntity="User")
	 * @JoinColumn(name="performer_id", referencedColumnName="id",onDelete="CASCADE")
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
	 * @Column(name="updated_at",type="datetime")
	 **/
	protected $updatedAt;

	public function __construct(){
		$this->tags=new \Doctrine\Common\Collections\ArrayCollection();
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
	}

	public function getId(){
		return $this->id;
	}

	public function setOwner($owner){
		$this->owner=$owner;
		return $this;
	}

	public function getOwner(){
		return $this->owner;
	}

	public function setDeviceSpecimen($deviceSpecimen){
		$this->deviceSpecimen=$deviceSpecimen;
		return $this;
	}

	public function getDeviceSpecimen(){
		return $this->deviceSpecimen;
	}

	public function setState($state){
		$this->state=$state;
		return $this;
	}

	public function getState(){
		return $this->state;
	}

	public function setPerformer($performer){
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

	public function setUpdatedAt($updatedAt){
		$this->updatedAt=$updatedAt;
		return $this;
	}

	public function getUpdatedAt(){
		return $this->updatedAt;
	}

	public function __toString(){
		return $this->getName();
	}

	/**
	 * @PreUpdate
	 */
	public function postUpdate(){
		$this->setUpdatedAt(new \DateTime());        
	}

}