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
use Entity\Functionality;
use Entity\Role;


/**
 * @package Test
 * @author Sławek Nowak (s.nowak@coderdojo.org.pl)
 */
class RoleTest extends WebTestCaseHelper
{
    //First role is from migrate script
    public function testIndexUnautheticate()
    {
        $client = $this->createClient();
        $url = $client->loadPage('/role')->getUrl();
        $this->assertEquals('/login', $url);
    }

    public function testIndex()
    {
        $em = $this->getService('doctrine')->getEntityManager();

        $role = new Role();
        $role->setName('Test role');

        $role->getFunctionalities()->add($em->getRepository('Entity\Functionality')->findOneById(1));
        $this->persist($role);
        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/role');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $tr = $client->getElement('table')->getElement('tbody')->findElements('tr');
        $this->assertCount(3, $tr, 'Invalid number records in grid');
        //one role from migrate, one created on test, one TR is a header

        $td = $tr[2]->findElements('td');

        $this->assertCount(3, $td, 'Invalid number columns in grid');
        $this->assertEquals($role->getId(), $td[0]->getText(), 'Invalid data column id');
        $this->assertEquals($role->getName(), $td[1]->getText(), 'Invalid data column name');

        $footerTr = $client->getElement('table')->getElement('tfoot')->findElements('tr');
        $addButton = $footerTr[1]->getElement('a');

        $actionButtons = $td[2]->findElements('a');
        $this->assertCount(1, $actionButtons, 'Invalid number action buttons in grid');
        $this->assertEquals('Edit', $actionButtons[0]->getText(), 'Invalid label for edit button');

        $actionButtons[0]->click();
        $this->assertEquals('/role/edit/' . $role->getId(), $client->getUrl(), 'Invalid edit url');

        $addButton->click();
        $this->assertEquals('/role/add', $client->getUrl(), 'Invalid add url');
    }

    public function testAddUnautheticate()
    {
        $client = $this->createClient();
        $url = $client->loadPage('/role/add')->getUrl();
        $this->assertEquals('/login', $url);
    }

    public function testAdd()
    {
        $em = $this->getService('doctrine')->getEntityManager();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/role/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();

        $this->assertCount(2, $fields, 'Invalid number fields');
        $form->submit();//check required

        $this->assertEquals('/role/add', $client->getUrl(), 'Invalid url form incorrect submit form');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(2, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for functionalities');

        $fields[0]->setData('Name test');
        $fields[1]->setData(array(1));

        $form->submit();

        $this->assertEquals('/role', $client->getUrl(), 'Invalid url form after submit');

        $roles = $em->getRepository('Entity\Role')->findAll();
        $this->assertCount(3, $roles, 'Invalid number roles');

        $this->assertEquals('Name test', $roles[2]->getName(), 'Invalid role name');
        $this->assertEquals(1, $roles[2]->getFunctionalities()->get(0)->getId(), 'Invalid role functionalities');
    }

    public function testEditUnautheticate()
    {
        $em = $this->getService('doctrine')->getEntityManager();
        $role = new Role();
        $role->setName('Test role');
        $role->getFunctionalities()->add($em->getRepository('Entity\Functionality')->findOneById(1));
        $this->persist($role);
        $this->flush();
        $client = $this->createClient();
        $url = $client->loadPage('/role/edit/' . $role->getId())->getUrl();

        $this->assertEquals('/login', $url);
    }

    public function testEdit()
    {

        $em = $this->getService('doctrine')->getEntityManager();

        $role = new Role();
        $role->setName('Test role');
        $role->getFunctionalities()->add($em->getRepository('Entity\Functionality')->findOneById(1));
        $this->persist($role);
        $this->flush();

        $session = $this->createSession();
        $session->set('user.id', $this->user->getId());

        $client = $this->createClient($session);
        $client->loadPage('/role/edit/' . $role->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Invalid status code.');

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(2, $fields, 'Invalid number fields');
        $this->assertEquals('Test role', $fields[0]->getData(), 'Invalid role name');
        $this->assertEquals(1, $fields[1]->getData(), 'Invalid role functionalities');
        $fields[0]->setData('');
        $fields[1]->setData('');
        $form->submit();

        $form = $client->getElement('form');
        $fields = $form->getFields();
        $this->assertCount(2, $fields, 'Invalid number fields');
        $this->assertEquals('Value can not empty', $fields[0]->getParent()->getElement('label')->getText(), 'Invalid error message for name');
        $this->assertEquals('Value can not empty', $fields[1]->getParent()->getElement('label')->getText(), 'Invalid error message for dimensions');
        $fields[0]->setData('Name test');
        $fields[1]->setData(array(9));

        $form->submit();
        $this->assertEquals('/role', $client->getUrl(), 'Invalid url form after submit');

        $em->clear();
        $newRole = $em->getRepository('Entity\Role')->findOneBy(array('id' => $role->getId()));
        $this->assertEquals('Name test', $newRole->getName(), 'Invalid role name');
        $this->assertEquals(9, $newRole->getFunctionalities()->get(0)->getId(), 'Invalid role functionalities');

    }
}