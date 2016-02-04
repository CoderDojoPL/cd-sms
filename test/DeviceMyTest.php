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
class DeviceMyTest extends WebTestCaseHelper
{

    public function testIndexUnautheticate()
    {

        $client = $this->createClient();
        $url = $client->loadPage('/device/my')
            ->getUrl();

        $this->assertEquals('/login', $url);

    }

    public function testIndex()
    {

        //prepare data
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
        $devices[0]->setUser($this->user);
        $devices[0]->setSymbol('ABC');
        $devices[0]->setLocation($this->user->getLocation());
        $devices[0]->setHireExpirationDate(new \DateTime());

        $this->persist($devices[0]);

        $devices[1] = new DeviceSpecimen();
        $devices[1]->setDevice($device);
        $devices[1]->setSerialNumber('Device serial number 2');
        $devices[1]->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $devices[1]->setUser($this->user);
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

        $this->flush();

        //create client
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);

        //check loading page
        $client->loadPage('/device/my');

        $this->assertUrl($client,'/device/my');

        //check valid rows grid
        $tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
        $this->assertCount(2, $tr, 'Invalid number records in grid');

        $ind=0;
        foreach($devices as $device){
            $td = $tr[$ind++]->findElements('td');
            $this->assertCount(6, $td, 'Invalid number columns in grid');

            $this->assertEquals($device->getId(), $td[0]->getText(), 'Invalid data columns id');
            $this->assertEquals($device->getDevice()->getName(), $td[1]->getText(), 'Invalid data columns name');
            $this->assertEquals($device->getSerialNumber(), $td[2]->getText(), 'Invalid data columns serial number');
            $this->assertEquals($device->getDevice()->getType()->getName(), $td[3]->getText(), 'Invalid data columns type');
            $this->assertEquals($device->getState()->getName(), $td[4]->getText(), 'Invalid data columns state');

        }

        //check buttons

        //for record 1
        $td = $tr[0]->findElements('td');

        $a1=$td[5]->findElements('a');
        $this->assertCount(1,$a1,'Invalid button number.');
        $assignButton=$a1[0];

        $this->assertEquals('Assign to location',$assignButton->getText(),'Invalid label name');

        $assignButton->click();

        $this->assertUrl($client,'/device/my/assign/'.$devices[0]->getId());

        //for record 2
        $td = $tr[1]->findElements('td');
        $a2=$td[5]->findElements('a');
        $this->assertCount(2,$a2,'Invalid number buttons.');
        $freeButton=$a2[0];
        $this->assertEquals('Free',$freeButton->getText(),'Invalid button label');

        $assignButton=$a2[1];

        $this->assertEquals('Assign to location',$assignButton->getText(),'Invalid label name');

        $freeButton->click();

        $this->assertUrl($client,'/device/my/free/'.$devices[1]->getId());

        $assignButton->click();

        $this->assertUrl($client,'/device/my/assign/'.$devices[1]->getId());

    }


    public function testFreeUnautheticate()
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
        $this->persist($device);
        
        $deviceSpecimen=new DeviceSpecimen();
        $deviceSpecimen->setDevice($device);
        $deviceSpecimen->setSerialNumber('Device serial number');
        $deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $deviceSpecimen->setUser($this->user);
        $deviceSpecimen->setSymbol('abc');
        $deviceSpecimen->setLocation($this->user->getLocation());
        $deviceSpecimen->setHireExpirationDate(new \DateTime());
        $this->persist($deviceSpecimen);

        $this->flush();
        $client = $this->createClient();
        $client->loadPage('/device/my/free/' . $device->getId());

        $this->assertUrl($client,'/login');

    }

    public function testFreeRemove()
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
        $this->persist($device);
        
        $deviceSpecimen=new DeviceSpecimen();
        $deviceSpecimen->setDevice($device);
        $deviceSpecimen->setSerialNumber('Device serial number');
        $deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $deviceSpecimen->setUser($this->user);
        $deviceSpecimen->setSymbol('abc');
        $deviceSpecimen->setLocation($this->user->getLocation());
        $deviceSpecimen->setHireExpirationDate(new \DateTime());
        $this->persist($deviceSpecimen);

        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/my/free/' . $deviceSpecimen->getId());

        $this->assertUrl($client,'/device/my/free/' . $deviceSpecimen->getId());

        $panelBody = $client->getElement('.panel-body');
        $buttons = $panelBody->findElements('a');


        $this->assertCount(2, $buttons, 'Invalid number buttons');

        $this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

        $this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


        $buttons[1]->click();

        $this->assertUrl($client,'/device/my', $client->getUrl());

        $buttons[0]->click();

        $this->assertUrl($client,'/device/my');

        $em->clear();
        $deviceSpecimen = $em->getRepository('Entity\DeviceSpecimen')->findOneBy(array('id' => $deviceSpecimen->getId()));

        $this->assertEquals(1,$deviceSpecimen->getState()->getId(),'Invalid device state.');
    }


    public function testAssignUnautheticate()
    {

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
        $deviceSpecimen->setSymbol('abc');
        $deviceSpecimen->setLocation($this->user->getLocation());
        $deviceSpecimen->setHireExpirationDate(new \DateTime());
        $this->persist($deviceSpecimen);
        $this->flush();

        //prepare client
        $client = $this->createClient();
        $url = $client->loadPage('/device/my/assign/' . $device->getId())->getUrl();
        //check url
        $this->assertEquals('/login', $url);

    }

    public function testAssign()
    {

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
        $deviceSpecimen->setSymbol('abc');
        $deviceSpecimen->setLocation($this->user->getLocation());
        $deviceSpecimen->setHireExpirationDate(new \DateTime());
        $this->persist($deviceSpecimen);
        $this->flush();

        //prepare client
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/my/assign/' . $deviceSpecimen->getId());

        //load page
        $this->assertUrl($client,'/device/my/assign/' . $deviceSpecimen->getId());

        $panelBody = $client->getElement('.panel-body');
        $buttons = $panelBody->findElements('a');


        $this->assertCount(2, $buttons, 'Invalid number buttons');

        $this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

        $this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


        $buttons[1]->click();

        $this->assertUrl($client,'/device/my');

        $buttons[0]->click();

        $this->assertUrl($client,'/device/my');

        //check data in database
        $em->clear();
        $deviceSpecimen = $em->getRepository('Entity\DeviceSpecimen')->findOneBy(array('id' => $deviceSpecimen->getId()));

        $this->assertNull($deviceSpecimen->getUser(),'Invalid device user.');
        $this->assertEquals($this->user->getLocation()->getId(),$deviceSpecimen->getLocation()->getId(),'Invalid location.');
    }
}