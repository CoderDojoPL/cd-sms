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

namespace Arbor\Provider;

use Arbor\Core\SessionProvider;
use Arbor\Exception\ValueNotFoundException;
use Arbor\Core\Enviorment;

/**
 * Provider for session.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
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