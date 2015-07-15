<?php
namespace Test;
require __DIR__.'/../arbor/core/WebTestCase.php';

use Arbor\Core\WebTestCase;
use Entity\Location;
use Entity\Device;
use Entity\DeviceTag;
use Entity\DeviceState;

class DeviceTest extends WebTestCase{	

	protected function setUp(){//FIXME configure migrate and execute command
		$em=$this->getService('doctrine')->getEntityManager();

		foreach($em->getRepository('Entity\Device')->findAll() as $entity){
			$entity->getTags()->clear();
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\DeviceTag')->findAll() as $entity){
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\Location')->findAll() as $entity){
			$em->remove($entity);
		}

		$em->flush();
    }

	public function testIndexUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/device')
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testIndex(){

		$em=$this->getService('doctrine')->getEntityManager();

		$location=new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$em->persist($location);



		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);


		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/device');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$tr=$client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(1,$tr,'Invalid number records in grid');

		$td=$tr[0]->findElements('td');

		$this->assertCount(7,$td,'Invalid number columns in grid');
		$this->assertEquals($device->getId(),$td[0]->getText(),'Invalid data columns id');
		$this->assertEquals('',$td[1]->getText(),'Invalid data columns photo');
		$this->assertEquals($device->getName(),$td[2]->getText(),'Invalid data columns name');
		$this->assertEquals($device->getSerialNumber(),$td[3]->getText(),'Invalid data columns serial number');
		$this->assertEquals($device->getType()->getName(),$td[4]->getText(),'Invalid data columns type');
		$this->assertEquals($device->getLocation()->getName(),$td[5]->getText(),'Invalid data columns location');

		$actionButtons=$td[6]->findElements('a');

		$footerTr=$client->getElement('table')->getElement('tfoot')->findElements('tr');
		$addButton=$footerTr[1]->getElement('a');

		$this->assertCount(2,$actionButtons,'Invalid number action buttons in grid');

		$this->assertEquals('Edit',$actionButtons[0]->getText(),'Invalid label for edit button');
		$this->assertEquals('Remove',$actionButtons[1]->getText(),'Invalid label for remove button');

		$actionButtons[0]->click();

		$this->assertEquals('/device/edit/'.$device->getId(), $client->getUrl(),'Invalid edit url');

		$actionButtons[1]->click();

		$this->assertEquals('/device/remove/'.$device->getId(), $client->getUrl(),'Invalid remove url');


		$addButton->click();
		$this->assertEquals('/device/add', $client->getUrl(),'Invalid add url');

	}

	public function testRemoveUnautheticate(){

		$em=$this->getService('doctrine')->getEntityManager();

		$location=new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$em->persist($location);



		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);


		$em->flush();
		$client=$this->createClient();
		$url=$client->loadPage('/device/remove/'.$device->getId())
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testRemove(){

		$em=$this->getService('doctrine')->getEntityManager();

		$location=new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$em->persist($location);



		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);


		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/device/remove/'.$device->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$panelBody=$client->getElement('.panel-body');
		$buttons=$panelBody->findElements('a');


		$this->assertCount(2,$buttons,'Invalid number buttons');

		$this->assertEquals('Yes',$buttons[0]->getText(),'Invalid text button YES');

		$this->assertEquals('No',$buttons[1]->getText(),'Invalid text button NO');


		$buttons[1]->click();

		$this->assertEquals('/device',$client->getUrl(),'Invalid url button NO.');

		$buttons[0]->click();

		$this->assertEquals('/device',$client->getUrl(),'Invalid url button YES.');


		//check removed in database
		$this->assertCount(0,$em->getRepository('Entity\Device')->findAll());
	}


	public function testAddUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/device/add')
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testAdd(){

		$em=$this->getService('doctrine')->getEntityManager();

		$location=new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$em->persist($location);

		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/device/add');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(11,$fields,'Invalid number fields');

		$form->submit();

		$this->assertEquals('/device/add',$client->getUrl(),'Invalid url form incorrect submit form');

		//TODO detect invalid field

	}


}