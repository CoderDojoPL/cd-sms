<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="users")
 **/
class User{

	/** 
	 * @Id
	 * @Column(type="integer") 
	 * @GeneratedValue
	 **/
	protected $id;

    /** 
     * @Column(type="string") 
     **/
    protected $email;

    /** 
     * @Column(name="first_name",type="string") 
     **/
    protected $firstName;

    /** 
     * @Column(name="last_name",type="string") 
     **/
    protected $lastName;

    /**
     * @ManyToOne(targetEntity="Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     **/
    protected $location;

	public function getId(){
		return $this->id;
	}

    public function setEmail($email){
        $this->email=$email;
        return $this;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setFirstName($firstName){
        $this->firstName=$firstName;
        return $this;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setLastName($lastName){
        $this->lastName=$lastName;
        return $this;
    }

    public function getLastName(){
        return $this->lastName;
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
        return $this;

    }

    public function __toString(){
        return $this->getEmail();
    }


}