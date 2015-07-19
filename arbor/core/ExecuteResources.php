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
use Arbor\Contener\RequestConfig;
use Arbor\Provider\Response;
use Arbor\Core\RequestProvider;
use Arbor\Exception\ResourcesNotRegisteredException;

/**
 * Container for all execute resources e.g.: services, providers
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class ExecuteResources{
	
	private $globalConfig;
	private $services=array();
	private $snippets=array();
	private $presenter;
	private $requestConfig;
	private $request;
	private $response;
	private $url;
	private $enviorment;

	public function registerGlobalConfig(GlobalConfig $config){
		$this->globalConfig=$config;
	}

	public function getGlobalConfig(){
		return $this->globalConfig;
	}

	public function registerService($name,$object){
		$this->services[$name]=$object;
	}

	public function registerSnippet($name,$object){
		$this->snippets[$name]=$object;
	}

	public function registerPresenter(Presenter $presenter){
		$this->presenter=$presenter;
	}

	public function getPresenter(){
		if($this->presenter)
			return $this->presenter;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function registerEnviorment(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}

	public function getEnviorment(){
		return $this->enviorment;
	}

	public function registerRequest(RequestProvider $request){
		$this->request=$request;
	}

	public function getRequest(){
		if($this->request)
			return $this->request;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function registerResponse(Response $response){
		$this->response=$response;
	}

	public function getResponse(){
		if($this->response)
			return $this->response;
		else
			throw new ResourcesNotRegisteredException();
	}

	public function getServices(){
		return $this->services;
	}

	public function getSnippets(){
		return $this->snippets;
	}

	public function registerUrl($url){
		$this->url=$url;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setDebug($debug){
		$this->debug=$debug;
	}

	public function isDebug(){
		return $this->debug;
	}

	public function setSilent($silent){
		$this->silent=$silent;
	}

	public function isSilent(){
		return $this->silent;
	}

}