<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="devices")
 * @HasLifecycleCallbacks
 **/
class Device
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
	 * @Column(type="string",nullable=true)
	 **/
	protected $photo;

	/**
	 * @ManyToMany(targetEntity="DeviceTag")
	 * @JoinTable(name="devices_tags",
	 *      joinColumns={@JoinColumn(name="device_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 *      )
	 **/
	private $tags;

	/**
	 * @ManyToMany(targetEntity="File")
	 * @JoinColumn(name="file_id", referencedColumnName="id")
	 **/
	private $deviceFiles;

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
	 * @Column(name="updated_at",type="datetime")
	 **/
	protected $updatedAt;

	/**
	 * @ManyToOne(targetEntity="DeviceState")
	 * @JoinColumn(name="state_id", referencedColumnName="id",nullable=false)
	 **/
	protected $state;

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

	public function __construct()
	{
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
	}

	public function getId()
	{
		return $this->id;
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
		return '/uploaded/device/photo/' . $this->photo;
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

	public function setDeviceFiles($deviceFiles)
	{
		$this->deviceFiles = $deviceFiles;
		return $this;
	}

	public function getDeviceFiles()
	{
		return $this->deviceFiles;
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

	public function setcreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
		return $this;
	}

	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	public function __toString()
	{
		return $this->getName().' ('.$this->getSerialNumber().')';
	}

	/**
	 * @PreUpdate
	 */
	public function postUpdate()
	{
		$this->setUpdatedAt(new \DateTime());
	}
}