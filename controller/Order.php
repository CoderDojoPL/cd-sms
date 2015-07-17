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
use Arbor\Exception\OrderNotFetchedException;

/**
 * Class Order
 * @package Controller
 */
class Order extends Controller
{
	/**
	 * Preparing data for index view
	 * @return array
	 */
	public function index()
	{
		$grid = $this->createGrid();
		$grid->render();
		return compact('grid');
	}

	/**
	 * Creates grid view
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
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
		$builder->addColumn('State', 'state');
		$builder->addColumn('Date', 'createdAt');

		$builder->addColumn('Action', 'id', new ActionColumnFormatter('order', array('show')));
		return $builder;
	}

	/**
	 * Save new order to database
	 * @return Response|array
	 */
	public function add()
	{
		$form = $this->createForm();

		if ($form->isValid()) {
			$data = $form->getData();
			$device = $this->cast('Mapper\Device', $data['device']);
			$entity = new \Entity\Order();
			$entity->setOwner($this->getUser());
			$entity->setDevice($device);
			$entity->setState($this->cast('Mapper\OrderState', 1));
			$this->persist($entity);

			$device->setState($this->cast('Mapper\DeviceState', 2));
			$this->flush();

			$response = new Response();
			$response->redirect('/order');

			return $response;

		}
		return compact('form');
	}

	/**
	 * Method for fetch order to realization
	 * @param \Entity\Order $entity
	 * @return mixed
	 * @throws OrderAllreadyFetchedException
	 */
	public function fetch($entity)
	{
		if ($entity->getState()->getId() != 1) {
			throw new OrderAllreadyFetchedException();
		}

		$entity->setPerformer($this->getUser());
		$entity->setState($this->cast('Mapper\OrderState', 2));
		$entity->setFetchedAt(new \DateTime());

		$this->flush();

		return $this->redirect('/order/show/' . $entity->getId());
	}

	/**
	 * Close order workflow
	 * @param \Entity\Order $entity
	 * @return mixed
	 * @throws OrderNotFetchedException
	 * @throws YouAreNotOwnerException
	 */
	public function close($entity)
	{
		if ($entity->getState()->getId() != 2) {
			throw new OrderNotFetchedException();
		}

		if ($entity->getOwner()->getId() != $this->getUser()->getId()) {
			throw new YouAreNotOwnerException();
		}

		$entity->setState($this->cast('Mapper\OrderState', 3));
		$entity->setClosedAt(new \DateTime());

		//set new location on device
		$entity->getDevice()->setLocation($this->getUser()->getLocation());
		$entity->getDevice()->setState($this->cast('Mapper\DeviceState', 1));

		$this->flush();

		return $this->redirect('/order/show/' . $entity->getId());
	}

	/**
	 * Shows order details
	 * @param \Entity\Order $entity
	 * @return array
	 */
	public function show($entity)
	{
		$isOwner = $entity->getOwner()->getId() == $this->getUser()->getId();

		return compact('entity', 'isOwner');
	}

	/**
	 * Create form helper
	 * @param null| \Entity\Order $entity
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createForm($entity = null)
	{
		$builder = $this->getService('form')->create();
		$helper = $this->getService('form.helper');
		$builder->setValidatorService($this->getService('validator'));
		$builder->setFormatter(new BasicFormFormatter());
		$builder->setSubmitTags(array('cancel' => true));
		$builder->addField(new SelectField(array(
				'name' => 'device'
			, 'label' => 'Device'
			, 'required' => true
			, 'collection' => $helper->entityToCollection(
					$this->find('Device', array(
							'state' => $this->findOne('DeviceState', array(
								'id' => 1
							)))
					)
					, array(array('', 'Select...'))
				))
		));

		if ($entity) {
			$data = $helper->entityToArray($entity);
			$builder->setData($data);
		}

		$builder->submit($this->getRequest());
		$builder->render();

		return $builder;
	}

}