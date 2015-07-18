<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test;

use Arbor\Core\WebTestCase;
use Entity\Location;
use Entity\Device;
use Entity\DeviceTag;
use Entity\DeviceState;
use Entity\User;
use Entity\Order;

require_once __DIR__.'/../arbor/core/WebTestCase.php';

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class OrderTest extends WebTestCase{	

	protected function setUp(){
		$this->executeCommand('migrate:downgrade');
		$this->executeCommand('migrate:update');
	}

	public function testIndexUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/order')
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

		$user=new User();
		$user->setEmail('owner@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);

		$em->persist($user);

		$order=new Order();
		$order->setOwner($user);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
		$order->setDevice($device);

		$em->persist($order);

		$em->flush();

		$session=$this->createSession();
		$session->set('user.id',$user->getId());

		$client=$this->createClient($session);
		$client->loadPage('/order');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$tr=$client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(1,$tr,'Invalid number records in grid');

		$td=$tr[0]->findElements('td');

		$this->assertCount(6,$td,'Invalid number columns in grid');
		$this->assertEquals($order->getId(),$td[0]->getText(),'Invalid data columns id');
		$this->assertEquals($order->getDevice()->__toString(),$td[1]->getText(),'Invalid data columns device');
		$this->assertEquals($order->getOwner()->__toString(),$td[2]->getText(),'Invalid data columns owner');
		$this->assertEquals($order->getState()->getName(),$td[3]->getText(),'Invalid data columns state');
		$this->assertEquals($device->getCreatedAt()->format('Y-m-d H:i:s'),$td[4]->getText(),'Invalid data columns date');

		$actionButtons=$td[5]->findElements('a');

		$footerTr=$client->getElement('table')->getElement('tfoot')->findElements('tr');
		$addButton=$footerTr[1]->getElement('a');

		$this->assertCount(1,$actionButtons,'Invalid number action buttons in grid');

		$this->assertEquals('Show',$actionButtons[0]->getText(),'Invalid label for show button');

		$actionButtons[0]->click();

		$this->assertEquals('/order/show/'.$order->getId(), $client->getUrl(),'Invalid show url');

		$addButton->click();
		$this->assertEquals('/order/add', $client->getUrl(),'Invalid add url');

	}

	public function testAddUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/order/add')
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

		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);

		$user=new User();
		$user->setEmail('owner@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);

		$em->persist($user);

		$em->flush();


		$session=$this->createSession();
		$session->set('user.id',$user->getId());

		$client=$this->createClient($session);
		$client->loadPage('/order/add');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(1,$fields,'Invalid number fields');

		//check required fields
		$form->submit();

		$this->assertEquals('/order/add',$client->getUrl(),'Invalid url form incorrect submit form');

		$form=$client->getElement('form');
		$fields=$form->getFields();
		

		$this->assertCount(1,$fields,'Invalid number fields');
		$this->assertEquals('Value can not empty',$fields[0]->getParent()->getElement('label')->getText(),'Invalid error message for device');

		$fields[0]->setData($device->getId());

		$form->submit();

		$this->assertEquals('/order/add/addapply',$client->getUrl(),'Invalid url form after submited');

		$panelBody=$client->getElement('.panel-body');
		$locationLabel=$panelBody->getElement('strong');

		$this->assertEquals($location->getName().' ('.$location->getCity().')',$locationLabel->getHtml(),'Invalid location label');

		$aContacts=$panelBody->findElements('a');

		$this->assertCount(2,$aContacts,'Invalid button contact info');
		
		$this->assertEquals($location->getPhone(),$aContacts[0]->getHtml(),'Invalid phone button');

		$this->assertEquals($location->getEmail(),$aContacts[1]->getHtml(),'Invalid email button');

		$client->getElement('form')->submit();
		$this->assertEquals('/order',$client->getUrl(),'Invalid url form after submited');

		$em->clear();
		$orders=$em->getRepository('Entity\Order')->findAll();
		$this->assertCount(1,$orders, 'Invalid number orders');
		$order=$orders[0];
		$this->assertEquals('first name last name',$order->getOwner()->__toString(),'Invalid owner');
		$this->assertEquals('Device name (Device serial number)',$order->getDevice()->__toString(),'Invalid device');
		$this->assertEquals(1,$order->getState()->getId(),'Invalid state');
		$this->assertNull($order->getPerformer(),'Invalid performer');

		$device=$em->getRepository('Entity\Device')->findOneById($device->getId());
		$this->assertEquals(2,$device->getState()->getId(),'Invalid device state');

	}

	public function testFetch(){

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
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);

		$owner=new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location);

		$em->persist($owner);

		$performer=new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location);

		$em->persist($performer);

		$order=new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
		$order->setDevice($device);

		$em->persist($order);

		$em->flush();


		$session=$this->createSession();
		$session->set('user.id',$performer->getId());

		$client=$this->createClient($session);
		$client->loadPage('/order/show/'.$order->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$timeLines=$client->findElements('.timeline-entry');

		$this->assertCount(1,$timeLines,'Invalid number steps');

		$timeLines[0]->getElement('a')->click();

		$this->assertEquals('/order/show/'.$order->getId(),$client->getUrl(),'Invalid url form after fetch');

		$em->clear();
		$now=new \DateTime();
		$order=$em->getRepository('Entity\Order')->findOneById($order->getId());
		$this->assertEquals('first name last name',$order->getOwner()->__toString(),'Invalid owner');
		$this->assertEquals('Device name (Device serial number)',$order->getDevice()->__toString(),'Invalid device');
		$this->assertEquals(2,$order->getState()->getId(),'Invalid state');
		$this->assertEquals($performer->getId(),$order->getPerformer()->getId(),'Invalid performer');
		$this->assertEquals($now->format('Y-m-d'),$order->getFetchedAt()->format('Y-m-d'),'Invalid fetched at');

	}

	public function testCloseByPerformer(){

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
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device->setLocation($location);

		$em->persist($device);

		$owner=new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location);

		$em->persist($owner);

		$performer=new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location);

		$em->persist($performer);

		$order=new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(2));
		$order->setDevice($device);
		$order->setPerformer($performer);
		$order->setFetchedAt(new \DateTime());

		$em->persist($order);

		$em->flush();


		$session=$this->createSession();
		$session->set('user.id',$performer->getId());

		$client=$this->createClient($session);
		$client->loadPage('/order/show/'.$order->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$timeLines=$client->findElements('.timeline-entry');

		$this->assertCount(2,$timeLines,'Invalid number steps');

		$this->assertFalse($timeLines[0]->hasElement('a'),'Redundant fetch button');
		$this->assertFalse($timeLines[1]->hasElement('a'),'Redundant close button');

	}

	public function testCloseByOwner(){

		$em=$this->getService('doctrine')->getEntityManager();

		$location1=new Location();
		$location1->setName('Location name');
		$location1->setCity('Location city');
		$location1->setStreet('Location street');
		$location1->setNumber('Location number');
		$location1->setApartment('Location apartment');
		$location1->setPostal('00-000');
		$location1->setPhone('+48100000000');
		$location1->setEmail('email@email.pl');
		$em->persist($location1);

		$location2=new Location();
		$location2->setName('Location name 2');
		$location2->setCity('Location city');
		$location2->setStreet('Location street');
		$location2->setNumber('Location number');
		$location2->setApartment('Location apartment');
		$location2->setPostal('00-000');
		$location2->setPhone('+48100000000');
		$location2->setEmail('email@email.pl');
		$em->persist($location2);

		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device->setLocation($location1);

		$em->persist($device);

		$owner=new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location2);

		$em->persist($owner);

		$performer=new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location1);

		$em->persist($performer);

		$order=new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(2));
		$order->setDevice($device);
		$order->setPerformer($performer);
		$order->setFetchedAt(new \DateTime());

		$em->persist($order);

		$em->flush();


		$session=$this->createSession();
		$session->set('user.id',$owner->getId());

		$client=$this->createClient($session);
		$client->loadPage('/order/show/'.$order->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$timeLines=$client->findElements('.timeline-entry');

		$this->assertCount(2,$timeLines,'Invalid number steps');

		$timeLines[1]->getElement('a')->click();

		$this->assertEquals('/order/show/'.$order->getId(), $client->getUrl(),'Invalid show url');

		$em->clear();
		$now=new \DateTime();
		$order=$em->getRepository('Entity\Order')->findOneById($order->getId());
		$this->assertEquals('first name last name',$order->getOwner()->__toString(),'Invalid owner');
		$this->assertEquals('Device name (Device serial number)',$order->getDevice()->__toString(),'Invalid device');
		$this->assertEquals(3,$order->getState()->getId(),'Invalid state');
		$this->assertEquals($performer->getId(),$order->getPerformer()->getId(),'Invalid performer');
		$this->assertEquals($now->format('Y-m-d'),$order->getFetchedAt()->format('Y-m-d'),'Invalid fetched at');
		$this->assertEquals($now->format('Y-m-d'),$order->getClosedAt()->format('Y-m-d'),'Invalid fetched at');

		$device=$em->getRepository('Entity\Device')->findOneById($device->getId());
		$this->assertEquals(1,$device->getState()->getId(),'Invalid device state');
		$this->assertEquals($location2->getId(),$device->getLocation()->getId(),'Invalid device state');

	}
}