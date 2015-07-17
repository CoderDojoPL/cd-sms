<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-11
 * Time: 18:52
 */

namespace Entity;

/**
 * @Entity()
 * @Table(name="device_states")
 **/
class DeviceState
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

    public function __construct($id){
        $this->id=$id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

}