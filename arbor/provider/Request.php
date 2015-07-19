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

use Arbor\Collection\Map;
use Arbor\Provider\Session;
use Arbor\Core\RequestProvider;
use Arbor\Exception\HeaderNotFoundException;
use Arbor\Core\FileUploaded;
use Arbor\Exception\FileNotUploadedException;

/**
 * Provider for request.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Request implements RequestProvider{

	private $config;
	private $data=array();
	private $query=array();
	private $session;
	private $arguments=array();
	private $url;
	private $headers=array();
	private $controller;
	private $method;
	private $class;
	private $extra;
	private $clientIp;
	private $files=array();

	public function __construct($config,$url,Session $session){
		$this->config=$config;
		$this->url=$url;
		$this->session=$session;
		$this->controller=$this->config->getController();
		$this->method=$this->config->getMethod();
		$this->class=$this->config->getClass();
		$this->extra=$this->config->getExtra();
		$this->prepare();

	}

	/**
	 * Get uploaded file
	 * @param string $name - field name
	 * @return Arbor\Core\FileUploaded|array[Arbor\Core\FileUploaded]
	 * @since 0.12.0
	 */
	public function getFile($name){
		if(!isset($this->files[$name])){
			throw new FileNotUploadedException($name);
		}

		return $this->files[$name];
	}

	public function getConfig(){
		return $this->config;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getType(){
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getHeader($name){
		if(!isset($this->headers[strtolower($name)]))
			throw new HeaderNotFoundException($name);
			
		return $this->headers[strtolower($name)]; 
	}

	public function getBody(){
		return file_get_contents('php://input');
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
		return $this->config->getRoute();
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
		$this->class="Controller\\".$controller; //FIXME może jednak jakoś inaczej to zapisywać np w controller pełna ścieżka
		$this->controller=$controller;
	}

	public function getMethod(){
		return $this->method;
	}

	public function setMethod($method){
		$this->method=$method;
	}

	public function getPresenter(){
		return $this->config->getPresenter();
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
		return $_SERVER['HTTP_HOST'];
	}

	public function getProtocol(){
		return $_SERVER['SERVER_PROTOCOL'];
	}

	public function isSSL(){
		return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) 
			&& $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) 
			&& $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') || (isset($_SERVER['HTTP_X_SSL_CIPHER'])));
	}

	public function isAjax(){
		try{
			return strtolower($this->getHeader('x-requested-with'))=='xmlhttprequest';
		}
		catch(HeaderNotFoundException $e){
			return false;
		}

	}

	public function getClientIp(){
		return $this->clientIp;
	}

	/**
	 * Detect for uploaded big post data on server. If return false then propably file and other post data not uploaded.
	 * @return boolean
	 * @since 0.18.0 
	 */
	public function isFullUploadedData(){
		return !(isset($_SERVER['CONTENT_LENGTH']) 
			&& (int) $_SERVER['CONTENT_LENGTH'] > $this->phpSizeToBytes(ini_get('post_max_size')));
	}

	private function phpSizeToBytes($size){  
		if (is_numeric( $size)){
			return $size;
		}
		$suffix = substr($size, -1);
		$value = substr($size, 0, -1);
		switch(strtolower($suffix)){
			case 'p':
				$value *= 1024;
			case 't':
				$value *= 1024;
			case 'g':
				$value *= 1024;
			case 'm':
				$value *= 1024;
			case 'k':
				$value *= 1024;
				break;
		}
		return $value;  
	}

	private function prepare(){
		$this->data=$_POST;
		$this->query=$_GET;

		foreach($_FILES as $kFile=>$file){
			$fileData=null;
			if(is_array($file['name'])){ //multiple files
				$fileData=array();
				for($i=0; $i<count($file['name']); $i++){
					if($file['tmp_name'][$i]==''){
						continue;
					}

					$metadata=array(
						'name'=>$file['name'][$i]
						,'tmp_name'=>$file['tmp_name'][$i]
						,'error'=>$file['error'][$i]
						,'size'=>$file['size'][$i]
						,'type'=>$file['type'][$i]
						);
					$fileData[]=new FileUploaded($metadata);
				}
			}
			else{
				if($file['tmp_name']==''){
					continue;
				}
				$fileData=new FileUploaded($file);
			}
			$this->files[$kFile]=$fileData;


		}

		foreach ($_SERVER as $name => $value) { 
			if(substr($name, 0, 5) == 'HTTP_'){ 
				$name = strtolower(str_replace(' ', '-', ucwords(str_replace('_', ' ', substr($name, 5))))); 
				$this->headers[$name] = $value; 
			}
			else if ($name == "CONTENT_TYPE"){ 
				$this->headers["content-type"] = $value; 
			}
			else if ($name == "CONTENT_LENGTH"){ 
				$this->headers["content-length"] = $value; 
			}
		}

		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$this->clientIp = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$this->clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$this->clientIp = $_SERVER['REMOTE_ADDR'];
		}		
	}
}