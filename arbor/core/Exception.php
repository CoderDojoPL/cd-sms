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
 * Main class for project exception.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
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