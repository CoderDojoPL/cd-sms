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
use Entity\DeviceSpecimen;
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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);
        $device->setSymbol('REF1');
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

        $this->assertCount(8, $td, 'Invalid number columns in grid');
        $this->assertEquals($device->getId(), $td[0]->getText(), 'Invalid data columns id');
        $this->assertEquals('', $td[1]->getText(), 'Invalid data columns photo');
        $this->assertEquals($device->getName(), $td[2]->getText(), 'Invalid data columns name');
        $this->assertEquals($device->getSerialNumber(), $td[3]->getText(), 'Invalid data columns serial number');
        $this->assertEquals($device->getType()->getName(), $td[4]->getText(), 'Invalid data columns type');
        $this->assertEquals($device->getSymbol(), $td[5]->getText(), 'Invalid data columns symbol');
        $this->assertEquals($device->getLocation()->getName(), $td[6]->getText(), 'Invalid data columns location');

        $actionButtons = $td[7]->findElements('a');

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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);
        $device->setSymbol('REF1');

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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);
        $device->setSymbol('REF1');

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

        list($location)=$this->prepareData();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/add');

        $this->assertUrl($client,'/device/add');

        $form = $client->getElement('form');
        $fields = $form->getFields(true);

        $this->assertCount(11, $fields, 'Invalid number fields');

        $fields['count']->setData('0');
        //check required fields
        $form->submit();

        $this->assertUrl($client,'/device/add');

        $form = $client->getElement('form');
        $fields = $form->getFields(true);

        $this->assertCount(11, $fields, 'Invalid number fields');

        $this->assertFields($fields,array(
            'name'=>'Value can not empty'
            ,'tags'=>'Value can not empty'
            ,'count'=>'Value is too small.'
            ,'warrantyExpirationDate'=>null
            ,'purchaseDate'=>null
            ,'price'=>null
            ,'photo'=>null
            ,'note'=>null
            ,'type'=>'Value can not empty'
            ,'location'=>'Value can not empty'
            ,'user'=>null
        ));

        $fields['name']->setData('Name test');
        $fields['tags']->setData('tag 1,tag 2');
        $fields['count']->setData('0');
        $fields['warrantyExpirationDate']->setData('2015-01-01');
        $fields['purchaseDate']->setData('2014-01-01');
        $fields['price']->setData('20.32');
        $fields['note']->setData('Note');
        $fields['type']->setData('1');//Refill
        $fields['location']->setData($location->getId());

        $form->submit();
        //count = 0
        $this->assertUrl($client,'/device/add');

        $form = $client->getElement('form');
        $fields = $form->getFields(true);
        $this->assertFields($fields,array('count'=>'Value is too small.'));

        $fields['count']->setData('2');
        $form->submit();
        //all data oK

        $this->assertUrl($client,'/device/add/serialNumber');

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

        $this->assertUrl($client,'/device');

        $devices = $em->getRepository('Entity\Device')->findAll();
        $this->assertCount(1, $devices, 'Invalid number devices');

        $device=$devices[0];
        $this->assertEquals('Name test', $device->getName(), 'Invalid device name');
        $this->assertEquals(20.32, $device->getPrice(), 'Invalid device price');
        $this->assertEquals('Note', $device->getNote(), 'Invalid device note');
        $this->assertEquals(1, $device->getType()->getId(), 'Invalid device type');
        $tags = $device->getTags();
        $this->assertCount(2, $tags, 'Invalid count device tags');

        $this->assertTrue(in_array('tag 1', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 1 name');
        $this->assertTrue(in_array('tag 2', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 2 name');


        $deviceSpecimens = $em->getRepository('Entity\DeviceSpecimen')->findAll();
        $this->assertCount(2, $deviceSpecimens, 'Invalid number device specimens');
        for($i=0; $i<count($deviceSpecimens); $i++){
            $this->assertEquals('2015-01-01', $deviceSpecimens[$i]->getWarrantyExpirationDate()->format('Y-m-d'), 'Invalid device warranty expiration date');
            $this->assertEquals('2014-01-01', $deviceSpecimens[$i]->getPurchaseDate()->format('Y-m-d'), 'Invalid device purchase date');
            $this->assertEquals($location->getId(), $deviceSpecimens[$i]->getLocation()->getId(), 'Invalid device location');
            $this->assertEquals('REF'.($i+1), $deviceSpecimens[$i]->getSymbol(), 'Invalid device symbol');

        }

        $this->assertTrue(in_array('serial 1', array($deviceSpecimens[0]->getSerialNumber(), $deviceSpecimens[1]->getSerialNumber())), 'Invalid device serial number 1');
        $this->assertTrue(in_array('serial 2', array($deviceSpecimens[0]->getSerialNumber(), $deviceSpecimens[1]->getSerialNumber())), 'Invalid device serial number tag 2');


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
        $device->setSerialNumber('Device serial number');
        $device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $device->setLocation($location);
        $device->setSymbol('REF1');

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
        //prepare data
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
        $device->setNote('Note');
        $device->setPrice(12.05);
        $this->persist($device);

        $deviceSpecimen=new DeviceSpecimen();
        $deviceSpecimen->setDevice($device);
        $deviceSpecimen->setWarrantyExpirationDate(new \DateTime('2015-01-01'));
        $deviceSpecimen->setPurchaseDate(new \DateTime('2014-01-01'));
        $deviceSpecimen->setSerialNumber('Device serial number');
        $deviceSpecimen->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
        $deviceSpecimen->setLocation($location);
        $deviceSpecimen->setSymbol('REF1');
        $deviceSpecimen->setHireExpirationDate(new \DateTime());
        $this->persist($deviceSpecimen);
            
        $this->flush();


        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/device/edit/' . $device->getId());

        $this->assertUrl($client,'/device/edit/'.$device->getId());

        $form = $client->getElement('form');
        $fields = $form->getFields(true);

        $this->assertFieldsData($fields,array(
            'name'=>'Device name'
            ,'price'=>'12.05'
            ,'photo'=>''
            ,'tags'=>'DeviceTag name'
            ,'note'=>'Note'
            ));

        $fields['name']->setData('');
        $fields['price']->setData('');
        $fields['photo']->setData('');
        $fields['tags']->setData('');
        $fields['note']->setData('');
        $form->submit();


        $form = $client->getElement('form');
        $fields = $form->getFields(true);

        $this->assertFields($fields,array(
            'name'=>'Value can not empty'
            ,'tags'=>'Value can not empty'
            ,'price'=>null
            ,'photo'=>null
            ,'note'=>null
        ));

        $fields['name']->setData('Name edit');
        $fields['price']->setData('5.32');
        $fields['tags']->setData('tag 1,tag 2');
        $fields['note']->setData('note edit');

        $form->submit();

        $this->assertUrl($client,'/device');

        //check data
        $em->clear();
        $device = $em->getRepository('Entity\Device')->findOneBy(array('id' => $device->getId()));

        $this->assertEquals('Name edit', $device->getName(), 'Invalid device name');
        $this->assertEquals(5.32, $device->getPrice(), 'Invalid device price');
        $this->assertEquals('note edit', $device->getNote(), 'Invalid device note');
        $tags = $device->getTags();
        $this->assertCount(2, $tags, 'Invalid count device tags');

        $this->assertTrue(in_array('tag 1', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 1 name');
        $this->assertTrue(in_array('tag 2', array($tags[0]->getName(), $tags[1]->getName())), 'Invalid device tag 2 name');

    }

    public function prepareData(){

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
        return array($location);
    }

}