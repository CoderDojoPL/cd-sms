<?php

namespace Event;
use Arbor\Core\Event;
use Arbor\Exception\ValueNotFoundException;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Provider\Response;

class SetLocation extends Event{
	
	public function onExecuteAction(ExecuteActionEvent $event,$eventConfig){
		$request=$event->getRequest();

		try{
			$userId=$request->getSession()->get('user.id');
			$user=$this->cast('Mapper\User',$userId);
			$redirectUrl=null;
			if($request->getController()=='Resource'){
				return;
			}
			else if(!$user->getLocation() && ($request->getController()!='Authenticate' || $request->getMethod()!='setLocation')){
				$redirectUrl='/login/location';
			}
			else if($user->getLocation() && $request->getController()=='Authenticate' && $request->getMethod()=='setLocation'){
				$redirectUrl='/';

			}
			else{
				return;
			}

			$response=new Response();
			$response->redirect($redirectUrl);
			$event->setResponse($response);

		}
		catch(ValueNotFoundException $e){
			return;
		}
		catch(UserNotFoundExceptio $e){
			$request->getSession()->clear();
			$response=new Response();
			$response->redirect('/login');
			$event->setResponse($response);
		}

	}

}