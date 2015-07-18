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
 * @Table(name="order_states")
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 **/
class OrderState{

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

	public function getId(){
		return $this->id;
	}

    public function setName($name){
        $this->name=$name;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function __toString(){
    	return $this->getName();
    }

}