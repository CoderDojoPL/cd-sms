<?php

namespace Arbor\Provider;

use Arbor\Core\SessionProvider;
use Arbor\Exception\ValueNotFoundException;
use Arbor\Core\Enviorment;

class Session implements SessionProvider{


	public function __construct(Enviorment $enviorment){
		if(!$enviorment->isSilent())
			session_start();
	}

	public function get($key){
		if(!isset($_SESSION[$key]))
			throw new ValueNotFoundException($key);
		return $_SESSION[$key];
	}

	public function set($key,$value){
		$_SESSION[$key]=$value;
	}

	public function remove($key){
		if(isset($_SESSION[$key])){
			unset($_SESSION[$key]);			
		}
		else{
			throw new ValueNotFoundException($key);
		}

	}

	public function clear(){
		$_SESSION=array();
	}

}