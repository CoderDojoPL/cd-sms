<?php 

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

/**
 * @package Migrate
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Version20150718171510 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){
		$locations=$container->findOne('Location',array());

		if(!$locations){
			$this->beginTransaction();
			$location=new \Entity\Location();
			$location->setName('Main');
			$location->setCity('?');
			$location->setStreet('?');
			$location->setPostal('?');
			$location->setNumber('?');
			$location->setPhone('?');
			$location->setEmail('?');
			$this->persist($location);
			$this->flush();
			$this->commitTransaction();
		}

	}


	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){

	}

}