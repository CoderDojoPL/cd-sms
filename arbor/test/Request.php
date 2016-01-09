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

namespace Arbor\Test;

use Arbor\Provider\Session;
use Arbor\Core\RequestProvider;
use Arbor\Exception\HeaderNotFoundException;
use Arbor\Core\Enviorment;
use Arbor\Root;
use Arbor\Core\Autoloader;
use Arbor\Core\FileUploaded;
use Arbor\Exception\FileNotUploadedException;

/**
 * Request provider for functionalit test
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Request implements RequestProvider{

	private $url;
	private $data=array();
	private $query=array();
	private $session;
	private $type='GET';
	private $headers=array();
	private $body;
	private $arguments=array();
	private $route;
	private $controller;
	private $class;
	private $method;
	private $presenter;
	private $extra=array();
	private $host;
	private $protocol;
	private $ssl=false;
	private $config;
	private $enviorment;
	private $ajax;
	private $clientIp;

	public function __construct($url,Enviorment $enviorment,Session $session=null){
		$this->url=$url;
		$this->session=($session?$session:new Session($enviorment));
		$this->enviorment=$enviorment;
		$this->clientIp='127.0.0.1';
	}

	public function setConfig($config){
		$this->config=$config;
		$this->controller=$this->config->getController();
		$this->method=$this->config->getMethod();
		$this->class=$this->config->getClass();
		$this->extra=$this->config->getExtra();
		$this->presenter=$this->config->getPresenter();

	}

	public function getConfig(){
		return $this->config;
	}


	public function execute(){
		$autoloader=new Autoloader();
		$root=new Root($autoloader,$this->enviorment->isDebug(),$this->enviorment->isSilent(),$this->enviorment->getName()); //FIXME wygląda że trzeba będzie tak wywoływać testy funkcjonalne
		return $root->executeRequestTest($this);
	}

	public function getUrl(){
		return $this->url;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type=$type;
	}

	public function setData($data){
		$this->data=$data;
	}

	public function getHeader($name){
		if(!isset($this->headers[strtolower($name)]))
			throw new HeaderNotFoundException($name);
			
		return $this->headers[strtolower($name)]; 
	}

	public function setHeader($name,$value){
		$this->headers[strtolower($name)]=$value;
	}

	public function getBody(){
		return $this->body;
	}

	public function setArgument($name,$value){
		$this->arguments[$name]=$value;
	}

	public function getArguments(){
		return $this->arguments;
	}

	public function removeArgument($index){
		unset($this->arguments[$index]);
	}

	public function getRoute(){
		return $this->route;
	}

	public function getSession(){
		return $this->session;
	}

	public function getClass(){
		return $this->class;
	}

	public function getController(){
		return $this->controller;
	}

	public function setController($controller){
		$this->controller=$controller;
	}

	public function getMethod(){
		return $this->method;
	}

	public function setMethod($method){
		$this->method=$method;
	}

	public function getPresenter(){
		return $this->presenter;
	}

	public function getExtra(){
		return $this->extra;
	}

	public function getData(){
		return $this->data;
	}

	public function getQuery(){
		return $this->query;
	}

	public function getHost(){
		return $this->host;
	}

	public function getProtocol(){
		return $this->protocol;
	}

	public function isSSL(){
		return $this->ssl;
	}

	public function getFile($name){
		if(!isset($this->files[$name])){
			throw new FileNotUploadedException($name);
		}

		return $this->files[$name];
	}

	public function addFile($name,$size,$error=null){
		$this->files[$name]=new FileUploaded();
	}


	public function isAjax(){
		try{
			return $this->ajax || strtolower($this->getHeader('x-requested-with'))=='xmlhttprequest';
		}
		catch(HeaderNotFoundException $e){
			return false;
		}

	}

	public function setAjax($flag){
		$this->ajax=$flag;
	}

	public function getClientIp(){
		return $this->clientIp;
	}

	public function setClientIp($ip){
		return $this->clientIp=$ip;
	}

	public function isFullUploadedData(){
		return true;
	}
}