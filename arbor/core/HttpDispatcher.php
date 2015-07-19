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

use Arbor\Core\ExecuteResources;
use Arbor\Provider\Response;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Event\ExecutedActionEvent;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Core\EventManager;
use Arbor\Exception\ActionNotFoundException;
use Arbor\Provider\Session;
use Arbor\Provider\Request;
use Arbor\Contener\RequestConfig;

/**
 * Dispatcher for http request
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class HttpDispatcher  implements Dispatcher {
	private $request;
	private $resources;
	private $config;

	public function __construct(RequestConfig $config){
		$this->config=$config;
	}

	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$session=new Session($this->resources->getEnviorment());
		$this->request=new Request($this->config,$this->resources->getUrl(),$session);
		$this->resources->registerRequest($this->request);

		$this->callMethod();

	}	

	private function callMethod(){

		$this->resources->registerPresenter($this->getPresenter());
		$response=new Response();
		$response->setPresenter($this->resources->getPresenter());
		$event=new ExecuteActionEvent($this->request);
		$this->eventManager->fire('executeAction',$event);
		if(!$event->getResponse()){

			$controllerName=$this->request->getClass();
			$controller=new $controllerName($this->request,$this->resources);

			if(!is_callable(array($controller,$this->request->getMethod()))){
				throw new ActionNotFoundException($controllerName,$this->request->getMethod());
			}

			$controllerData=call_user_func_array(array($controller, $this->request->getMethod()), $this->request->getArguments());
			if($controllerData instanceof Response){
				$response=$controllerData;
				if(!$response->getPresenter())
					$response->setPresenter($this->resources->getPresenter());
				else
					$this->resources->registerPresenter($response->getPresenter());
			}
			else
				$response->setContent($controllerData);
			$event=new ExecutedActionEvent($this->request,$response);
			$this->eventManager->fire('executedAction',$event);

		}
		else
			$response=$event->getResponse();			

		$this->resources->registerResponse($response);

		$this->prepareView($this->request , $this->resources->getPresenter() , $response);
	}

	private function getPresenter(){
		$presenterConfig=$this->config->getPresenter();
		return new $presenterConfig['class']($this->services);
	}

	private function prepareView(Request $request , Presenter $presenter , Response $response){
		$event=new ExecutePresenterEvent($request,$response);
		$this->eventManager->fire('executePresenter',$event);

		$presenter->render($request->getConfig() , $response);
	}


}