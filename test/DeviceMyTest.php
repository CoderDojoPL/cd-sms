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

        $devices=array();

        $devices[0] = new Device();
        $devices[0]->setName('Device name');
        $devices[0]->setPhoto('Device.photo.jpg');
        $devices[0]->getTags()->add($deviceTag);
        $devices[0]->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
        $devices[0]->setSerialNumber('Device serial number');
        $devices[0]->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $devices[0]->setUser($this->user);
        $devices[0]->setSymbol('ABC');
        $devices[0]->setLocation($this->user->getLocation());

        $this->persist($devices[0]);
        $devices[1] = new Device();
        $devices[1]->setName('Device name2');
        $devices[1]->setPhoto('Device.photo.jpg');
        $devices[1]->getTags()->add($deviceTag);
        $devices[1]->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
        $devices[1]->setSerialNumber('Device serial number');
        $devices[1]->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $devices[1]->setUser($this->user);
        $devices[1]->setSymbol('ABC');
        $devices[1]->setLocation($this->user->getLocation());

        $this->persist($devices[1]);

        $device3 = new Device();
        $device3->setName('Device name other');
        $device3->setPhoto('Device.photo.jpg');
        $device3->getTags()->add($deviceTag);
        $device3->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
        $device3->setSerialNumber('Device serial number');
        $device3->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $device3->setUser($otherUser);
        $device3->setSymbol('ABC');
        $device3->setLocation($otherUser->getLocation());

        $this->persist($device3);

        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/my');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
        $this->assertCount(2, $tr, 'Invalid number records in grid');

        $ind=0;
        foreach($devices as $device){
            $td = $tr[$ind++]->findElements('td');
            $this->assertCount(6, $td, 'Invalid number columns in grid');

            $this->assertEquals($device->getId(), $td[0]->getText(), 'Invalid data columns id');
            $this->assertEquals($device->getName(), $td[1]->getText(), 'Invalid data columns name');
            $this->assertEquals($device->getSerialNumber(), $td[2]->getText(), 'Invalid data columns serial number');
            $this->assertEquals($device->getType()->getName(), $td[3]->getText(), 'Invalid data columns type');
            $this->assertEquals($device->getState()->getName(), $td[4]->getText(), 'Invalid data columns state');

        }

        $td = $tr[0]->findElements('td');
        $this->assertFalse($td['5']->hasElement('a'),'Redundant button.');

        $td = $tr[1]->findElements('td');
        $this->assertTrue($td['5']->hasElement('a'),'Free button not found.');
        $freeButton=$td['5']->getElement('a');
        $this->assertEquals('Free',$freeButton->getText(),'Invalid button label');



        $freeButton->click();

        $this->assertEquals('/device/my/free/'.$devices[1]->getId(), $client->getUrl(), 'Invalid free device url');

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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $device->setUser($this->user);
        $device->setSymbol('abc');
        $device->setLocation($this->user->getLocation());

        $this->persist($device);
        $this->flush();
        $client = $this->createClient();
        $url = $client->loadPage('/device/my/free/' . $device->getId())->getUrl();

        $this->assertEquals('/login', $url);

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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $device->setUser($this->user);
        $device->setSymbol('ABC');
        $device->setLocation($this->user->getLocation());

        $this->persist($device);
        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/my/free/' . $device->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $panelBody = $client->getElement('.panel-body');
        $buttons = $panelBody->findElements('a');


        $this->assertCount(2, $buttons, 'Invalid number buttons');

        $this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

        $this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


        $buttons[1]->click();

        $this->assertEquals('/device/my', $client->getUrl(), 'Invalid url button NO.');

        $buttons[0]->click();

        $this->assertEquals('/device/my', $client->getUrl(), 'Invalid url button YES.');

        $em->clear();
        $device = $em->getRepository('Entity\Device')->findOneBy(array('id' => $device->getId()));

        $this->assertEquals(1,$device->getState()->getId(),'Invalid device state.');
    }
}