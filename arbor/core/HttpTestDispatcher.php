<?php


namespace Arbor\Core;
use Arbor\Core\ExecuteResources;
use Arbor\Provider\Response;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Event\ExecutedActionEvent;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Core\EventManager;
use Arbor\Exception\ActionNotFoundException;
use Arbor\Provider\Session;
use Arbor\Provider\Request;

class HttpTestDispatcher  extends HttpDispatcher {
	private $request;
	private $resources;

	public function setRequestTest(RequestTest $request){
		$this->request;
	}

	protected function process(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$session=new Session($this->resources->getEnviorment());
		$this->request->setConfig($this->config);
		$this->resources->registerRequest($this->request);

		$this->callMethod();

	}

}