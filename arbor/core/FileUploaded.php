<?php

namespace Arbor\Core;

use Arbor\Exception\FileFailSavedException;

class FileUploaded{
	private $name;
	private $tmpName;
	private $error;
	private $size;
	private $type;
	private $extension;

	public function __construct($data){
		$this->name=$data['name'];
		$this->tmpName=$data['tmp_name'];
		$this->error=$data['error'];
		$this->size=$data['size'];
		$this->type=$data['type'];

		if($this->tmpName){
			$fileInfo = new \finfo(FILEINFO_MIME);
			$this->extension=$fileInfo->buffer(file_get_contents($this->tmpName));			
		}
 
	}

	/**
	 * Save uploaded file to destiny dir
	 * @arg path - path dir to save file
	 * @arg name - file name if is empty then set name on origin name
	 * @exception FileFailSavedException
	 * @since 0.12.0
	 */
	public function save($path,$name=null){

		if(!file_exists($path)){
			mkdir($path,0777,true);
		}

		$destPath=rtrim($path, '/');
		if($name){
			$destPath.='/'.$name;
		}
		else{
			$destPath.='/'.$this->name;
		}

		if(!move_uploaded_file($this->tmpName, $destPath)){
			//detect error reason
			$reason='Unknown';
			if(!file_exists($path)){
				$reason='Path "'.$path.'" not exists.';
			}
			else if(!is_writeable($path))
				$reason='Path "'.$path.'" required permission to write.';

			throw new FileFailSavedException($reason);
		}
	}

	/*
	 * Return true if is has error on uploaded, else return false
	 * @since 0.12.0
	 */
	public function isError(){
		return $this->error!='0';
	}

	/*
	 * Return error message if fail uploaded
	 * @since 0.12.0
	 */
	public function getError(){
		return $this->error;
	}

	/*
	 * Get origin file name
	 * @since 0.12.0
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * Get file size in bytes
	 * @since 0.17.0
	 */
	public function getSize(){
		return $this->size;
	}

	/**
	 * get file extension (mime/type)
	 * @return string
	 * @since 0.17.0
	 */
	public function getExtension(){
		return $this->extension;
	}
}

