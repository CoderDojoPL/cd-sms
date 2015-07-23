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
 * @Table(name="location_logs")
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class LocationLog
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
	 * @Column(type="string")
	 **/
	protected $city;
	/**
	 * @Column(type="string")
	 **/
	protected $street;
	/**
	 * @Column(type="string")
	 **/
	protected $number;
	/**
	 * @Column(type="string",nullable=true)
	 **/
	protected $apartment;
	/**
	 * @Column(type="string")
	 **/
	protected $postal;

	/**
	 * @Column(type="string")
	 **/
	protected $phone;

	/**
	 * @Column(type="string")
	 **/
	protected $email;

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
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * @param mixed $street
	 */
	public function setStreet($street)
	{
		$this->street = $street;
	}

	/**
	 * @return mixed
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @param mixed $number
	 */
	public function setNumber($number)
	{
		$this->number = $number;
	}

	/**
	 * @return mixed
	 */
	public function getApartment()
	{
		return $this->apartment;
	}

	/**
	 * @param mixed $apartment
	 */
	public function setApartment($apartment)
	{
		$this->apartment = $apartment;
	}

	/**
	 * @return mixed
	 */
	public function getPostal()
	{
		return $this->postal;
	}

	/**
	 * @param mixed $postal
	 */
	public function setPostal($postal)
	{
		$this->postal = $postal;
	}

	/**
	 * @return mixed
	 */
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

	/**
	 * @return mixed
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param mixed $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}


	public function __toString()
	{
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