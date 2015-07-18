<?php 
namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

class Version20150718171510 extends MigrateHelper{
	
	public function update(Container $container){
		$locations=$container->findOne('Location',array());

		if(!$locations){
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
		}

	}


	public function downgrade(Container $container){

	}

}