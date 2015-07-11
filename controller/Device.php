<?php

namespace Controller;
use Arbor\Core\Controller;
use Common\BasicGridFormatter;
use Common\ActionColumnFormatter;
use Common\EmptyDataManager;
use Common\BasicFormFormatter;
use Arbor\Component\Form\TextField;
use Arbor\Component\Form\NumberField;
use Arbor\Component\Form\SelectField;
use Arbor\Component\Form\CheckboxField;
use Arbor\Component\Form\FileField;
use Library\Doctrine\Form\DoctrineDesigner;
use Arbor\Provider\Response;
use Common\BasicDataManager;

class Device extends Controller{
	
	public function index(){
		$grid=$this->createGrid();
		return compact('grid');
	}

	public function add(){
		$form=$this->createForm();

		if($form->isValid()){
			$data=$form->getData();

			$this->getRequest()->getSession()->set('device.info',$data);

			$response=new Response();
			$response->redirect('/device/add/serialNumber');

			return $response;

		}
		return compact('form');
	}

	public function removeConfirm($entity){
		return compact('entity');
	}

	public function remove($entity){
		$this->getDoctrine()->getEntityManager()->remove($entity);		
		$this->flush();

		$response=new Response();
		$response->redirect('/device');
		return $response;

	}

	public function serialNumber(){
		$data=$this->getRequest()->getSession()->get('device.info');

		$form=$this->createFormBuilder();
		for($i=0; $i<$data['count'];$i++){
			$form->addField(new TextField(array(
				'name'=>'serialNumber['.$i.']'
				,'label'=>($i+1).'.'
				,'required'=>true
			)));

		}

		$form->submit($this->getRequest());

		if($form->isValid()){
			$serialNumbersData=$form->getData();
			$this->saveEntities($data,$serialNumbersData['serialNumber']);

			$response=new Response();
			$response->redirect('/device');

			return $response;

		}

		return compact('form','data');

	}

	private function saveEntities($data,$serialNumber){
		for($i=0; $i<$data['count']; $i++){
			$deviceEntity=new \Entity\Device();
			$this->saveEntity($deviceEntity,$data,$serialNumber[$i]);
		}


		$this->flush();

	}

	private function saveEntity($entity,$data,$serialNumber){
		$entity->setName($data['name']);
		$entity->setDimensions($data['dimensions']);
		$entity->setWeight($data['weight']);
		$entity->setType($this->cast('Mapper\DeviceType',$data['type']));
		$entity->setSerialNumber($serialNumber);
		$this->persist($entity);

		$tagsPart=explode(',',$data['tags']);
		$entity->getTags()->clear();
		foreach($tagsPart as $tag){
			$tag=trim($tag);
			$tagEntity=$this->findOne('DeviceTag',array('name'=>$tag));

			if($tagEntity==null){
				$tagEntity=new \Entity\DeviceTag();
				$tagEntity->setName($tag);
				$this->persist($tagEntity);
				$this->flush();

			}

			$entity->getTags()->add($tagEntity);
		}

	}

	public function edit($device){
		$form=$this->createForm($device);

		if($form->isValid()){
			$data=$form->getData();
			$this->saveEntity($device,$data,$data['serialNumber']);
			$this->flush();

			$response=new Response();
			$response->redirect('/device');
	
			return $response;

		}

		return compact('form');
	}

	private function createGrid(){
		$builder=$this->getService('grid')->create();
		$builder->setFormatter(new BasicGridFormatter('device'));
		$builder->setDataManager(new BasicDataManager(
			$this->getDoctrine()->getEntityManager()
			,'Entity\Device'
		));

		$builder->setLimit(10);
		$query=$this->getRequest()->getQuery();
		if(!isset($query['page'])){
			$query['page']=1;
		}
		$builder->setPage($query['page']);

		$builder->addColumn('#','id');
		$builder->addColumn('Name','name');
		$builder->addColumn('Serial number','serialNumber');
		$builder->addColumn('Typ','type');
		$builder->addColumn('Akcja','id',new ActionColumnFormatter('device',array('edit','remove')));
		return $builder;
	}

	private function createFormBuilder(){
		$builder=$this->getService('form')->create();
		$builder->setValidatorService($this->getService('validator'));
		$builder->setFormatter(new BasicFormFormatter());
		$builder->setSubmitTags(array('cancel'=>true));
		return $builder;
	}

	private function createForm($entity=null){
		$builder=$this->createFormBuilder();
		$builder->setDesigner(new DoctrineDesigner($this->getDoctrine(),'Entity\Device'));
		$builder->removeField('photo');
		$builder->removeField('deviceFiles');
		$builder->removeField('serialNumber');
		$builder->removeField('updatedAt');
		$builder->removeField('createdAt');
		// $builder->addField(new FileField(array(
		// 	'name'=>'photo'
		// 	,'label'=>'Photo'
		// 	)));

		$dimensionsField=$builder->getField('dimensions');
		$dimensionsField->setPattern('^([0-9]+([\.\,]{1}[0-9]{1}){0,1}x){2}[0-9]+([\.\,]{1}[0-9]{1}){0,1}$');
		$dimensionsField->setTag('placeholder','{Width}x{Height}x{Depth}');
		// $dimensionsField->setValue('1x1x1');
		$dimensionsField=$builder->getField('weight');
		$dimensionsField->setPattern('^[0-9]+([\.\,]{1}[0-9]{1}){0,1}kg$');
		$dimensionsField->setTag('placeholder','{Weight}kg');

		$builder->removeField('tags');

		$builder->addField(new TextField(array(
			'name'=>'tags'
			,'label'=>'Tags'
			,'required'=>true
			)));

		//TODO set required for photo in global configuration

		if($entity){
			$builder->addField(new TextField(array(
				'name'=>'serialNumber'
				,'label'=>'Serial number'
				,'required'=>true
				)));

			$helper=$this->getService('form.helper');
			$data=$helper->entityToArray($entity,array('Entity\DeviceTag'=>'getName'));
			$data['tags']=implode(',', $data['tags']);
			$builder->setData($data);


		}
		else{
			$builder->addField(new NumberField(array(
				'name'=>'count'
				,'label'=>'Count'
				,'required'=>true
				,'value'=>1
				,'min'=>1
				)));
		}


		$builder->submit($this->getRequest());
		$builder->render();

		return $builder;

	}

}