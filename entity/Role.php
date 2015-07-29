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
 * @Table(name="roles")
 * @HasLifecycleCallbacks
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class Role
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
     * @ManyToMany(targetEntity="Functionality")
     * @JoinTable(name="roles_functionalities",
     *      joinColumns={@JoinColumn(name="role_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="functionality_id", referencedColumnName="id",onDelete="CASCADE")}
     *      )
     **/
    private $functionalities;

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

    /**
     * @return mixed
     */
    public function getFunctionalities()
    {
        return $this->functionalities;
    }

    /**
     * @param mixed $functionalities
     */
    public function setFunctionalities($functionalities)
    {
        $this->functionalities = $functionalities;
        return $this;
    }


}