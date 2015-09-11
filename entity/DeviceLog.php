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
 * @Table(name="device_logs")
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class DeviceLog
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
	 * @Column(type="string",nullable=true)
	 **/
	protected $photo;

	/**
	 * @ManyToOne(targetEntity="DeviceType")
	 * @JoinColumn(name="type_id", referencedColumnName="id")
	 **/
	private $type;

	/**
	 * @Column(type="string")
	 **/
	protected $dimensions;

	/**
	 * @Column(type="string")
	 **/
	protected $weight;

	/**
	 * @Column(name="serial_number",type="string")
	 **/
	protected $serialNumber;

	/**
	 * @Column(name="created_at",type="datetime")
	 **/
	protected $createdAt;


	/**
	 * @ManyToOne(targetEntity="DeviceState")
	 * @JoinColumn(name="state_id", referencedColumnName="id",nullable=false)
	 **/
	protected $state;

	/**
	 * @Column(name="location_id",type="integer",nullable=false)
	 **/
	protected $location;

	/**
	 * @Column(name="user_id",type="integer",nullable=true)
	 **/
	protected $user;

	/**
	 * @Column(name="warranty_expiration_date",type="datetime",nullable=true)
	 **/
	protected $warrantyExpirationDate;

	/**
	 * @Column(name="price",type="decimal",scale=2,nullable=true)
	 **/
	protected $price;

	/**
	 * @Column(name="note",type="text",nullable=true)
	 **/
	protected $note;

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

	/**
	 * @Column(type="text")
	 **/
	protected $symbol;

	/**
	 * @Column(name="hire_expiration_date",type="datetime",nullable=true)
	 **/
	protected $hireExpirationDate;

	public function __construct(){
        $this->setCreatedAt(new \DateTime());
        $this->setRemoved(false);
    }

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		return $this->id=$id;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setPhoto($photo)
	{
		$this->photo = $photo;
		return $this;
	}

	public function getPhoto()
	{
		$prefix = '';
		if ($this->photo) {
			$prefix = '/uploaded/device/photo/';
		}
		return $prefix . $this->photo;
	}

	public function setTags($tags)
	{
		$this->tags = $tags;
		return $this;
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setDimensions($dimensions)
	{
		$this->dimensions = $dimensions;
		return $this;
	}

	public function getDimensions()
	{
		return $this->dimensions;
	}

	public function setWeight($weight)
	{
		$this->weight = $weight;
		return $this;
	}

	public function getWeight()
	{
		return $this->weight;
	}

	public function setSerialNumber($serialNumber)
	{
		$this->serialNumber = $serialNumber;
		return $this;
	}

	public function getSerialNumber()
	{
		return $this->serialNumber;
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
	    if($location){
            $location=$location->getId();
        }

		$this->location = $location;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
	    if($user){
            $user=$user->getId();
        }

		$this->user = $user;
	}

	/**
	 * @return mixed
	 */
	public function getWarrantyExpirationDate()
	{
		return $this->warrantyExpirationDate;
	}

	/**
	 * @param mixed $warrantyExpirationDate
	 */
	public function setWarrantyExpirationDate($warrantyExpirationDate)
	{
		$this->warrantyExpirationDate = $warrantyExpirationDate;
	}


	/**
	 * @return mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param mixed $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * @return mixed
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * @param mixed $note
	 */
	public function setNote($note)
	{
		$this->note = $note;
	}


	public function __toString()
	{
		return $this->getName() . ' (' . $this->getSerialNumber() . ')';
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
	public function getSymbol()
	{
		return $this->symbol;
	}

	/**
	 * @param mixed $symbol
	 */
	public function setSymbol($symbol)
	{
		$this->symbol = $symbol;
	}

	/**
	 * @return mixed
	 */
	public function getHireExpirationDate()
	{
		return $this->hireExpirationDate;
	}

	/**
	 * @param mixed $hireExpirationDate
	 */
	public function setHireExpirationDate($hireExpirationDate)
	{
		$this->hireExpirationDate = $hireExpirationDate;
	}

}