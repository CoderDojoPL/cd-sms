<?php

namespace Arbor\Event;

use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;

class ExecutePresenterEvent{
	private $request;
	private $response;

	public function __construct(RequestProvider $request,Response $response){
		$this->request=$request;
		$this->response=$response;
	}

	public function getRequest(){
		return $this->request;
	}

	public function setResponse(Response $response){
		$this->response=$response;
	}

	public function getResponse(){
		return $this->response;
	}
}