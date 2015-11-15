<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-09-13
 * Time: 20:15
 */

namespace Test;
require_once __DIR__ . '/../common/WebTestCaseHelper.php';


use Common\WebTestCaseHelper;
use Entity\DeviceType;

/**
 * @package Test
 * @author Sławek Nowak (s.nowak@coderdojo.org.pl)
 */
class DeviceTypeTest extends WebTestCaseHelper
{
    //First role is from migrate script
    public function testIndexUnautheticate()
    {
        $client = $this->createClient();
        $url = $client->loadPage('/devicetype')->getUrl();
        $this->assertEquals('/login', $url);
    }


    public function testIndex()
    {
        $em = $this->getService('doctrine')->getEntityManager();
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/devicetype');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
        $this->assertCount(2, $tr, 'Invalid number records in grid');

        $devicetype = $this->getService('doctrine')->getRepository('Entity\DeviceType')->findOneBy(array('id' => 1));

        $td = $tr[0]->findElements('td');
        $this->assertCount(4, $td, 'Invalid number columns in grid');
        $this->assertEquals($devicetype->getId(), $td[0]->getText(), 'Invalid data columns id');
        $this->assertEquals($devicetype->getName(), $td[1]->getText(), 'Invalid data columns name');
        $this->assertEquals($devicetype->getSymbolPrefix(), $td[2]->getText(), 'Invalid data columns symbol');

        $actionButtons = $td[3]->findElements('a');
        $footerTr = $client->getElement('table')->getElement('tfoot')->findElements('tr');
        $addButton = $footerTr[1]->getElement('a');
        $this->assertCount(1, $actionButtons, 'Invalid number action buttons in grid');
        $this->assertEquals('Edit', $actionButtons[0]->getText(), 'Invalid label for edit button');
        $actionButtons[0]->click();
        $this->assertEquals('/devicetype/edit/' . $devicetype->getId(), $client->getUrl(), 'Invalid edit url');

        $addButton->click();
        $this->assertEquals('/devicetype/add', $client->getUrl(), 'Invalid add url');
    }

    public function testAddUnautheticate()
    {
        $client = $this->createClient();
        $url = $client->loadPage('/devicetype/add')->getUrl();
        $this->assertEquals('/login', $url);
    }

    public function testAdd()
    {

        $em = $this->getService('doctrine')->getEntityManager();
        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/devicetype/add');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(2, $fields, 'Invalid number fields');

        $form->submit();
        $this->assertEquals('/devicetype/add', $client->getUrl(), 'Invalid url form incorrect submit form');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(2, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');//name
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for prefix');//prefix

        $fields[0]->setData('Name test');
        $fields[1]->setData('test');
        $form->submit();
        $this->assertEquals('/devicetype', $client->getUrl(), 'Invalid url form after submit');

        $devicetypes = $em->getRepository('Entity\DeviceType')->findAll();
        $this->assertCount(3, $devicetypes, 'Invalid number devices');

        $i = count($devicetypes) - 1;
        $this->assertEquals('Name test', $devicetypes[$i]->getName(), 'Invalid device type name');
        $this->assertEquals('test', $devicetypes[$i]->getSymbolPrefix(), 'Invalid device type prefix');
    }

    public function testEditUnautheticate()
    {
        $client = $this->createClient();
        $url = $client->loadPage('/devicetype/edit/1')->getUrl();
        $this->assertEquals('/login', $url);
    }

    public function testEdit()
    {

        $em = $this->getService('doctrine')->getEntityManager();
        $deviceType = new DeviceType();
        $deviceType->setName('Test Name');
        $deviceType->setSymbolPrefix('Test');
        $this->persist($deviceType);
        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/devicetype/edit/' . $deviceType->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(1, $fields, 'Invalid number fields');
        $this->assertEquals($deviceType->getName(), $fields[0]->getData(), 'Invalid value for name');

        $fields[0]->setData('');
        $form->submit();
        $this->assertEquals('/devicetype/edit/' . $deviceType->getId(), $client->getUrl(), 'Invalid url form incorrect submit form');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(1, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');//name

        $fields[0]->setData('New name');
        $form->submit();
        $this->assertEquals('/devicetype', $client->getUrl(), 'Invalid url form after submit');
        $em->clear();
        $deviceType = $em->getRepository('Entity\DeviceType')->findOneBy(array('id' => $deviceType->getId()));
        $this->assertEquals('New name', $deviceType->getName(), 'Invalid device name');
    }
}