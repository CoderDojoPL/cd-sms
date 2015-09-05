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
use Entity\User;
use Entity\Location;
use Entity\Role;

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class UserTest extends WebTestCaseHelper{	

	public function testIndexUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/user')
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
		$this->persist($location);

		$user=$this->user;
		$this->flush();

		$session=$this->createSession();
		$session->set('user.id',$user->getId());

		$client=$this->createClient($session);
		$client->loadPage('/user');

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$tr=$client->getElement('table')->getElement('tbody')->findElements('tr');
		$this->assertCount(1,$tr,'Invalid number records in grid');

		$td=$tr[0]->findElements('td');

		$this->assertCount(6,$td,'Invalid number columns in grid');
		$this->assertEquals($user->getId(),$td[0]->getText(),'Invalid data columns id');
		$this->assertEquals($user->getEmail(),$td[1]->getText(),'Invalid data columns email');
		$this->assertEquals($user->getFirstName(),$td[2]->getText(),'Invalid data columns first name');
		$this->assertEquals($user->getLastName(),$td[3]->getText(),'Invalid data columns last name');
		$this->assertEquals($user->getLocation()->getName(),$td[4]->getText(),'Invalid data columns location');

		$actionButtons=$td[5]->findElements('a');

		$this->assertCount(1,$actionButtons,'Invalid number action buttons in grid');

		$this->assertEquals('Edit',$actionButtons[0]->getText(),'Invalid label for edit button');

		$actionButtons[0]->click();

		$this->assertEquals('/user/edit/'.$user->getId(), $client->getUrl(),'Invalid edit url');

	}

	public function testEditUnautheticate(){

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
		$this->persist($location);

		$user=new User();
		$user->setEmail('test@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location);
		$this->persist($user);

		$this->flush();

		$client=$this->createClient();
		$url=$client->loadPage('/user/edit/'.$user->getId())
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testEdit(){

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
		$this->persist($location1);

		$location2=new Location();
		$location2->setName('Location 2 name');
		$location2->setCity('Location 2 city');
		$location2->setStreet('Location 2 street');
		$location2->setNumber('Location 2 number');
		$location2->setApartment('Location 2 apartment');
		$location2->setPostal('02-000');
		$location2->setPhone('+28100000000');
		$location2->setEmail('email2@email.pl');
		$this->persist($location2);

		$role=new Role();
		$role->setName('Admin');
		foreach($em->getRepository('Entity\Functionality')->findAll() as $functionality){
			$role->getFunctionalities()->add($functionality);
		}

		$this->persist($role);

		$user=new User();
		$user->setEmail('test@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$user->setLocation($location1);
		$user->setRole($role);
		$this->persist($user);

		$this->flush();


		$session=$this->createSession();
		$session->set('user.id',$user->getId());

		$client=$this->createClient($session);
		$client->loadPage('/user/edit/'.$user->getId());

		$this->assertEquals(200,$client->getResponse()->getStatusCode(),'Invalid status code.');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(5,$fields,'Invalid number fields');

		$this->assertEquals('test@coderdojo.org.pl',$fields[0]->getData(),'Invalid field value for email');
		$this->assertEquals('first name',$fields[1]->getData(),'Invalid field value for first name');
		$this->assertEquals('last name',$fields[2]->getData(),'Invalid field value for last name');
		$this->assertEquals($location1->getId(),$fields[3]->getData(),'Invalid field value for location');

		$fields[0]->setData('');
		$fields[1]->setData('');
		$fields[2]->setData('');
		$fields[3]->setData('');

		$form->submit();

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertEquals('/user/edit/'.$user->getId(),$client->getUrl(),'Invalid url form after submited location');

		$this->assertCount(5,$fields,'Invalid number fields');
		$this->assertFalse($fields[0]->getParent()->hasElement('label'),'Redundant error message for email');
		$this->assertEquals('Value can not empty',$fields[1]->getParent()->getElement('label')->getText(),'Invalid error message for first name');
		$this->assertEquals('Value can not empty',$fields[2]->getParent()->getElement('label')->getText(),'Invalid error message for last name');
		$this->assertEquals('Value can not empty',$fields[3]->getParent()->getElement('label')->getText(),'Invalid error message for location');

		$fields[0]->setData('chang@coderdojo.org.pl');
		$fields[1]->setData('First name edit');
		$fields[2]->setData('Last name edit');
		$fields[3]->setData($location2->getId());
		$form->submit();

		$this->assertEquals('/user',$client->getUrl(),'Invalid url form after submited location');

		$em->clear();
		$users=$em->getRepository('Entity\User')->findAll();
		$this->assertCount(2,$users, 'Invalid number users');
		$user=$users[1];
		$this->assertEquals('test@coderdojo.org.pl',$user->getEmail(),'Invalid user email');
		$this->assertEquals('First name edit',$user->getFirstName(),'Invalid user first name');
		$this->assertEquals('Last name edit',$user->getLastName(),'Invalid user last name');
		$this->assertEquals($location2->getId(),$user->getLocation()->getId(),'Invalid user location');

	}

}