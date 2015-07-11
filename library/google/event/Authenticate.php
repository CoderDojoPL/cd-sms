<?php

namespace Library\Google\Event;
use Arbor\Core\Event;
use Arbor\Provider\Response;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Exception\ValueNotFoundException;

class Authenticate extends Event{
	
	public function onExecuteAction(ExecuteActionEvent $event,$eventConfig){
		$request=$event->getRequest();
		foreach($request->getExtra() as $extra){
			foreach($extra as $parameter=>$config){
				if($parameter=='authenticate'){
					$this->execute($event,$config,$eventConfig);
				}
			}
		}

	}

	private function execute($event,$config,$eventConfig){
		$request=$event->getRequest();
		$session=$request->getSession();
		$maxTime=(isset($eventConfig['maxTime'])?$eventConfig['maxTime']:0);

		try{
			$session->get('user.id');
			if($maxTime>0){
				
				if($session->get('session.epoch')<time()){ //deprecated session
					$session->clear();
					throw new ValueNotFoundException('session.epoch');
				}

				$session->set('session.epoch',time()+$maxTime);
				if(!$this->getService('google')->isAuthenticated())
					throw new ValueNotFoundException();
			}

			if(isset($config['session-redirect'])){
				$response=$this->createResponseRedirect($config['session-redirect'],$request);
				$event->setResponse($response);					

			}
		}
		catch(ValueNotFoundException $e){
			if(isset($config['incognito']) && $config['incognito']=='true'){
				//IGNORED
			}
			else if(isset($config['redirect'])){
				$response=$this->createResponseRedirect($config['redirect'],$request);
				$event->setResponse($response);					
			}
			else{
				throw new PermissionDeniedException();
			}
		}


	}

	private function createResponseRedirect($redirect,$request){
		$response=new Response();
		if($request->isAjax()){
			$response->setStatusCode(401);
			$response->setHeader('X-Location',$redirect);
		}
		else{
			$response->redirect($redirect);
		}
		return $response;
	}

	private function checkAuthKey($request){


	}

}