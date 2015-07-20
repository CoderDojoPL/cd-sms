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

use Arbor\Core\ExecuteResources;

/**
 * Manager for events.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class EventManager{
	
	/**
	 * Enviorment.
	 *
	 * @var \Arbor\Core\ExecuteResources $resources
	 */
	private $resources;

	/**
	 * Enviorment.
	 *
	 * @var array $events
	 */
	private $events=array();

	/**
	 * Cache classes.
	 *
	 * @var array $cacheClasses
	 */
	private $cacheClasses=array();

	/**
	 * Constructor.
	 *
	 * @param \Arbor\Core\ExecuteResources $resources
	 * @since 0.1.0
	 */
	public function __construct(ExecuteResources $resources){
		$this->resources=$resources;
	}

	/**
	 * Register event.
	 *
	 * @param string $event event name
	 * @param array $config event config
	 * @since 0.1.0
	 */
	public function register($event,$config){
		$this->events+=array($event=>array());
		$this->events[$event][]=$config;
	}

	/**
	 * Execute event.
	 *
	 * @param string $event event name
	 * @param object $infoClass contener with event info eg.: \Arbor\Event\ExecutePresenterEvent
	 * @since 0.1.0
	 */
	public function fire($event,$infoClass=null){
		if(isset($this->events[$event])){
			foreach($this->events[$event] as $bind=>$config){
				$className=$config['class'];
				if(!isset($this->cacheClasses[$className]))
					$this->cacheClasses[$className]=new $className($this->resources);
				call_user_func_array(array($this->cacheClasses[$className], $config['method']), array($infoClass,$config['config']));
			}
		}
	}

}