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

/**
 * @package Test
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class AuthenticateTest extends WebTestCaseHelper{	

	public function testSetLocationUnautheticate(){

		$client=$this->createClient();
		$url=$client->loadPage('/login/location')
		->getUrl();

		$this->assertEquals('/login',$url);

	}

	public function testSetLocation(){
		$em=$this->getService('doctrine')->getEntityManager();

		$user=new User();
		$user->setEmail('test@coderdojo.org.pl');
		$user->setFirstName('first name');
		$user->setLastName('last name');
		$this->persist($user);

		$this->flush();

		$location=$this->getService('doctrine')->getRepository('Entity\Location')->findOneBy(array());

		$session=$this->createSession();
		$session->set('user.id',$user->getId());

		$client=$this->createClient($session);
		$client->loadPage('/');

		$this->assertEquals('/login/location',$client->getUrl(),'invalid url');

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertCount(1,$fields,'Invalid number fields');

		$fields[0]->setData('');

		$form->submit();

		$form=$client->getElement('form');
		$fields=$form->getFields();

		$this->assertEquals('/login/location',$client->getUrl(),'Invalid url form after submited form');

		$this->assertEquals('Value can not empty',$fields[0]->getParent()->getElement('label')->getText(),'Invalid error message for location');

		$fields[0]->setData($location->getId());

		$form->submit();

		$this->assertEquals('/',$client->getUrl(),'Invalid url form after submited form');

		$em->clear();
		$users=$em->getRepository('Entity\User')->findAll();
		$this->assertCount(2,$users, 'Invalid number users');
		$user=$users[1];
		$this->assertEquals($location->getId(),$user->getLocation()->getId(),'Invalid location');

		$client->loadPage('/login/location');
		$this->assertEquals('/',$client->getUrl(),'Invalid url after set location');

	}

}