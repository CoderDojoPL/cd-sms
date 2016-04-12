<?php
namespace Controller;

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Common\ActionColumnFormatter;
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Entity\DeviceSpecimen;
use Exception\OrderBelongToUserException;
use Exception\OrderWrongLocationException;
use Arbor\Component\Form\SelectField;
use Arbor\Exception\OrderNotFetchedException;
use Arbor\Component\Grid\Column;
use Common\DqlDataManager;
use Common\DateColumnFormatter;
/**
 * Class Order
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Order extends Controller
{
	/**
	 * Preparing data for index view
	 *
	 * @return array
	 */
	public function index()
	{
		$grid = $this->createGrid();
		return compact('grid');
	}

	/**
	 * Creates grid view
	 *
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createGrid()
	{
		$builder = $this->getService('grid')->create($this->getRequest());
		$builder->setFormatter(new BasicGridFormatter('order',$this->isAllow(11)));

        $builder->setDataManager(new DqlDataManager(
            $this->getDoctrine()->getEntityManager()
            ,'SELECT i.id,d.name as device,ds.serialNumber as specimen ,concat(o.firstName,concat(\' \',o.lastName)) as owner,s.name as state,i.createdAt FROM Entity\Order i JOIN i.deviceSpecimen ds JOIN i.owner o JOIN i.state s JOIN ds.device d'
            ,'SELECT count(i) as c FROM Entity\Order i'
        ));

		$builder->setLimit(10);

		$builder->addColumn(new Column('id','#'));
		$builder->addColumn(new Column('device','Device'));
		$builder->addColumn(new Column('specimen','Specimen'));
		$builder->addColumn(new Column('owner','Owner'));
		$builder->addColumn(new Column('state','State'));
		$builder->addColumn(new Column('createdAt','Date',new DateColumnFormatter()));

		$builder->addColumn(new Column('id','Action', new ActionColumnFormatter('order', array('show')),array()));
		return $builder;
	}

	/**
	 * Save new order to database
	 *
	 * @return Response|array
	 */
	public function add()
	{
		$form = $this->createForm();

		if ($form->isValid()) {
			$data = $form->getData();
			$this->getRequest()->getSession()->set('order.info', $data);

			$response = new Response();
			$response->redirect('/order/add/addapply');

			return $response;

		}
		return compact('form');
	}

	/**
	 * Form with contact data to current owner
	 *
	 * @return Response|array
	 */
	public function addApply()
	{
		$data = $this->getRequest()->getSession()->get('order.info');

		$deviceSpecimen = $this->cast('Mapper\DeviceSpecimen', $data['device']);
		/* @var $deviceSpecimen DeviceSpecimen */
		$location = $deviceSpecimen->getLocation();
		/* @var $location Location */

		$form = $this->createApplyForm();

		if ($form->isValid()) {

			if ($deviceSpecimen->getLocation() == $this->getUser()->getLocation()){
				throw new OrderWrongLocationException();
			}

			$entity = new \Entity\Order();
			$entity->setOwner($this->getUser());
			$entity->setDeviceSpecimen($deviceSpecimen);
			$entity->setState($this->cast('Mapper\OrderState', 1));
			$this->persist($entity);

			$deviceSpecimen->setState($this->cast('Mapper\DeviceState', 2));
			$this->flush();

			$mailBody = $this->getService('twig')->render('Mail/NewOrderForDevice.twig', ['user' => $this->getUser(), 'device' => $deviceSpecimen]);
			$this->send($location->getEmail(), 'SMS - New order for device '.$deviceSpecimen->getDevice()->getName(), $mailBody);

			$response = new Response();
			$response->redirect('/order');
			$this->getRequest()->getSession()->remove('order.info');
			return $response;
		}
		return compact('form', 'deviceSpecimen', 'location');
	}


	/**
	 * Method for fetch order to realization
	 *
	 * @param \Entity\Order $entity
	 * @return mixed
	 * @throws OrderAllreadyFetchedException
	 */
	public function fetch($entity)
	{
		if ($entity->getState()->getId() != 1) {
			throw new OrderAllreadyFetchedException();
		}

		if ($entity->getOwner()->getId() == $this->getUser()->getId()) {
			throw new OrderBelongToUserException();
		}

		$entity->setPerformer($this->getUser());
		$entity->setState($this->cast('Mapper\OrderState', 2));
		$entity->setFetchedAt(new \DateTime());

		$this->flush();

		return $this->redirect('/order/show/' . $entity->getId());
	}

	/**
	 * Close order workflow
	 *
	 * @param \Entity\Order $entity
	 * @param string $bind
	 * @return mixed
	 * @throws OrderNotFetchedException
	 * @throws YouAreNotOwnerException
	 */
	public function close($entity,$bind)
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
		$entity->getDeviceSpecimen()->setLocation($entity->getOwner()->getLocation());
		$expirationDate=new \DateTime();
		$expirationDate->add(new \DateInterval('P14D'));

		if($bind=='me'){

			$entity->getDeviceSpecimen()->setUser($entity->getOwner());
		}
		else{
			$entity->getDeviceSpecimen()->setUser(null);
		}
		$entity->getDeviceSpecimen()->setHireExpirationDate($expirationDate);

		$entity->getDeviceSpecimen()->setState($this->cast('Mapper\DeviceState', 2));

		$this->flush();

		return $this->redirect('/order/show/' . $entity->getId());
	}

	/**
	 * Shows order details
	 *
	 * @param \Entity\Order $entity
	 * @return array
	 */
	public function show($entity)
	{
		$isOwner = $entity->getOwner()->getId() == $this->getUser()->getId();

		return compact('entity', 'isOwner');
	}

	/**
	 * Form builed
	 *
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createFormBuilder()
	{
		$builder = $this->getService('form')->create();
		$builder->setValidatorService($this->getService('validator'));
		$builder->setFormatter(new BasicFormFormatter());
		$builder->setSubmitTags(array('cancel' => true));
		return $builder;
	}

	/**
	 * Creates apply form
	 *
	 * @param null $entity
	 * @return mixed
	 */
	private function createApplyForm($entity = null)
	{
		$builder = $this->createFormBuilder();

		$builder->submit($this->getRequest());

		return $builder;
	}

	/**
	 * Create choose device form
	 *
	 * @param null| \Entity\Order $entity
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createForm($entity = null)
	{
		$builder = $this->createFormBuilder();
		$helper = $this->getService('form.helper');
		/* @var $helper \Service\FormHelper */

		$query = $this->getDoctrine()->getEntityManager()->createQuery('SELECT ds.id as id,concat(d.name,concat(\' (\',concat(ds.serialNumber,\')\')) as name FROM Entity\DeviceSpecimen ds JOIN ds.device d  WHERE ds.state = 1 and ds.location != :id');
		$query->setParameter('id',$this->getUser()->getLocation());

		$devices = $query->getResult();
		$builder->addField(new SelectField(array(
				'name' => 'device'
			, 'label' => 'Device'
			, 'required' => true
			, 'collection' => $helper->arrayToCollection(
					$devices, array(array('', 'Select...'))
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