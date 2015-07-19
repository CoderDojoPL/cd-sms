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

namespace Arbor\Event;

use Arbor\Core\Event;
use Arbor\Provider\Response;
use Arbor\Event\ExecuteActionEvent;
use Arbor\Event\ExecutedActionEvent;
use Arbor\Exception\ValueNotFoundException;
use Arbor\Exception\PermissionDeniedException;

/**
 * Event for support authorization user
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class Authorization extends Event{
	private $userFunctionalities=array();

	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();
		$session=$request->getSession();

		try{
			$userId=$session->get('user.id');
			if(!$this->isAllow($userId,$this->getFunctionalities($request->getExtra())))
				throw new PermissionDeniedException();
			//goo
		}
		catch(ValueNotFoundException $e){
			//nie zalogowany - pomiń
		}

	}

	public function onExecutedAction(ExecutedActionEvent $event){
		$request=$event->getRequest();

		$presenter=$request->getPresenter();
		if($presenter['class']!='Library\Twig\Presenter\Twig')//tylko dla wywłań twigowych
			return;

		$response=$event->getResponse();
		$content=$response->getContent();
		$content['_functionalities']=$this->userFunctionalities;
		$response->setContent($content);
	}

	private function isAllow($userId,$functionalities){
		$doctrine=$this->getService('doctrine');
		$user=$doctrine->getRepository('Entity\User')->findOneById($userId);
		if(!$user)
			return true; //jeżeli nie jest zalogowany i nie wymaga autentykacji to przepuszczaj

		$role=$user->getRole();

		foreach($role->getFunctionalities() as $functionality){
			$this->userFunctionalities[$functionality->getCode()]=true;
		}

		if(count($functionalities)==0)
			return true;

		foreach($functionalities as $functionality){
				if(isset($this->userFunctionalities[$functionality]))
					return true;
		}

		return false;

	}

	private function getFunctionalities($extra){
		$functionalities=array();
		foreach($extra as $parameter){
			foreach($parameter as $name=>$value){
				if($name=='functionality')
					$functionalities[]=$value['name'];
			}

		}

		return $functionalities;
	}
}