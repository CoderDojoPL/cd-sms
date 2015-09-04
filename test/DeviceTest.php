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
use Entity\Location;
use Entity\Device;
use Entity\DeviceTag;
use Entity\DeviceState;
use Entity\User;
use Entity\Order;

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class DeviceTest extends WebTestCaseHelper
{

    public function testIndexUnautheticate()
    {

        $client = $this->createClient();
        $url = $client->loadPage('/device')
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
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);

        $this->persist($device);


        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
        $this->assertCount(1, $tr, 'Invalid number records in grid');

        $td = $tr[0]->findElements('td');

        $this->assertCount(7, $td, 'Invalid number columns in grid');
        $this->assertEquals($device->getId(), $td[0]->getText(), 'Invalid data columns id');
        $this->assertEquals('', $td[1]->getText(), 'Invalid data columns photo');
        $this->assertEquals($device->getName(), $td[2]->getText(), 'Invalid data columns name');
        $this->assertEquals($device->getSerialNumber(), $td[3]->getText(), 'Invalid data columns serial number');
        $this->assertEquals($device->getType()->getName(), $td[4]->getText(), 'Invalid data columns type');
        $this->assertEquals($device->getLocation()->getName(), $td[5]->getText(), 'Invalid data columns location');

        $actionButtons = $td[6]->findElements('a');

        $footerTr = $client->getElement('table')->getElement('tfoot')->findElements('tr');
        $addButton = $footerTr[1]->getElement('a');

        $this->assertCount(2, $actionButtons, 'Invalid number action buttons in grid');

        $this->assertEquals('Edit', $actionButtons[0]->getText(), 'Invalid label for edit button');
        $this->assertEquals('Remove', $actionButtons[1]->getText(), 'Invalid label for remove button');

        $actionButtons[0]->click();

        $this->assertEquals('/device/edit/' . $device->getId(), $client->getUrl(), 'Invalid edit url');

        $actionButtons[1]->click();

        $this->assertEquals('/device/remove/' . $device->getId(), $client->getUrl(), 'Invalid remove url');


        $addButton->click();
        $this->assertEquals('/device/add', $client->getUrl(), 'Invalid add url');

    }

    public function testRemoveUnautheticate()
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
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);

        $this->persist($device);


        $this->flush();
        $client = $this->createClient();
        $url = $client->loadPage('/device/remove/' . $device->getId())
            ->getUrl();

        $this->assertEquals('/login', $url);

    }

    public function testRemove()
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
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);

        $this->persist($device);

        $user = new User();
        $user->setEmail('owner@coderdojo.org.pl');
        $user->setFirstName('first name');
        $user->setLastName('last name');
        $user->setLocation($location);

        $this->persist($user);

        $order = new Order();
        $order->setOwner($user);
        $order->setState($em->getRepository('Entity\OrderState')->findOneById(1));
        $order->setDevice($device);

        $this->persist($order);


        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/remove/' . $device->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $panelBody = $client->getElement('.panel-body');
        $buttons = $panelBody->findElements('a');


        $this->assertCount(2, $buttons, 'Invalid number buttons');

        $this->assertEquals('Yes', $buttons[0]->getText(), 'Invalid text button YES');

        $this->assertEquals('No', $buttons[1]->getText(), 'Invalid text button NO');


        $buttons[1]->click();

        $this->assertEquals('/device', $client->getUrl(), 'Invalid url button NO.');

        $buttons[0]->click();

        $this->assertEquals('/device', $client->getUrl(), 'Invalid url button YES.');


        //check removed in database
        $this->assertCount(0, $em->getRepository('Entity\Device')->findAll());
    }


    public function testAddUnautheticate()
    {

        $client = $this->createClient();
        $url = $client->loadPage('/device/add')
            ->getUrl();

        $this->assertEquals('/login', $url);

    }

    public function testAdd()
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

        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();

        $this->assertCount(11, $fields, 'Invalid number fields');

        $fields[7]->setData('0');
        //check required fields
        $form->submit();

        $this->assertEquals('/device/add', $client->getUrl(), 'Invalid url form incorrect submit form');

        $form = $client->getElement('form');
        $fields = $form->getFields();


        $this->assertCount(11, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for dimensions');
        $this->assertEquals('Value can not empty', $fields[2]->getParent()->getElement('label')->getText(), 'Invalid error message for weight');

        $this->assertFalse($fields[3]->getParent()->hasElement('label'), 'Redundant error message for warranty expiration date');
        $this->assertFalse($fields[4]->getParent()->hasElement('label'), 'Redundant error message for price');
        $this->assertFalse($fields[5]->getParent()->hasElement('label'), 'Redundant error message for photo');
        $this->assertEquals('Value can not empty', $fields[6]->getParent()->getElement('label')->getText(), 'Invalid error message for tags');
        $this->assertEquals('Value is too small.', $fields[7]->getParent()->getElement('label')->getText(), 'Invalid error message for counts');

        $this->assertFalse($fields[8]->getParent()->hasElement('label'), 'Redundant error message for note');
        $this->assertEquals('Value can not empty', $fields[9]->getParent()->getElement('label')->getText(), 'Invalid error message for type');
        $this->assertEquals('Value can not empty', $fields[10]->getParent()->getElement('label')->getText(), 'Invalid error message for location');

        $fields[0]->setData('Name test');
        $fields[1]->setData('10x10x10');
        $fields[2]->setData('10kg');
        $fields[3]->setData('2015-01-01');
        $fields[4]->setData('20.32');
        $fields[6]->setData('tag 1,tag 2');
        $fields[7]->setData('2');
        $fields[8]->setData('Note');
        $fields[9]->setData('1');//Refill
        $fields[10]->setData($location->getId());

        $form->submit();

        $this->assertEquals('/device/add/serialNumber', $client->getUrl(), 'Invalid url form after submit');

        $form = $client->getElement('form');


        $fields = $form->getFields();

        $this->assertCount(2, $fields, 'Invalid serial number fields');

        $fields[0]->setData('serial 1');
        $form->submit();

        $form = $client->getElement('form');
        $fields = $form->getFields();

        $this->assertFalse($fields[0]->getParent()->hasElement('label'), 'Redundant error message for first serial number');
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for second serial number');

        $fields[1]->setData('serial 2');

        $form->submit();

        $this->assertEquals('/device', $client->getUrl(), 'Invalid url form after submited serial number');

        $devices = $em->getRepository('Entity\Device')->findAll();
        $this->assertCount(2, $devices, 'Invalid number devices');

        for ($i = 0; $i < count($devices); $i++) {
            $this->assertEquals('Name test', $devices[$i]->getName(), 'Invalid device name');
            $this->assertEquals('10x10x10', $devices[$i]->getDimensions(), 'Invalid device dimensions');
            $this->assertEquals('10kg', $devices[$i]->getWeight(), 'Invalid device weight');
            $this->assertEquals('2015-01-01', $devices[$i]->getWarrantyExpirationDate()->format('Y-m-d'), 'Invalid device warranty expiration date');
            $this->assertEquals(20.32, $devices[$i]->getPrice(), 'Invalid device price');
            $this->assertEquals('Note', $devices[$i]->getNote(), 'Invalid device note');
            $this->assertEquals(1, $devices[$i]->getType()->getId(), 'Invalid device type');
            $this->assertEquals($location->getId(), $devices[$i]->getLocation()->getId(), 'Invalid device location');
            $tags = $devices[$i]->getTags();
            $this->assertCount(2, $tags, 'Invalid count device tags');

            $this->assertTrue(in_array('tag 1', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 1 name');
            $this->assertTrue(in_array('tag 2', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 2 name');

        }

        $this->assertTrue(in_array('serial 1', array($devices[0]->getSerialNumber(), $devices[1]->getSerialNumber())), 'Invalid device serial number 1');
        $this->assertTrue(in_array('serial 2', array($devices[0]->getSerialNumber(), $devices[1]->getSerialNumber())), 'Invalid device serial number tag 2');

    }

    public function testEditUnautheticate()
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
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);

        $this->persist($device);


        $this->flush();

        $client = $this->createClient();
        $url = $client->loadPage('/device/edit/' . $device->getId())
            ->getUrl();

        $this->assertEquals('/login', $url);

    }

    public function testEdit()
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
        $device->setWarrantyExpirationDate(new \DateTime('2015-01-01'));
        $device->setWeight('10kg');
        $device->setPrice(12.05);
        $device->setNote('Note');
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);

        $this->persist($device);


        $this->flush();


        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/edit/' . $device->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();

        $this->assertCount(10, $fields, 'Invalid number fields');


        $this->assertEquals('Device name', $fields[0]->getData(), 'Invalid value for name');
        $this->assertEquals('10x10x10', $fields[1]->getData(), 'Invalid value for dmiesions');
        $this->assertEquals('10kg', $fields[2]->getData(), 'Invalid value for weight');
        $this->assertEquals('2015-01-01', $fields[3]->getData(), 'Invalid value for warranty expiration date');
        $this->assertEquals('12.05', $fields[4]->getData(), 'Invalid value for price');
        $this->assertEquals('', $fields[5]->getData(), 'Invalid value for photo');
        $this->assertEquals('DeviceTag name', $fields[6]->getData(), 'Invalid value for tags');
        $this->assertEquals('Device serial number', $fields[7]->getData(), 'Invalid value for serial number');
        $this->assertEquals('Note', $fields[8]->getData(), 'Invalid value for note');
        $this->assertEquals('1', $fields[9]->getData(), 'Invalid value for type');


        $fields[0]->setData('');
        $fields[1]->setData('');
        $fields[2]->setData('');
        $fields[3]->setData('');
        $fields[4]->setData('');
        $fields[6]->setData('');
        $fields[7]->setData('');
        $fields[8]->setData('');
        $fields[9]->setData('');
        $form->submit();


        $form = $client->getElement('form');
        $fields = $form->getFields();


        $this->assertCount(10, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for dimensions');
        $this->assertEquals('Value can not empty', $fields[2]->getParent()->getElement('label')->getText(), 'Invalid error message for weight');

        $this->assertFalse($fields[3]->getParent()->hasElement('label'), 'Redundant error message for warranty expiration date');
        $this->assertFalse($fields[4]->getParent()->hasElement('label'), 'Redundant error message for price');
        $this->assertFalse($fields[5]->getParent()->hasElement('label'), 'Redundant error message for photo');
        $this->assertEquals('Value can not empty', $fields[6]->getParent()->getElement('label')->getText(), 'Invalid error message for tags');
        $this->assertEquals('Value can not empty', $fields[7]->getParent()->getElement('label')->getText(), 'Invalid error message for serial number');

        $this->assertFalse($fields[8]->getParent()->hasElement('label'), 'Redundant error message for note');
        $this->assertEquals('Value can not empty', $fields[9]->getParent()->getElement('label')->getText(), 'Invalid error message for type');


        $fields[0]->setData('Name edit');
        $fields[1]->setData('2x3x4');
        $fields[2]->setData('2kg');
        $fields[3]->setData('2016-01-01');
        $fields[4]->setData('5.32');
        $fields[6]->setData('tag 1,tag 2');
        $fields[7]->setData('serial number');
        $fields[8]->setData('note edit');
        $fields[9]->setData('2');

        $form->submit();

        $this->assertEquals('/device', $client->getUrl(), 'Invalid url form after submit');

        $em->clear();
        $device = $em->getRepository('Entity\Device')->findOneBy(array('id' => $device->getId()));

        $this->assertEquals('Name edit', $device->getName(), 'Invalid device name');
        $this->assertEquals('2x3x4', $device->getDimensions(), 'Invalid device dimensions');
        $this->assertEquals('2kg', $device->getWeight(), 'Invalid device weight');
        $this->assertEquals('2016-01-01', $device->getWarrantyExpirationDate()->format('Y-m-d'), 'Invalid device warranty expiration date');
        $this->assertEquals(5.32, $device->getPrice(), 'Invalid device price');
        $this->assertEquals('note edit', $device->getNote(), 'Invalid device note');
        $this->assertEquals(2, $device->getType()->getId(), 'Invalid device type');
        $this->assertEquals($location->getId(), $device->getLocation()->getId(), 'Invalid device location');
        $tags = $device->getTags();
        $this->assertCount(2, $tags, 'Invalid count device tags');

        $this->assertTrue(in_array('tag 1', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 1 name');
        $this->assertTrue(in_array('tag 2', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 2 name');


        $this->assertEquals('serial number', $device->getSerialNumber(), 'Invalid device serial number');

    }

}