<?php
namespace Test;
require __DIR__.'/../arbor/core/WebTestCase.php';

use Arbor\Core\WebTestCase;
use Entity\Location;
use Entity\Device;
use Entity\DeviceTag;
use Entity\DeviceState;

class DeviceTest extends WebTestCase{	

	protected function setUp(){//FIXME configure migrate and execute command
		$em=$this->getService('doctrine')->getEntityManager();

		foreach($em->getRepository('Entity\Device')->findAll() as $entity){
			$entity->getTags()->clear();
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\DeviceTag')->findAll() as $entity){
			$em->remove($entity);
		}

		foreach($em->getRepository('Entity\Location')->findAll() as $entity){
			$em->remove($entity);
		}

		$em->flush();
    }

	public function testIndexUnautheticate(){

		$request=$this->createRequest('/device');
		$response=$request->execute();
		$this->assertEquals(302,$response->getStatusCode(),'Invalid status code.');

	}

	public function testIndexAuthenticate(){

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
		$em->persist($location);



		$deviceTag=new DeviceTag();
		$deviceTag->setName('DeviceTag name');
		$em->persist($deviceTag);

		$device=new Device();
		$device->setName('Device name');
		$device->setPhoto('Device.photo.jpg');
		$device->getTags()->add($deviceTag);
		$device->setType($em->getRepository('Entity\DeviceType')->findOneById(1));
		$device->setDimensions('10x10x10');
		$device->setWeight('10kg');
		$device->setSerialNumber('Device serial number');
		$device->setState($em->getRepository('Entity\DeviceState')->findOneById(1));
		$device->setLocation($location);

		$em->persist($device);

		$em->flush();

		$request=$this->createRequest('/device');
		$request->getSession()->set('user.id',1);
		$response=$request->execute();
		$this->assertEquals(200,$response->getStatusCode(),'Invalid status code.');
		preg_match_all('/<tr><td>([0-9]+)<\/td><td><\/td><td>Device name<\/td><td>Device serial number<\/td><td>Refill<\/td><td>Location name<\/td><td><a type="button" href="\/device\/edit\/\1" class="btn btn-default btn-xs">Edit<\/a><a type="button" href="\/device\/remove\/\1" class="btn btn-default btn-xs">Remove<\/a><\/td>/',$response->getContent(),$match);
		$this->assertCount(1,$match[0],'Invalid number records in grid.');
	}

	public function testAddUnautheticate(){

		$request=$this->createRequest('/device/add');
		$response=$request->execute();
		$this->assertEquals(302,$response->getStatusCode(),'Invalid status code.');

	}

	public function testAddAuthenticate(){

		$em=$this->getService('doctrine')->getEntityManager();

		$request=$this->createRequest('/device/add');
		$request->getSession()->set('user.id',1);
		$response=$request->execute();
		$this->assertEquals(200,$response->getStatusCode(),'Invalid status code.');
	}

	public function testAddRequiredField(){

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
		$em->persist($location);
		$em->flush();

		$request=$this->createRequest('/device/add');
		$request->getSession()->set('user.id',1);
		$request->setType('POST');
		$response=$request->execute();
		$this->assertEquals(200,$response->getStatusCode(),'Invalid status code.');


		$fields=array();
		$fields[]=array('Name','Value can not empty');
		$fields[]=array('Dimensions','Value can not empty');
		$fields[]=array('Weight','Value can not empty');
		$fields[]=array('Type','Value can not empty');
		$fields[]=array('Location','Value can not empty');
		$fields[]=array('Tags','Value can not empty');

		preg_match_all('/<div class="form-group has-error has-required">.*?<label class="col-sm-3 control-label" for=".*?">(.*?)<\/label>.*?<div class="col-sm-6">.*?<label for="name" class="error">(.*?)<\/label>.*?<\/div>.*?<\/div>/s'
			,$response->getContent(),$match);

		for($i=0; $i<count($fields); $i++){
			$this->assertEquals($fields[$i][0],$match[1][$i],'Invalid field label.');
			$this->assertEquals($fields[$i][1],$match[2][$i],'Invalid field error message.');
		}

	}

	public function testAddSuccess(){

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
		$em->persist($location);
		$em->flush();

		$data=array(
			'name'=>'Device name'
			,'dimensions'=>'10x10x10'
			,'weight'=>'10kg'
			,'type'=>'1'
			,'location'=>$location->getId()
			,'tags'=>'tag 1, tag2'
			,'count'=>'2'
			);
		$request=$this->createRequest('/device/add');
		$request->getSession()->set('user.id',1);
		$request->setType('POST');
		$request->setData($data);
		$response=$request->execute();
		$this->assertEquals(302,$response->getStatusCode(),'Invalid status code.');
		$this->assertEquals('/device/add/serialNumber',$response->getHeader('Location'),'Invalid url redirect');

		$deviceInfo=$request->getSession()->get('device.info');

		$this->assertArraySubset($data,$deviceInfo,'Invalid device.info');
	}

}