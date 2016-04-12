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
 * @Table(name="device_specimens")
 * @HasLifecycleCallbacks
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class DeviceSpecimen
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @ManyToOne(targetEntity="Device")
     * @JoinColumn(name="device_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     **/
    protected $device;

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
     * @ManyToOne(targetEntity="Location")
     * @JoinColumn(name="location_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     **/
    protected $location;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")
     **/
    protected $user;

    /**
     * @Column(name="warranty_expiration_date",type="datetime",nullable=true)
     **/
    protected $warrantyExpirationDate;

    /**
     * @Column(name="purchase_date",type="datetime",nullable=true)
     **/
    protected $purchaseDate;

    /**
     * @Column(type="text")
     **/
    protected $symbol;

    /**
     * @Column(name="hire_expiration_date",type="datetime",nullable=true)
     **/
    protected $hireExpirationDate;

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
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

	/**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
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

    public function setCreatedAt($createdAt)
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
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * @param mixed $PurchaseDate
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;
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

    public function __toString()
    {
        return $this->getDevice()->getName().' ('.$this->getSerialNumber().')';
    }

    /**
     * @PreUpdate
     */
    public function postUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}