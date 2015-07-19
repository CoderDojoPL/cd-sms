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
use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Core\RequestProvider;
use Arbor\Exception\ResourcesNotRegisteredException;
use Arbor\Core\Enviorment;

/**
 * Container for all execute resources e.g.: services, providers
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class ExecuteResources{
	
	/**
	 * Global config.
	 *
	 * @var \Arbor\Contener\GlobalConfig $globalConfig
	 */
	private $globalConfig;

	/**
	 * Registered services.
	 *
	 * @var array $services
	 */
	private $services=array();

	/**
	 * Registered snippets.
	 *
	 * @var array $snippets
	 */
	private $snippets=array();

	/**
	 * Registered presenter.
	 *
	 * @var \Arbor\Core\Presenter $presenter
	 */
	private $presenter;

	/**
	 * Registered request.
	 *
	 * @var \Arbor\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * Registered response.
	 *
	 * @var \Arbor\Provider\Response $response
	 */
	private $response;

	/**
	 * Registered http url.
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * Registered enviorment.
	 *
	 * @var \Arbor\Core\Enviorment $enviorment
	 */
	private $enviorment;

	/**
	 * Register global config.
	 *
	 * @param \Arbor\Contener\GlobalConfig $config
	 * @since 0.1.0
	 */
	public function registerGlobalConfig(GlobalConfig $config){
		$this->globalConfig=$config;
	}

	/**
	 * Get global config.
	 *
	 * @return \Arbor\Contener\GlobalConfig
	 * @since 0.1.0
	 */
	public function getGlobalConfig(){
		return $this->globalConfig;
	}

	/**
	 * Register service.
	 *
	 * @param string $name service name
	 * @param object $object serivce object
	 * @since 0.1.0
	 */
	public function registerService($name,$object){
		$this->services[$name]=$object;
	}

	/**
	 * Register snippet.
	 *
	 * @param string $name snippet name
	 * @param object $object snippet object
	 * @since 0.1.0
	 */
	public function registerSnippet($name,$object){
		$this->snippets[$name]=$object;
	}

	/**
	 * Register presenter.
	 *
	 * @param \Arbor\Core\Presenter $presenter
	 * @since 0.1.0
	 */
	public function registerPresenter(Presenter $presenter){
		$this->presenter=$presenter;
	}

	/**
	 * Get presenter.
	 *
	 * @return \Arbor\Core\Presenter
	 * @throws \Arbor\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getPresenter(){
		if($this->presenter)
			return $this->presenter;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Register enviorment.
	 *
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @since 0.1.0
	 */
	public function registerEnviorment(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}

	/**
	 * Get enviorment
	 *
	 * @return \Arbor\Core\Enviorment
	 * @since 0.1.0
	 */
	public function getEnviorment(){
		return $this->enviorment;
	}

	/**
	 * Register request
	 *
	 * @param \Arbor\Core\RequestProvider $request
	 * @since 0.1.0
	 */
	public function registerRequest(RequestProvider $request){
		$this->request=$request;
	}

	/**
	 * Get request.
	 *
	 * @return \Arbor\Core\RequestProvider
	 * @throws \Arbor\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getRequest(){
		if($this->request)
			return $this->request;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Register response.
	 *
	 * @param \Arbor\Provider\Response $response
	 * @since 0.1.0
	 */
	public function registerResponse(Response $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return \Arbor\Provider\Response
	 * @throws \Arbor\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getResponse(){
		if($this->response)
			return $this->response;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Get all registered services.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getServices(){
		return $this->services;
	}

	/**
	 * Get all registered snippets.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getSnippets(){
		return $this->snippets;
	}

	/**
	 * Register url.
	 *
	 * @param string $url
	 * @since 0.1.0
	 */
	public function registerUrl($url){
		$this->url=$url;
	}

	/**
	 * Get registered url.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getUrl(){
		return $this->url;
	}

	/**
	 * Set debug flag
	 *
	 * @param boolean $debug
	 * @since 0.1.0
	 */
	public function setDebug($debug){
		$this->debug=$debug;
	}

	/**
	 * Check is debug mode enabled.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isDebug(){
		return $this->debug;
	}

	/**
	 * Set silent flag
	 *
	 * @param boolean $silent
	 * @since 0.1.0
	 */
	public function setSilent($silent){
		$this->silent=$silent;
	}

	/**
	 * Check is silent mode enabled.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSilent(){
		return $this->silent;
	}

}