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
use Exception\LogNotFoundException;

/**
 * Cast id to Entity\Log
 *
 * @package Mapper
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Log extends Mapper{
	

	/**
	 * {@inheritdoc}
	 */
	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\Log')->findOneById($value);
		if(!$entity)
			throw new LogNotFoundException();

		return $entity;
	}
}