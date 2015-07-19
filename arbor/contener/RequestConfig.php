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
use Arbor\Core\Enviorment;

/**
 * Contener with request config.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class RequestConfig{
	
	private $route;
	private $presenter;
	private $controller;
	private $method;
	private $extra;
	private $class;
	private $enviorment;

	/**
	 * @param string $controller
	 * @param string $method
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param array $actionConfig
	 * @return array
	 * @since 0.1.0
	 */
	public function __construct($controller , $method ,$enviorment , $actionConfig){
		$this->enviorment=$enviorment;
		$this->route=$actionConfig['route'];
		$this->presenter=$actionConfig['presenter'];
		$this->extra=(isset($actionConfig['extra'])?$actionConfig['extra']:array());
		$this->class=$actionConfig['class'];
		$this->controller=$controller;
		$this->method=$method;

	}

	/**
	 * @return string
	 * @since 0.1.0
	 */
	public function getRoute(){
		return $this->route;
	}

	/**
	 * @return string
	 * @since 0.1.0
	 */
	public function getClass(){
		return $this->class;
	}

	/**
	 * @return string
	 * @since 0.1.0
	 */
	public function getPresenter(){
		return $this->presenter;
	}

	/**
	 * @return string
	 * @since 0.1.0
	 */
	public function getController(){
		return $this->controller;
	}

	/**
	 * @return string
	 * @since 0.1.0
	 */
	public function getMethod(){
		return $this->method;
	}

	/**
	 * @return array
	 * @since 0.1.0
	 */
	public function getExtra(){
		return $this->extra;
	}

	/**
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isDebug(){
		return $this->enviorment->isDebug();
	}

	/**
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSilent(){
		return $this->enviorment->isSilent();
	}

}