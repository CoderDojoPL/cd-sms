<?php

namespace Arbor\Core;

class Exception extends \Exception{
	
	public function __construct($code,$message , $safeMessage=null,$file=null,$line=null){
		$this->code=$code;
		$this->message=$message;
		$this->safeMessage=$safeMessage;
		if($this->file)
			$this->file=$file;
		if($this->line)
			$this->line=$line;
	}

	public function getSafeMessage(){
		if($this->safeMessage)
			return $this->safeMessage;
		else
			return $this->getMessage();
	}
}