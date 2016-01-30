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
 * @Table(name="device_specimen_logs")
 * @HasLifecycleCallbacks
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class DeviceSpecimenLog
{

    /**
     * @Id
     * @Column(type="integer")
     **/
    protected $id;

    /**
     * @ManyToOne(targetEntity="Device")
     * @JoinColumn(name="device_id", referencedColumnName="id",nullable=false)
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

    public function setId($id)
    {
        return $this->id=$id;
    }

    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

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

    public function __toString()
    {
        return $this->getSerialNumber();
    }
}