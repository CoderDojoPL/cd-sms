<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="orders")
 * @HasLifecycleCallbacks
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
     * @JoinColumn(name="owner_id", referencedColumnName="id",nullable=false)
     **/
    protected $owner;

    /**
     * @ManyToOne(targetEntity="Device")
     * @JoinColumn(name="device_id", referencedColumnName="id",nullable=false)
     **/
    protected $device;

    /**
     * @ManyToOne(targetEntity="OrderState")
     * @JoinColumn(name="state_id", referencedColumnName="id",nullable=false)
     **/
    protected $state;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="performer_id", referencedColumnName="id")
     **/
    private $performer;

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

    public function setDevice($device){
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
        $this->performer=$performer;
        return $this;
    }

    public function getPerformer(){
        return $this->performer;
    }

    public function setcreatedAt($createdAt){
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