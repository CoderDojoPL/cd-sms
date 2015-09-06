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
 * @Table(name="device_types")
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class DeviceType
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
     * @Column(name="symbol_prefix", type="string")
     **/
    protected $symbolPrefix;

    /**
     * @Column(type="integer",options={"default"=0})
     **/
    protected $current;

    public function __construct($id)
    {
        $this->id = $id;
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

    /**
     * @return mixed
     */
    public function getSymbolPrefix()
    {
        return $this->symbolPrefix;
    }

    /**
     * @param mixed $symbolPrefix
     */
    public function setSymbolPrefix($symbolPrefix)
    {
        $this->symbolPrefix = $symbolPrefix;
    }

    /**
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param mixed $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    public function __toString()
    {
        return $this->getName();
    }

}