<?php

namespace Arbor\core;


class Autoloader{
	
	public function __construct(){
		spl_autoload_register(array($this,'execute'));
   
	}

	public function execute($class){
		$path=$this->getPath($class);
		if(file_exists($path)){//TODO stworzyć listę autoloaderów?
			require_once $path;
		}
	}

	private function getPath($class){
		$parts=explode('\\' , $class);

		$path="../";

		for($i=0; $i < count($parts);$i++){
			$part=$parts[$i];
			if($i!=count($parts)-1){
				$path.=strtolower($part).'/';
			}
			else{
				$path.=$part.'.php';
			}

		}

		return $path;
	}
}