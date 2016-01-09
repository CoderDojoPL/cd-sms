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

        //create client
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);

        //check loading page
        $client->loadPage('/device/my');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        //check valid rows grid
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

        //check buttons

        //for record 1
        $td = $tr[0]->findElements('td');

        $a1=$td[5]->findElements('a');
        $this->assertCount(1,$a1,'Invalid button number.');
        $assignButton=$a1[0];

        $this->assertEquals('Assign to location',$assignButton->getText(),'Invalid label name');

        $assignButton->click();

        $this->assertEquals('/device/my/assign/'.$devices[0]->getId(), $client->getUrl(), 'Invalid assign device url');

        //for record 2
        $td = $tr[1]->findElements('td');
        $a2=$td[5]->findElements('a');
        $this->assertCount(2,$a2,'Invalid number buttons.');
        $freeButton=$a2[0];
        $this->assertEquals('Free',$freeButton->getText(),'Invalid button label');

        $assignButton=$a2[1];

        $this->assertEquals('Assign to location',$assignButton->getText(),'Invalid label name');

        $freeButton->click();

        $this->assertEquals('/device/my/free/'.$devices[1]->getId(), $client->getUrl(), 'Invalid free device url');

        $assignButton->click();

        $this->assertEquals('/device/my/assign/'.$devices[1]->getId(), $client->getUrl(), 'Invalid assign device url');

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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $device->setUser($this->user);
        $device->setSymbol('abc');
        $device->setLocation($this->user->getLocation());

        $this->persist($device);
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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(2));
        $device->setUser($this->user);
        $device->setSymbol('ABC');
        $device->setLocation($this->user->getLocation());

        $this->persist($device);
        $this->flush();

        //prepare client
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/my/assign/' . $device->getId());

        //load page
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

        //check data in database
        $em->clear();
        $device = $em->getRepository('Entity\Device')->findOneBy(array('id' => $device->getId()));

        $this->assertNull($device->getUser(),'Invalid device user.');
        $this->assertEquals($this->user->getLocation()->getId(),$device->getLocation()->getId(),'Invalid location.');
    }
}