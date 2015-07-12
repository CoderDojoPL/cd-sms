<?php
namespace Controller;


use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Common\ActionColumnFormatter;
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Library\Doctrine\Form\DoctrineDesigner;
use Arbor\Component\Form\SelectField;

class Order extends Controller
{
	public function index()
	{
		$grid = $this->createGrid();
		return compact('grid');
	}

	private function createGrid()
	{
		$builder = $this->getService('grid')->create();
		$builder->setFormatter(new BasicGridFormatter('order'));//prefix
		$builder->setDataManager(new BasicDataManager(
			$this->getDoctrine()->getEntityManager()
			, 'Entity\Order'
		));

		$builder->setLimit(10);
		$query = $this->getRequest()->getQuery();
		if (!isset($query['page'])) {
			$query['page'] = 1;
		}
		$builder->setPage($query['page']);

		$builder->addColumn('#', 'id');
		$builder->addColumn('Device', 'device');
		$builder->addColumn('Owner', 'owner');
		$builder->addColumn('Date', 'createdAt');

//        $builder->addColumn('Serial number','serialNumber');
//        $builder->addColumn('Type','type');
		// $builder->addColumn('Action', 'id', new ActionColumnFormatter('location', array('edit', 'remove')));
		return $builder;
	}

	public function add()
	{
		$form = $this->createForm();

		if ($form->isValid()) {
			$data = $form->getData();

			$entity = new \Entity\Order();
			$entity->setOwner($this->getUser());
			$entity->setDevice($this->cast('Mapper\Device',$data['device']));
			$entity->setState($this->cast('Mapper\OrderState',1));
			$this->persist($entity);
			$this->flush();

			$response = new Response();
			$response->redirect('/order');

			return $response;

		}
		return compact('form');
	}

	public function edit($entity)
	{
		$form = $this->createForm($entity);

		if ($form->isValid()) {
			$data = $form->getData();

			if($entity->getState()->getId()==1){
				$entity->setPerformer($this->cast('Mapper\User',$this->getUser()));                
			}

			$entity->setState($this->cast('Mapper\OrderState',1));

			$this->flush();

			$response = new Response();
			$response->redirect('/order');

			return $response;

		}
		return compact('form');
	}

	private function createForm($entity = null)
	{
		$builder = $this->getService('form')->create();
		$helper=$this->getService('form.helper');
		$builder->setValidatorService($this->getService('validator'));
		$builder->setFormatter(new BasicFormFormatter());
		$builder->setSubmitTags(array('cancel' => true));
		$builder->addField(new SelectField(array(
			'name'=>'device'
			,'label'=>'Device'
			,'required'=>true
			,'collection'=>$helper->entityToCollection(
				$this->find('Device',array(
					'state'=>$this->findOne('DeviceState',array(
						'id'=>1
						)))
				)
				,array(array('','Select...'))
			))
		));

		if($entity){
			$data=$helper->entityToArray($entity);
			$builder->setData($data);
		}

		$builder->submit($this->getRequest());
		$builder->render();

		return $builder;
	}

}