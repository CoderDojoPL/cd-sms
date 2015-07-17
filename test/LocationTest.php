<?php
namespace Test;
require_once __DIR__.'/../arbor/core/WebTestCase.php';

use Arbor\Core\WebTestCase;
use Entity\Location;

class LocationTest extends WebTestCase{	

	protected function setUp(){//FIXME configure migrate and execute command
		$em=$this->getService('doctrine')->getEntityManager();

		foreach($em->getRepository('Entity\Order')->findAll() as $entity){
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\Device')->findAll() as $entity){
			$entity->getTags()->clear();
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\DeviceTag')->findAll() as $entity){
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\User')->findAll() as $entity){
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\Location')->findAll() as $entity){
			$em->remove($entity);
		}

		$em->flush();
    }

	public function testIndexUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/location')
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

		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/location');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$tr=$client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(1,$tr,'Invalid number records in grid');

		$td=$tr[0]->findElements('td');

		$this->assertCount(6,$td,'Invalid number columns in grid');
		$this->assertEquals($location->getId(),$td[0]->getText(),'Invalid data columns id');
		$this->assertEquals($location->getName(),$td[1]->getText(),'Invalid data columns name');
		$this->assertEquals($location->getCity(),$td[2]->getText(),'Invalid data columns city');
		$this->assertEquals($location->getStreet(),$td[3]->getText(),'Invalid data columns street');
		$this->assertEquals($location->getNumber(),$td[4]->getText(),'Invalid data columns number');

		$actionButtons=$td[5]->findElements('a');

		$footerTr=$client->getElement('table')->getElement('tfoot')->findElements('tr');
		$addButton=$footerTr[1]->getElement('a');

		$this->assertCount(2,$actionButtons,'Invalid number action buttons in grid');

		$this->assertEquals('Edit',$actionButtons[0]->getText(),'Invalid label for edit button');
		$this->assertEquals('Remove',$actionButtons[1]->getText(),'Invalid label for remove button');

		$actionButtons[0]->click();

		$this->assertEquals('/location/edit/'.$location->getId(), $client->getUrl(),'Invalid edit url');

		$actionButtons[1]->click();

		$this->assertEquals('/location/remove/'.$location->getId(), $client->getUrl(),'Invalid remove url');

		$addButton->click();
		$this->assertEquals('/location/add', $client->getUrl(),'Invalid add url');

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

		$em->flush();
		$client=$this->createClient();
		$url=$client->loadPage('/location/remove/'.$location->getId())
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

		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/location/remove/'.$location->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$panelBody=$client->getElement('.panel-body');
		$buttons=$panelBody->findElements('a');


		$this->assertCount(2,$buttons,'Invalid number buttons');

		$this->assertEquals('Yes',$buttons[0]->getText(),'Invalid text button YES');

		$this->assertEquals('No',$buttons[1]->getText(),'Invalid text button NO');


		$buttons[1]->click();

		$this->assertEquals('/location',$client->getUrl(),'Invalid url button NO.');

		$buttons[0]->click();

		$this->assertEquals('/location',$client->getUrl(),'Invalid url button YES.');


		//check removed in database
		$this->assertCount(0,$em->getRepository('Entity\Location')->findAll());
	}


	public function testAddUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/location/add')
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testAdd(){

		$em=$this->getService('doctrine')->getEntityManager();

		$session=$this->createSession();
		$session->set('user.id',1);

		$client=$this->createClient($session);
		$client->loadPage('/location/add');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(8,$fields,'Invalid number fields');

		//check required fields
		$form->submit();

		$this->assertEquals('/location/add',$client->getUrl(),'Invalid url form incorrect submit form');

		$form=$client->getElement('form');
		$fields=$form->getFields();
		

		$this->assertCount(8,$fields,'Invalid number fields');
		$this->assertEquals('Value can not empty',$fields[0]->getParent()->getElement('label')->getText(),'Invalid error message for name');
		$this->assertEquals('Value can not empty',$fields[1]->getParent()->getElement('label')->getText(),'Invalid error message for city');
		$this->assertEquals('Value can not empty',$fields[2]->getParent()->getElement('label')->getText(),'Invalid error message for street');
		$this->assertEquals('Value can not empty',$fields[3]->getParent()->getElement('label')->getText(),'Invalid error message for number');
		$this->assertFalse($fields[4]->getParent()->hasElement('label'),'Redundant error message for apartment');
		$this->assertEquals('Value can not empty',$fields[5]->getParent()->getElement('label')->getText(),'Invalid error message for postal');
		$this->assertEquals('Value can not empty',$fields[6]->getParent()->getElement('label')->getText(),'Invalid error message for phone');
		$this->assertEquals('Value can not empty',$fields[7]->getParent()->getElement('label')->getText(),'Invalid error message for email');

		$fields[0]->setData('name');
		$fields[1]->setData('city');
		$fields[2]->setData('street');
		$fields[3]->setData('number');
		$fields[4]->setData('apartment');
		$fields[5]->setData('00-234');
		$fields[6]->setData('+48531777901');
		$fields[7]->setData('test@coderdojo.org.pl');

		$form->submit();


		$this->assertEquals('/location',$client->getUrl(),'Invalid url form after submited location');

		$locations=$em->getRepository('Entity\Location')->findAll();
		$this->assertCount(1,$locations, 'Invalid number locations');
		$location=$locations[0];
		$this->assertEquals('name',$location->getName(),'Invalid location name');
		$this->assertEquals('city',$location->getCity(),'Invalid location city');
		$this->assertEquals('street',$location->getStreet(),'Invalid location street');
		$this->assertEquals('number',$location->getNumber(),'Invalid location number');
		$this->assertEquals('apartment',$location->getApartment(),'Invalid location apartment');
		$this->assertEquals('00-234',$location->getPostal(),'Invalid location postal');
		$this->assertEquals('+48531777901',$location->getPhone(),'Invalid location phone');
		$this->assertEquals('test@coderdojo.org.pl',$location->getEmail(),'Invalid location email');

	}

	public function testEditUnautheticate(){

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

		$client=$this->createClient();
		$url=$client->loadPage('/location/edit/'.$location->getId())
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testEdit(){

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
		$client->loadPage('/location/edit/'.$location->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(8,$fields,'Invalid number fields');

		$this->assertEquals('Location name',$fields[0]->getData(),'Invalid field value for name');
		$this->assertEquals('Location city',$fields[1]->getData(),'Invalid field value for city');
		$this->assertEquals('Location street',$fields[2]->getData(),'Invalid field value for street');
		$this->assertEquals('Location number',$fields[3]->getData(),'Invalid field value for number');
		$this->assertEquals('Location apartment',$fields[4]->getData(),'Invalid field value for number');
		$this->assertEquals('00-000',$fields[5]->getData(),'Invalid field value for number');
		$this->assertEquals('+48100000000',$fields[6]->getData(),'Invalid field value for number');
		$this->assertEquals('email@email.pl',$fields[7]->getData(),'Invalid field value for number');

		$fields[0]->setData('');
		$fields[1]->setData('');
		$fields[2]->setData('');
		$fields[3]->setData('');
		$fields[4]->setData('');
		$fields[5]->setData('');
		$fields[6]->setData('');
		$fields[7]->setData('');

		$form->submit();

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertEquals('/location/edit/'.$location->getId(),$client->getUrl(),'Invalid url form after submited location');

		$this->assertCount(8,$fields,'Invalid number fields');
		$this->assertEquals('Value can not empty',$fields[0]->getParent()->getElement('label')->getText(),'Invalid error message for name');
		$this->assertEquals('Value can not empty',$fields[1]->getParent()->getElement('label')->getText(),'Invalid error message for city');
		$this->assertEquals('Value can not empty',$fields[2]->getParent()->getElement('label')->getText(),'Invalid error message for street');
		$this->assertEquals('Value can not empty',$fields[3]->getParent()->getElement('label')->getText(),'Invalid error message for number');
		$this->assertFalse($fields[4]->getParent()->hasElement('label'),'Redundant error message for apartment');
		$this->assertEquals('Value can not empty',$fields[5]->getParent()->getElement('label')->getText(),'Invalid error message for postal');
		$this->assertEquals('Value can not empty',$fields[6]->getParent()->getElement('label')->getText(),'Invalid error message for phone');
		$this->assertEquals('Value can not empty',$fields[7]->getParent()->getElement('label')->getText(),'Invalid error message for email');

		$fields[0]->setData('name edit');
		$fields[1]->setData('city edit');
		$fields[2]->setData('street edit');
		$fields[3]->setData('number edit');
		$fields[4]->setData('apartment edit');
		$fields[5]->setData('12-123');
		$fields[6]->setData('+48123123123');
		$fields[7]->setData('change@change.pl');
		$form->submit();

		$this->assertEquals('/location',$client->getUrl(),'Invalid url form after submited location');

		$em->clear();
		$locations=$em->getRepository('Entity\Location')->findAll();
		$this->assertCount(1,$locations, 'Invalid number locations');
		$location=$locations[0];
		$this->assertEquals('name edit',$location->getName(),'Invalid location name');
		$this->assertEquals('city edit',$location->getCity(),'Invalid location city');
		$this->assertEquals('street edit',$location->getStreet(),'Invalid location street');
		$this->assertEquals('number edit',$location->getNumber(),'Invalid location number');
		$this->assertEquals('apartment edit',$location->getApartment(),'Invalid location apartment');
		$this->assertEquals('12-123',$location->getPostal(),'Invalid location postal');
		$this->assertEquals('+48123123123',$location->getPhone(),'Invalid location phone');
		$this->assertEquals('change@change.pl',$location->getEmail(),'Invalid location email');

	}

}