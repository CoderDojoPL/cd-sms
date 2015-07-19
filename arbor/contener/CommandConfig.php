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

namespace Arbor\Contener;

use Arbor\Contener\GlobalConfig;

/**
 * Contener with command config.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class CommandConfig{
	
	private $command;
	private $method;
	private $class;

	/**
	 * @param string $command command name
	 * @param string $class class name
	 * @param string $method method name
	 * @since 0.1.0
	 */
	public function __construct($command , $class,$method){

		$this->class=$class;
		$this->command=$command;
		$this->method=$method;

	}

	/**
	 * @return string command name
	 * @since 0.1.0
	 */
	public function getCommand(){
		return $this->command;
	}

	/**
	 * @return string method name
	 * @since 0.1.0
	 */
	public function getMethod(){
		return $this->method;
	}

	/**
	 * @return string class name
	 * @since 0.1.0
	 */
	public function getClass(){
		return $this->class;
	}
}