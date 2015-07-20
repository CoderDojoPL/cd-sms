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

	/**
	 * Route.
	 *
	 * @var string $route
	 */	
	private $route;

	/**
	 * Presenter name.
	 *
	 * @var string $presenter
	 */	
	private $presenter;

	/**
	 * Controller name.
	 *
	 * @var string $controller
	 */	
	private $controller;

	/**
	 * Method name.
	 *
	 * @var string $method
	 */	
	private $method;

	/**
	 * Extra data.
	 *
	 * @var array $extra
	 */	
	private $extra;

	/**
	 * Class name.
	 *
	 * @var string $class
	 */
	private $class;

	/**
	 * Enviorment.
	 *
	 * @var \Arbor\Core\Enviorment $enviorment
	 */	
	private $enviorment;

	/**
	 * Constructor.
	 *
	 * @param string $controller
	 * @param string $method
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param array $actionConfig
	 * @return array
	 * @since 0.1.0
	 */
	public function __construct($controller , $method ,Enviorment $enviorment , $actionConfig){
		$this->enviorment=$enviorment;
		$this->route=$actionConfig['route'];
		$this->presenter=$actionConfig['presenter'];
		$this->extra=(isset($actionConfig['extra'])?$actionConfig['extra']:array());
		$this->class=$actionConfig['class'];
		$this->controller=$controller;
		$this->method=$method;

	}

	/**
	 * Get route.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getRoute(){
		return $this->route;
	}

	/**
	 * Get class name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getClass(){
		return $this->class;
	}

	/**
	 * Get presenter name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getPresenter(){
		return $this->presenter;
	}

	/**
	 * Get controller name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getController(){
		return $this->controller;
	}

	/**
	 * Get method name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getMethod(){
		return $this->method;
	}

	/**
	 * Get extra data.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getExtra(){
		return $this->extra;
	}

	/**
	 * Check is debug.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isDebug(){
		return $this->enviorment->isDebug();
	}

	/**
	 * Get is silent (for test and command).
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSilent(){
		return $this->enviorment->isSilent();
	}

}