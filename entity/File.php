<?php

namespace Entity;

/**
 * @Entity()
 * @Table(name="files")
 **/
class File{

	/** 
	 * @Id
	 * @Column(type="integer") 
	 * @GeneratedValue
	 **/
	protected $id;

    /** 
     * @Column(name="file_name",type="string") 
     **/
    protected $fileName;

    /** 
     * @Column(type="string",nullable=true) 
     **/
    protected $description;

    /** 
     * @Column(type="bigint") 
     **/
    protected $size;

    /** 
     * @Column(name="mime_type",type="string") 
     **/
    protected $mimeType;

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