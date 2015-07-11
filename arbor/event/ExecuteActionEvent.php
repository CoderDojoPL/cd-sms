<?php

namespace Arbor\Event;

use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;

class ExecuteActionEvent{
	private $request;
	private $response;

	public function __construct(RequestProvider $request){
		$this->request=$request;
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