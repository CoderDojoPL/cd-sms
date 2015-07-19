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
 * Enviorment settings.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Enviorment{

	/**
	 * Debug flag.
	 *
	 * @var boolean $debug
	 */
	private $debug;

	/**
	 * Silent flag.
	 *
	 * @var boolean $silent
	 */
	private $silent;

	/**
	 * Enviorment name.
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * Constructor.
	 *
	 * @param boolean $debug
	 * @param boolean $silent
	 * @param string $name
	 * @since 0.1.0
	 */
	public function __construct($debug,$silent,$name){
		$this->debug=$debug;
		$this->silent=$silent;
		$this->name=$name;
	}

	/**
	 * Check is debug mode.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isDebug(){
		return $this->debug;
	}	

	/**
	 * Check is silent mode.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSilent(){
		return $this->silent;
	}

	/**
	 * Get enviorment name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getName(){
		return $this->name;
	}
}