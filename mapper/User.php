<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mapper;
use Arbor\Core\Mapper;
use Exception\UserNotFoundException;

/**
 * Cast id to Entity\User
 *
 * @package Mapper
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class User extends Mapper{
	

	/**
	 * {@inheritdoc}
	 */
	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\User')->findOneById($value);
		if(!$entity)
			throw new UserNotFoundException();

		return $entity;
	}
}