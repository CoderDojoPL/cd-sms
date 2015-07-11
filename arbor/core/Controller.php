<?php

namespace Arbor\Core;

use Arbor\Core\ContenerServices;
use Arbor\Core\RequestProvider;
use Arbor\Provider\Response;
use Arbor\Provider\Session;
use Arbor\Exception\ServiceNotFoundException;
use Arbor\Exception\MethodNotFoundException;
use Arbor\Core\ExecuteResources;

abstract class Controller extends Container{

	private $request;
	private $session;
	private $services=array();
	private $snippets=array();	

	public function __construct(RequestProvider $request, ExecuteResources $executeResources){
		$this->request=$request;
		$this->session=$request->getSession();
		parent::__construct($executeResources);
	}

	public function getRequest(){
		return $this->request;
	}

	public function getSession(){
		return $this->session;
	}
}