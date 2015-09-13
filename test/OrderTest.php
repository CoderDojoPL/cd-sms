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

require_once __DIR__.'/../common/WebTestCaseHelper.php';

use Common\WebTestCaseHelper;
use Entity\Location;
use Entity\Device;
use Entity\DeviceTag;
use Entity\User;
use Entity\Order;
use Entity\Role;

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class OrderTest extends WebTestCaseHelper
{

	public function testIndexUnautheticate()
	{

		$client = $this->createClient();
		$url = $client->loadPage('/order')
			->getUrl();

		$this->assertEquals('/login', $url);

	}

	public function testIndex()
	{

		$em = $this->getService('doctrine')->getEntityManager();

		$location = new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$this->persist($location);

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setSymbol('?');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$this->persist($device);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$user = new User();
		$user->setEmail('owner@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);
		$user->setRole($role);
		$this->persist($user);

		$order = new Order();
		$order->setOwner($user);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
		$order->setDevice($device);

		$this->persist($order);

		$this->flush();

		$session = $this->createSession();
		$session->set('user.id', $user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order');

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(1, $tr, 'Invalid number records in grid');

		$td = $tr[0]->findElements('td');

		$this->assertCount(6, $td, 'Invalid number columns in grid');
		$this->assertEquals($order->getId(), $td[0]->getText(), 'Invalid data columns id');
		$this->assertEquals($order->getDevice()->__toString(), $td[1]->getText(), 'Invalid data columns device');
		$this->assertEquals($order->getOwner()->__toString(), $td[2]->getText(), 'Invalid data columns owner');
		$this->assertEquals($order->getState()->getName(), $td[3]->getText(), 'Invalid data columns state');
		$this->assertEquals($device->getCreatedAt()->format('Y-m-d H:i:s'), $td[4]->getText(), 'Invalid data columns date');

		$actionButtons = $td[5]->findElements('a');

		$footerTr = $client->getElement('table')->getElement('tfoot')->findElements('tr');
		$addButton = $footerTr[1]->getElement('a');

		$this->assertCount(1, $actionButtons, 'Invalid number action buttons in grid');

		$this->assertEquals('Show', $actionButtons[0]->getText(), 'Invalid label for show button');

		$actionButtons[0]->click();

		$this->assertEquals('/order/show/' . $order->getId(), $client->getUrl(), 'Invalid show url');

		$addButton->click();
		$this->assertEquals('/order/add', $client->getUrl(), 'Invalid add url');

	}

	public function testAddUnautheticate()
	{

		$client = $this->createClient();
		$url = $client->loadPage('/order/add')
			->getUrl();

		$this->assertEquals('/login', $url);

	}

	public function testAdd()
	{

		$em = $this->getService('doctrine')->getEntityManager();

		//my location
		$location = new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$this->persist($location);

		//other location
		$otherLocation = new Location();
		$otherLocation->setName('Location name');
		$otherLocation->setCity('Location city');
		$otherLocation->setStreet('Location street');
		$otherLocation->setNumber('Location number');
		$otherLocation->setApartment('Location apartment');
		$otherLocation->setPostal('00-000');
		$otherLocation->setPhone('+48100000000');
		$otherLocation->setEmail('email@email.pl');
		$this->persist($otherLocation);


		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		//device on my location
		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setSymbol('?');
		$device->setLocation($location);

		$this->persist($device);

		//device on other location
		$deviceOtherLocation = new Device();
		$deviceOtherLocation->setName('Device name');
		$deviceOtherLocation->setPhoto('Device.photo.jpg');
		$deviceOtherLocation->getTags()->add($deviceTag);
		$deviceOtherLocation->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$deviceOtherLocation->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$deviceOtherLocation->setDimensions('10x10x10');
		$deviceOtherLocation->setWeight('10kg');
		$deviceOtherLocation->setSerialNumber('Device serial number');
		$deviceOtherLocation->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$deviceOtherLocation->setSymbol('?');
		$deviceOtherLocation->setLocation($otherLocation);

		$this->persist($deviceOtherLocation);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$user = new User();
		$user->setEmail('owner@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);
		$user->setRole($role);

		$this->persist($user);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/add');

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$form = $client->getElement('form');
		$fields = $form->getFields();

		$this->assertCount(1, $fields, 'Invalid number fields');

		$selectOptions = $fields[0]->findElements('option');
		$this->assertCount(2, $selectOptions, 'Invalid number of devices');
		$this->assertEquals($deviceOtherLocation->getId(), $selectOptions[1]->getAttribute('value'), 'Wrong device ID');

		//check required fields
		$form->submit();

		$this->assertEquals('/order/add', $client->getUrl(), 'Invalid url form incorrect submit form');

			//other location
		$form = $client->getElement('form');
		$fields = $form->getFields();


		$this->assertCount(1, $fields, 'Invalid number fields');
		$this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for device');

		$fields[0]->setData($deviceOtherLocation->getId());//device in other location

		$form->submit();

		$this->assertEquals('/order/add/addapply', $client->getUrl(), 'Invalid url form after submited');

		$panelBody = $client->getElement('.panel-body');
		$locationLabel = $panelBody->getElement('strong');

		$this->assertEquals($location->getName() . ' (' . $location->getCity() . ')', $locationLabel->getHtml(), 'Invalid location label');

		$aContacts = $panelBody->findElements('a');

		$this->assertCount(2, $aContacts, 'Invalid button contact info');

		$this->assertEquals($location->getPhone(), $aContacts[0]->getHtml(), 'Invalid phone button');

		$this->assertEquals($location->getEmail(), $aContacts[1]->getHtml(), 'Invalid email button');

		$client->getElement('form')->submit();
		$this->assertEquals('/order', $client->getUrl(), 'Invalid url form after submited');

		$em->clear();
		$orders = $em->getRepository('Entity\Order')->findAll();
		$this->assertCount(1, $orders, 'Invalid number orders');
		$order = $orders[0];
		$this->assertEquals('first name last name', $order->getOwner()->__toString(), 'Invalid owner');
		$this->assertEquals('Device name (Device serial number)', $order->getDevice()->__toString(), 'Invalid device');
		$this->assertEquals(1, $order->getState()->getId(), 'Invalid state');
		$this->assertNull($order->getPerformer(), 'Invalid performer');

		$orderedDevice = $em->getRepository('Entity\Device')->findOneById($deviceOtherLocation->getId());
		$this->assertEquals(2, $orderedDevice->getState()->getId(), 'Invalid device state');

	}

	public function testAddDeviceMyLocation()
	{

		$em = $this->getService('doctrine')->getEntityManager();

		//my location
		$location = new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$this->persist($location);

		//other location
		$otherLocation = new Location();
		$otherLocation->setName('Location name');
		$otherLocation->setCity('Location city');
		$otherLocation->setStreet('Location street');
		$otherLocation->setNumber('Location number');
		$otherLocation->setApartment('Location apartment');
		$otherLocation->setPostal('00-000');
		$otherLocation->setPhone('+48100000000');
		$otherLocation->setEmail('email@email.pl');
		$this->persist($otherLocation);


		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		//device on my location
		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setSymbol('?');
		$device->setLocation($location);

		$this->persist($device);

		//device on other location
		$deviceOtherLocation = new Device();
		$deviceOtherLocation->setName('Device name');
		$deviceOtherLocation->setPhoto('Device.photo.jpg');
		$deviceOtherLocation->getTags()->add($deviceTag);
		$deviceOtherLocation->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$deviceOtherLocation->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$deviceOtherLocation->setDimensions('10x10x10');
		$deviceOtherLocation->setWeight('10kg');
		$deviceOtherLocation->setSerialNumber('Device serial number');
		$deviceOtherLocation->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$deviceOtherLocation->setSymbol('?');
		$deviceOtherLocation->setLocation($otherLocation);

		$this->persist($deviceOtherLocation);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$user = new User();
		$user->setEmail('owner@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);
		$user->setRole($role);
		$this->persist($user);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/add');

		//other location
		$form = $client->getElement('form');
		$fields = $form->getFields();

		$fields[0]->setData($device->getId());//device my location

		$form->submit();

		$this->assertEquals('/order/add/addapply', $client->getUrl(), 'Invalid url form after submited');

		$client->getElement('form')->submit();
		$this->assertEquals(500, $client->getResponse()->getStatusCode(), 'Invalid request status code');
	}


	public function testFetch()
	{

		$em = $this->getService('doctrine')->getEntityManager();

		$location = new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$this->persist($location);

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setSymbol('?');
		$device->setLocation($location);

		$this->persist($device);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$owner = new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location);
		$owner->setRole($role);
		$this->persist($owner);

		$performer = new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location);
		$performer->setRole($role);
		$this->persist($performer);

		$order = new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
		$order->setDevice($device);

		$this->persist($order);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $performer->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/show/' . $order->getId());

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$timeLines = $client->findElements('.timeline-entry');

		$this->assertCount(1, $timeLines, 'Invalid number steps');

		$timeLines[0]->getElement('a')->click();

		$this->assertEquals('/order/show/' . $order->getId(), $client->getUrl(), 'Invalid url form after fetch');

		$em->clear();
		$now = new \DateTime();
		$order = $em->getRepository('Entity\Order')->findOneById($order->getId());
		$this->assertEquals('first name last name', $order->getOwner()->__toString(), 'Invalid owner');
		$this->assertEquals('Device name (Device serial number)', $order->getDevice()->__toString(), 'Invalid device');
		$this->assertEquals(2, $order->getState()->getId(), 'Invalid state');
		$this->assertEquals($performer->getId(), $order->getPerformer()->getId(), 'Invalid performer');
		$this->assertEquals($now->format('Y-m-d'), $order->getFetchedAt()->format('Y-m-d'), 'Invalid fetched at');

	}

	public function testFetchByOwner()
	{

		$em = $this->getService('doctrine')->getEntityManager();


		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setSymbol('?');
		$device->setLocation($this->user->getLocation());

		$this->persist($device);

		$order = new Order();
		$order->setOwner($this->user);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
		$order->setDevice($device);

		$this->persist($order);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $this->user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/show/' . $order->getId());

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$timeLines = $client->findElements('.timeline-entry');

		$this->assertCount(1, $timeLines, 'Invalid number steps');

		$this->assertFalse($timeLines[0]->hasElement('a'),'Redundant fetch button');

		$client->loadPage('/order/fetch/' . $order->getId());

		$this->assertEquals(500, $client->getResponse()->getStatusCode(), 'Invalid status code.');

	}

	public function testCloseByPerformer()
	{

		$em = $this->getService('doctrine')->getEntityManager();

		$location = new Location();
		$location->setName('Location name');
		$location->setCity('Location city');
		$location->setStreet('Location street');
		$location->setNumber('Location number');
		$location->setApartment('Location apartment');
		$location->setPostal('00-000');
		$location->setPhone('+48100000000');
		$location->setEmail('email@email.pl');
		$this->persist($location);

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device->setSymbol('?');
		$device->setLocation($location);

		$this->persist($device);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$owner = new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location);
		$owner->setRole($role);
		$this->persist($owner);

		$performer = new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location);
		$performer->setRole($role);
		$this->persist($performer);

		$order = new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(2));
		$order->setDevice($device);
		$order->setPerformer($performer);
		$order->setFetchedAt(new \DateTime());

		$this->persist($order);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $performer->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/show/' . $order->getId());

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$timeLines = $client->findElements('.timeline-entry');

		$this->assertCount(2, $timeLines, 'Invalid number steps');

		$this->assertFalse($timeLines[0]->hasElement('a'), 'Redundant fetch button');
		$this->assertFalse($timeLines[1]->hasElement('a'), 'Redundant close button');

	}

	private function prepareCloseByOwner(){
		$em = $this->getService('doctrine')->getEntityManager();

		$location1 = new Location();
		$location1->setName('Location name');
		$location1->setCity('Location city');
		$location1->setStreet('Location street');
		$location1->setNumber('Location number');
		$location1->setApartment('Location apartment');
		$location1->setPostal('00-000');
		$location1->setPhone('+48100000000');
		$location1->setEmail('email@email.pl');
		$this->persist($location1);

		$location2 = new Location();
		$location2->setName('Location name 2');
		$location2->setCity('Location city');
		$location2->setStreet('Location street');
		$location2->setNumber('Location number');
		$location2->setApartment('Location apartment');
		$location2->setPostal('00-000');
		$location2->setPhone('+48100000000');
		$location2->setEmail('email@email.pl');
		$this->persist($location2);


		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$owner = new User();
		$owner->setEmail('owner@coderdojo.org.pl');
		$owner->setFirstName('first name');
		$owner->setLastName('last name');
		$owner->setLocation($location2);
		$owner->setRole($role);
		$this->persist($owner);

		$performer = new User();
		$performer->setEmail('owner@coderdojo.org.pl');
		$performer->setFirstName('first name');
		$performer->setLastName('last name');
		$performer->setLocation($location1);
		$performer->setRole($role);
		$this->persist($performer);

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device->setSymbol('?');
		$device->setLocation($location1);
		$device->setUser($performer);

		$this->persist($device);

		$order = new Order();
		$order->setOwner($owner);
		$order->setState($em->getRepository('Entity\OrderState')->findOneById(2));
		$order->setDevice($device);
		$order->setPerformer($performer);
		$order->setFetchedAt(new \DateTime());

		$this->persist($order);

		$this->flush();


		$session = $this->createSession();
		$session->set('user.id', $owner->getId());

		$client = $this->createClient($session);
		$client->loadPage('/order/show/' . $order->getId());

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$timeLines = $client->findElements('.timeline-entry');

		$this->assertCount(2, $timeLines, 'Invalid number steps');

		$buttons=$timeLines[1]->findElements('a');
		$this->assertCount(2,$buttons,'Invalid buttons number');
		$this->assertEquals('Me',$buttons[0]->getHtml(),'Invalid Me label');
		$this->assertEquals('My location',$buttons[1]->getHtml(),'Invalid Location label');

		return array($buttons,$order,$performer,$device,$location2,$client,$owner);
	}

	public function testCloseByOwnerBindMe()
	{
		$em = $this->getService('doctrine')->getEntityManager();

		list($buttons,$order,$performer,$device,$location,$client,$owner)=$this->prepareCloseByOwner();

		$buttons[0]->click();
		$this->assertEquals('/order/show/' . $order->getId(), $client->getUrl(), 'Invalid show url');

		$em->clear();
		$now = new \DateTime();
		$expirationDate=new \DateTime();
		$expirationDate->add(new \DateInterval('P14D'));

		$order = $em->getRepository('Entity\Order')->findOneById($order->getId());
		$this->assertEquals('first name last name', $order->getOwner()->__toString(), 'Invalid owner');
		$this->assertEquals('Device name (Device serial number)', $order->getDevice()->__toString(), 'Invalid device');
		$this->assertEquals(3, $order->getState()->getId(), 'Invalid state');
		$this->assertEquals($performer->getId(), $order->getPerformer()->getId(), 'Invalid performer');
		$this->assertEquals($now->format('Y-m-d'), $order->getFetchedAt()->format('Y-m-d'), 'Invalid fetched at');
		$this->assertEquals($now->format('Y-m-d'), $order->getClosedAt()->format('Y-m-d'), 'Invalid fetched at');

		$device = $em->getRepository('Entity\Device')->findOneById($device->getId());
		$this->assertEquals(2, $device->getState()->getId(), 'Invalid device state');
		$this->assertEquals($location->getId(), $device->getLocation()->getId(), 'Invalid device state');
		$this->assertEquals($expirationDate->format('Y-m-d'), $device->getHireExpirationDate()->format('Y-m-d'), 'Invalid hire expiration date');
		$this->assertEquals($owner->getId(), $device->getUser()->getId(), 'Invalid owner');

	}

	public function testCloseByOwnerBindLocation()
	{
		$em = $this->getService('doctrine')->getEntityManager();

		list($buttons,$order,$performer,$device,$location,$client,$owner)=$this->prepareCloseByOwner();

		$buttons[1]->click();
		$this->assertEquals('/order/show/' . $order->getId(), $client->getUrl(), 'Invalid show url');

		$em->clear();
		$now = new \DateTime();
		$expirationDate=new \DateTime();
		$expirationDate->add(new \DateInterval('P14D'));

		$order = $em->getRepository('Entity\Order')->findOneById($order->getId());
		$this->assertEquals('first name last name', $order->getOwner()->__toString(), 'Invalid owner');
		$this->assertEquals('Device name (Device serial number)', $order->getDevice()->__toString(), 'Invalid device');
		$this->assertEquals(3, $order->getState()->getId(), 'Invalid state');
		$this->assertEquals($performer->getId(), $order->getPerformer()->getId(), 'Invalid performer');
		$this->assertEquals($now->format('Y-m-d'), $order->getFetchedAt()->format('Y-m-d'), 'Invalid fetched at');
		$this->assertEquals($now->format('Y-m-d'), $order->getClosedAt()->format('Y-m-d'), 'Invalid fetched at');

		$device = $em->getRepository('Entity\Device')->findOneById($device->getId());
		$this->assertEquals(2, $device->getState()->getId(), 'Invalid device state');
		$this->assertEquals($location->getId(), $device->getLocation()->getId(), 'Invalid device state');
		$this->assertNull($device->getHireExpirationDate(), 'Invalid hire expiration date');
		$this->assertNull($device->getUser(), 'Invalid owner');

	}
}