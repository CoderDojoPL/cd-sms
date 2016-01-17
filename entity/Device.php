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
 * @Table(name="devices")
 * @HasLifecycleCallbacks
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
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
     *      joinColumns={@JoinColumn(name="device_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     **/
    private $tags;

    /**
     * @ManyToOne(targetEntity="DeviceType")
     * @JoinColumn(name="type_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     **/
    private $type;

    /**
     * @Column(name="created_at",type="datetime")
     **/
    protected $createdAt;

    /**
     * @Column(name="updated_at",type="datetime")
     **/
    protected $updatedAt;

    /**
     * @Column(name="price",type="decimal",scale=2,nullable=true)
     **/
    protected $price;

    /**
     * @Column(name="note",type="text",nullable=true)
     **/
    protected $note;

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
        return $this->getName();
    }

    /**
     * @PreUpdate
     */
    public function postUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}