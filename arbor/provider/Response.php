<?php

namespace Arbor\Provider;

use Arbor\Exception\InvalidStatusCodeException;
use Arbor\Exception\HeaderNotFoundException;
use Arbor\Core\Presenter;

class Response{

	private $statusCode=200;
	private $content;
	private $headers=array();
	private $presenter;

	private $availableStatusCodes=array(
		100 =>'Continue'
		,101 => 'Switching Protocols'
		,110 => 'Connection Timed Out'
		,111 => 'Connection refused'
		,200 => 'OK'
		,201 => 'Created'
		,202 => 'Accepted'
		,203 =>	'Non-Authoritative Information'
		,204 =>	'No content'
		,205 =>	'Reset Content'
		,206 =>	'Partial Content'
		,300 =>	'Multiple Choices'
		,301 =>	'Moved Permanently'
		,302 =>	'Found'
		,303 =>	'See Other'
		,304 =>	'Not Modified'
		,305 =>	'Use Proxy'
		,306 =>	'Switch Proxy'
		,307 =>	'Temporary Redirect'
		,310 =>	'Too many redirects'
		,400 =>	'Bad Request'
		,401 =>	'Unauthorized'
		,402 =>	'Payment Required'
		,403 =>	'Forbidden'
		,404 =>	'Not Found'
		,405 =>	'Method Not Allowed'
		,406 =>	'Not Acceptable'
		,407 =>	'Proxy Authentication Required'
		,408 =>	'Request Timeout'
		,409 =>	'Conflict'
		,410 =>	'Gone'
		,411 =>	'Length required'
		,412 =>	'Precondition Failed'
		,413 =>	'Request Entity Too Large'
		,414 =>	'Request-URI Too Long'
		,415 =>	'Unsupported Media Type'
		,416 =>	'Requested Range Not Satisfiable'
		,417 =>	'Expectation Failed'
		,500 => 'Internal Server Error'
		,501 =>	'Not Implemented'
		,502 =>	'Bad Gateway'
		,503 =>	'Service Unavailable'
		,504 =>	'Gateway Timeout'
		,505 =>	'HTTP Version Not Supported'
		);

	public function getStatusCode(){
		return $this->statusCode;
	}

	public function getStatusMessage(){
		return $this->availableStatusCodes[$this->statusCode];
	}

	public function setStatusCode($statusCode){
		if(!isset($this->availableStatusCodes[$statusCode]))
			throw new InvalidStatusCodeException($statusCode);
		$this->statusCode=$statusCode;
	}

	public function setHeader($name,$value){
		$this->headers[strtolower($name)]=$value;
	}

	public function getHeader($name){
		$name=strtolower($name);
		if(!isset($this->headers[$name]))
			throw new HeaderNotFoundException($name);
		return $this->headers[$name];
	}

	public function getHeaders(){
		return $this->headers;
	}

	public function redirect($url){
		$this->setStatusCode(302);
		$this->setHeader('Location',$url);
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content=$content;
	}

	public function setPresenter(Presenter $presenter){
		$this->presenter=$presenter;
	}

	public function getPresenter(){
		return $this->presenter;
	}

	public function setExpire($seconds){
		$time=gmdate("D, d M Y H:i:s", time()+$seconds)." GMT";

		$this->setHeader('Expires' ,$time);
		$this->setHeader('Cache-Control' ,"public; max-age=".$seconds);
		$this->setHeader('Pragma' ,"public; max-age=".$seconds);

	}
}