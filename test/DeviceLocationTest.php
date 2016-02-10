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
require_once __DIR__ . '/../common/WebTestCaseHelper.php';

use Common\WebTestCaseHelper;
use Entity\User;
use Entity\Device;
use Entity\DeviceTag;
use Entity\DeviceSpecimen;

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class DeviceLocationTest extends WebTestCaseHelper{

	public function testIndexUnautheticate(){

		$client = $this->createClient();
		$url = $client->loadPage('/device/location')
			 ->getUrl();

		$this->assertEquals('/login', $url);

	}

	public function testIndex(){

		$em = $this->getService('doctrine')->getEntityManager();

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$otherUser = new User();
		$otherUser->setEmail('owner@coderdojo.org.pl');
		$otherUser->setFirstName('first name');
		$otherUser->setLastName('last name');
		$otherUser->setLocation($this->user->getLocation());
		$otherUser->setRole($this->user->getRole());
		$this->persist($otherUser);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$this->persist($device);

		$devices=array();

		$devices[0] = new DeviceSpecimen();
		$devices[0]->setDevice($device);
		$devices[0]->setSerialNumber('Device serial number 1');
		$devices[0]->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$devices[0]->setSymbol('ABC');
		$devices[0]->setLocation($this->user->getLocation());
		$devices[0]->setHireExpirationDate(new \DateTime());

		$this->persist($devices[0]);

		$devices[1] = new DeviceSpecimen();
		$devices[1]->setDevice($device);
		$devices[1]->setSerialNumber('Device serial number 2');
		$devices[1]->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$devices[1]->setSymbol('ABC');
		$devices[1]->setLocation($this->user->getLocation());
		$devices[1]->setHireExpirationDate(new \DateTime());

		$this->persist($devices[1]);

		$device3 = new DeviceSpecimen();
		$device3->setDevice($device);
		$device3->setSerialNumber('Device serial number 3');
		$device3->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device3->setUser($otherUser);
		$device3->setSymbol('ABC');
		$device3->setLocation($otherUser->getLocation());
		$device3->setHireExpirationDate(new \DateTime());

		$this->persist($device3);

		$device4 = new DeviceSpecimen();
		$device4->setDevice($device);
		$device4->setSerialNumber('Device serial number');
		$device4->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$device4->setUser($otherUser);
		$device4->setLocation($this->user->getLocation());
		$device4->setSymbol('REF1');
		$device4->setHireExpirationDate(new \DateTime());

		$this->persist($device4);

		$this->flush();

		$session = $this->createSession();
		$session->set('user.id', $this->user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/device/location');

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(2, $tr, 'Invalid number records in grid');

		$ind=0;
		foreach($devices as $deviceSpecimen){
			$td = $tr[$ind++]->findElements('td');
			$this->assertCount(6, $td, 'Invalid number columns in grid');

			$this->assertEquals($deviceSpecimen->getId(), $td[0]->getText(), 'Invalid data columns id');
			$this->assertEquals($deviceSpecimen->getDevice()->getName(), $td[1]->getText(), 'Invalid data columns name');
			$this->assertEquals($deviceSpecimen->getSerialNumber(), $td[2]->getText(), 'Invalid data columns serial number');
			$this->assertEquals($deviceSpecimen->getDevice()->getType()->getName(), $td[3]->getText(), 'Invalid data columns type');
			$this->assertEquals($deviceSpecimen->getState()->getName(), $td[4]->getText(), 'Invalid data columns state');

		}

		$td = $tr[0]->findElements('td');
		$a1=$td['5']->findElements('a');
		$this->assertCount(1,$a1,'Invalid buttons number.');
		$assignButton=$a1[0];

		$this->assertEquals('Assign to me',$assignButton->getText(),'Invalid button label');

		$assignButton->click();

		$this->assertEquals('/device/location/assign/'.$devices[0]->getId(), $client->getUrl(), 'Invalid assign device url');

		$td = $tr[1]->findElements('td');
		$a2=$td['5']->findElements('a');
		$this->assertCount(2,$a2,'Invalid buttons number.');

		$freeButton=$a2[0];
		$this->assertEquals('Free',$freeButton->getText(),'Invalid button label');


		$freeButton->click();

		$this->assertEquals('/device/location/free/'.$devices[1]->getId(), $client->getUrl(), 'Invalid free device url');

		$assignButton=$a2[1];

		$this->assertEquals('Assign to me',$assignButton->getText(),'Invalid button label');

		$assignButton->click();

		$this->assertEquals('/device/location/assign/'.$devices[1]->getId(), $client->getUrl(), 'Invalid assign device url');

	}

	public function testFreeUnautheticate(){

		$em = $this->getService('doctrine')->getEntityManager();

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$this->persist($device);

		$deviceSpecimen=new DeviceSpecimen();
		$deviceSpecimen->setDevice($device);
		$deviceSpecimen->setSerialNumber('Device serial number');
		$deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$deviceSpecimen->setUser($this->user);
		$deviceSpecimen->setLocation($this->user->getLocation());
		$deviceSpecimen->setSymbol('REF1');
		$deviceSpecimen->setHireExpirationDate(new \DateTime());

		$this->persist($deviceSpecimen);
		$this->flush();

		$client = $this->createClient();
		$url = $client->loadPage('/device/location/free/' . $deviceSpecimen->getId())->getUrl();

		$this->assertEquals('/login', $url);

	 }

	 public function testFreeRemove(){

		$em = $this->getService('doctrine')->getEntityManager();

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$this->persist($device);

		$deviceSpecimen=new DeviceSpecimen();
		$deviceSpecimen->setDevice($device);
		$deviceSpecimen->setSerialNumber('Device serial number');
		$deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$deviceSpecimen->setUser($this->user);
		$deviceSpecimen->setLocation($this->user->getLocation());
		$deviceSpecimen->setSymbol('REF1');
		$deviceSpecimen->setHireExpirationDate(new \DateTime());

		$this->persist($deviceSpecimen);
		$this->flush();

		$session = $this->createSession();
		$session->set('user.id', $this->user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/device/location/free/' . $device->getId());

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$panelBody = $client->getElement('.panel-body');
		$buttons = $panelBody->findElements('a');


		$this->assertCount(2, $buttons, 'Invalid number buttons');

		$this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

		$this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


		$buttons[1]->click();

		$this->assertEquals('/device/location', $client->getUrl(), 'Invalid url button NO.');

		$buttons[0]->click();

		$this->assertEquals('/device/location', $client->getUrl(), 'Invalid url button YES.');

		$em->clear();
		$deviceSpecimen = $em->getRepository('Entity\DeviceSpecimen')->findOneBy(array('id' => $deviceSpecimen->getId()));

		$this->assertEquals(1,$deviceSpecimen->getState()->getId(),'Invalid device state.');

 	}

	public function testAssignUnautheticate(){

		//prepare data
		$em = $this->getService('doctrine')->getEntityManager();

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$this->persist($device);

		$deviceSpecimen=new DeviceSpecimen();
		$deviceSpecimen->setDevice($device);
		$deviceSpecimen->setSerialNumber('Device serial number');
		$deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$deviceSpecimen->setUser($this->user);
		$deviceSpecimen->setLocation($this->user->getLocation());
		$deviceSpecimen->setSymbol('REF1');
		$deviceSpecimen->setHireExpirationDate(new \DateTime());

		$this->persist($deviceSpecimen);
		$this->flush();

		//prepare client
		$client = $this->createClient();
		$url = $client->loadPage('/device/location/assign/' . $device->getId())->getUrl();
		//check url
		$this->assertEquals('/login', $url);

	}

	public function testAssign(){

		//prepare data
		$em = $this->getService('doctrine')->getEntityManager();

		$deviceTag = new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$this->persist($deviceTag);

		$device = new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$this->persist($device);

		$deviceSpecimen=new DeviceSpecimen();
		$deviceSpecimen->setDevice($device);
		$deviceSpecimen->setSerialNumber('Device serial number');
		$deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
		$deviceSpecimen->setLocation($this->user->getLocation());
		$deviceSpecimen->setSymbol('REF1');
		$deviceSpecimen->setHireExpirationDate(new \DateTime());

		$this->persist($deviceSpecimen);

		$this->flush();

		//prepare client
		$session = $this->createSession();
		$session->set('user.id', $this->user->getId());

		$client = $this->createClient($session);
		$client->loadPage('/device/location/assign/' . $deviceSpecimen->getId());

		//load page
		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

		$panelBody = $client->getElement('.panel-body');
		$buttons = $panelBody->findElements('a');


		$this->assertCount(2, $buttons, 'Invalid number buttons');

		$this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

		$this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


		$buttons[1]->click();

		$this->assertUrl($client,'/device/location', $client->getUrl());

		$buttons[0]->click();
		$this->assertUrl($client,'/device/location');

		//check data in database
		$em->clear();
		$deviceSpecimen = $em->getRepository('Entity\DeviceSpecimen')->findOneBy(array('id' => $deviceSpecimen->getId()));

		$this->assertEquals($this->user->getId(),$deviceSpecimen->getUser()->getId(),'Invalid device user.');
	}
}