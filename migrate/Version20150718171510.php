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

				$this->executeQuery('INSERT INTO locations('.($this->getDriver()=='pdo_pgsql'?'id,':'').'name,city,street,postal,number,phone,email,created_at,updated_at) VALUES('.($this->getDriver()=='pdo_pgsql'?"nextval('locations_id_seq'),":'').':name,:city,:street,:postal,:number,:phone,:email,now(),now());',array(
					'name'=>'Main'
					,'city'=>'?'
					,'street'=>'?'
					,'postal'=>'?'
					,'number'=>'?'
					,'phone'=>'?'
					,'email'=>'?'
				));


			$this->commitTransaction();
		}

	}


	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){

	}

}