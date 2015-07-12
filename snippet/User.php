<?php

namespace Snippet;
use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Arbor\Exception\UserNotFoundException;
use Arbor\Core\Container;

class User {
	
	public function getUser(Controller $controller){
		$userId=$controller->getRequest()->getSession()->get('user.id');

		$user=$controller->findOne('User',array('id'=>$userId));

		if(!$user)
			throw new UserNotFoundException();

		return $user;

	}

}
