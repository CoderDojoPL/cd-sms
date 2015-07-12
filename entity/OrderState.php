<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="order_states")
 **/
class OrderState{

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