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

use Arbor\Contener\GlobalConfig;
use Arbor\Core\Enviorment;
use Arbor\Contener\RequestConfig;
use Arbor\Contener\CommandConfig;
use Arbor\Exception\RouteNotFoundException;
use Arbor\Exception\CommandNotFoundException;
use Arbor\Core\HttpDispatcher;
use Arbor\Core\HttpTestDispatcher;
use Arbor\Core\CommandDispatcher;

/**
 * Factory for dispatcher. 
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Router{
	

	/**
	 * Create http dispatcher.
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \Arbor\Core\HttpDispatcher
	 * @throws \Arbor\Exception\RouteNotFoundException
	 * @since 0.1.0
	 */
	public function createHttpDispatcher(Enviorment $enviorment,GlobalConfig $config,$url){

		$requestConfig=$this->findRecources($enviorment,$config,$url);
		if(!$requestConfig){
			$requestConfig=$this->findAction($enviorment,$config,$url);
		}


		if($requestConfig){
			return new HttpDispatcher($requestConfig);
		}

		throw new RouteNotFoundException($url);

	}

	/**
	 * Create http test dispatcher.
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \Arbor\Core\HttpTestDispatcher
	 * @throws \Arbor\Exception\RouteNotFoundException
	 * @since 0.1.0
	 */
	public function createHttpTestDispatcher(Enviorment $enviorment,GlobalConfig $config,$url){

		$requestConfig=$this->findRecources($enviorment,$config,$url);
		if(!$requestConfig){
			$requestConfig=$this->findAction($enviorment,$config,$url);
		}


		if($requestConfig){
			return new HttpTestDispatcher($requestConfig);
		}

		throw new RouteNotFoundException($url);

	}

	/**
	 * Create command dispatcher.
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $commandName
	 * @param array $arguments
	 * @return \Arbor\Core\CommandDispatcher
	 * @throws \Arbor\Exception\CommandNotFoundException
	 * @since 0.1.0
	 */
	public function createCommandDispatcher(Enviorment $enviorment,GlobalConfig $config,$commandName,$arguments){

		$commandConfig=$this->findCommand($config,$commandName);


		if($commandConfig){
			return new CommandDispatcher($commandConfig,$arguments);
		}

		throw new CommandNotFoundException($commandName);

	}

	/**
	 * Find resource routing
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \Arbor\Contener\RequestConfig
	 * @since 0.1.0
	 */
	private function findRecources(Enviorment $enviorment,GlobalConfig $config,$url){
		foreach($config->getResources() as $resource){
			if(preg_match('/^'.$resource['pattern'].'$/',$url)){
				return new RequestConfig('Resource','download',$enviorment,
					array(
						'route'=>$url
						,'presenter'=>array('class' => 'Arbor\\Presenter\\File')
						,'class'=>'Arbor\\Controller\\Resource'
						,'extra'=>array(array('expire'=>$resource['expire'],'pattern'=>$resource['pattern'],'path'=>$resource['path']))
						));
			}
		}

	}

	/**
	 * Find action routing
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \Arbor\Contener\RequestConfig
	 * @since 0.1.0
	 */
	private function findAction(Enviorment $enviorment,GlobalConfig $config,$url){
		foreach($config->getMethods() as $methodName=>$method){
			if(preg_match('/^'.$method['route']['pattern'].'$/',$url)){
				$actionPart=explode(':',$methodName);
				$method['class']='Controller\\'.$actionPart[0];
				return new RequestConfig($actionPart[0],$actionPart[1],$enviorment,$method);
			}
		}

	}

	/**
	 * Find resource routing
	 *
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @param string $commandRequestName
	 * @return \Arbor\Contener\CommandConfig
	 * @since 0.1.0
	 */
	private function findCommand(GlobalConfig $config,$commandRequestName){

		foreach($config->getCommands() as $commandName=>$command){
			if(preg_match('/^'.$commandRequestName.'$/',$commandName)){
				$method['class']=$command['class'];
				return new CommandConfig($commandName,$command['class'],$command['method']);
			}
		}
	}

}