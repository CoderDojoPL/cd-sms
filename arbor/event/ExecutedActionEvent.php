<?php

namespace Arbor\Event;

use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;

class ExecutedActionEvent{
	private $response;

	public function __construct(RequestProvider $request , Response $response){
		$this->request=$request;
		$this->response=$response;
	}

	public function getResponse(){
		return $this->response;
	}

	public function getRequest(){
		return $this->request;
	}
}