<?php

/**
 * ArborPHP: Freamwork PHP (http://arborphp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://arborphp.com ArborPHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Arbor\Core;

/**
 * Autoloader project classes
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Autoloader{
	
	/**
	 * Constructor.
	 * @since 0.1.0
	 */
	public function __construct(){
		spl_autoload_register(array($this,'execute'));
   
	}

	/**
	 * Search class file and included file.
	 *
	 * @param string $class class with namespace name
	 * @since 0.1.0
	 */
	public function execute($class){
		$path=$this->getPath($class);
		if(file_exists($path)){//TODO stworzyć listę autoloaderów?
			require_once $path;
		}
	}

	/**
	 * Get path to class file
	 *
	 * @param string $class class with namespace name
	 * @since 0.1.0
	 */
	private function getPath($class){
		$parts=explode('\\' , $class);

		$path=__DIR__.'/../../';

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