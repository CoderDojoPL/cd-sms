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
use Exception\LogActionNotFoundException;

/**
 * Cast id to Entity\LogAction
 *
 * @package Mapper
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class LogAction extends Mapper{
	

	/**
	 * {@inheritdoc}
	 */
	public function cast($value){
		$entity=$this->getService('doctrine')->getEntityManager()->getRepository('Entity\LogAction')->findOneById($value);
		if(!$entity)
			throw new LogActionNotFoundException();

		return $entity;
	}
}