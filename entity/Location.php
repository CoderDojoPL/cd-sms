<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-11
 * Time: 15:59
 */

namespace Entity;

/**
 * @Entity()
 * @Table(name="locations")
 * @HasLifecycleCallbacks
 **/
class Location
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


    public function __toString()
    {
        return $this->getName();
    }


}