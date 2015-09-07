<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snippet;
use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Arbor\Exception\UserNotFoundException;

/**
 * @package Snippet
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class User {
	
	/**
	 * Get logged user
	 *
	 * @param \Arbor\Core\Controller $controller
	 * @return \Entity\User
	 * @throws \Arbor\Exception\UserNotFoundException
	 */
	public function getUser(Controller $controller){
		$userId=$controller->getRequest()->getSession()->get('user.id');

		$user=$controller->findOne('User',array('id'=>$userId));

		if(!$user)
			throw new UserNotFoundException();

		return $user;

	}

	public function isAllow(Controller $controller,$requiredFunctionality){
		$user=$this->getUser($controller);
		if(!$user->getRole()){
			return false;
		}
		foreach($user->getRole()->getFunctionalities() as $functionality){
			if($functionality->getId()==$requiredFunctionality){
				return true;
			}
		}

		return false;

	}

}
