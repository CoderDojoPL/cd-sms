<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Controller;

use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Common\ActionColumnFormatter;
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Library\Doctrine\Form\DoctrineDesigner;

/**
 * Class User
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class User extends Controller
{

	/**
	 * Shows list of users
	 *
	 * @return array
	 */
	public function index()
	{
		$grid = $this->createGrid();
		return compact('grid');
	}

	/**
	 * Helper for creating grid with users
	 *
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createGrid()
	{
		$builder = $this->getService('grid')->create();
		$builder->setFormatter(new BasicGridFormatter('user', false));//prefix
		$builder->setDataManager(new BasicDataManager(
			$this->getDoctrine()->getEntityManager()
			, 'Entity\User'
		));

		$builder->setLimit(10);
		$query = $this->getRequest()->getQuery();
		if (!isset($query['page'])) {
			$query['page'] = 1;
		}
		$builder->setPage($query['page']);

		$builder->addColumn('#', 'id');
		$builder->addColumn('Email', 'email');
		$builder->addColumn('First Name', 'firstName');
		$builder->addColumn('Last Name', 'lastName');
		$builder->addColumn('Location', 'location');
//        $builder->render();

		$builder->addColumn('Action', 'id', new ActionColumnFormatter('user', array('edit')));
		return $builder;
	}

	/**
	 * Saves user entity to database after edit
	 *
	 * @param User $entity
	 * @return Response|array
	 */
	public function edit($entity)
	{
		$form = $this->createForm($entity);

		if ($form->isValid()) {
			$data = $form->getData();

			$this->setEntity($entity, $data);
			$this->flush();

			$response = new Response();
			$response->redirect('/user');

			return $response;

		}
		return compact('form');
	}

	/**
	 * Create form for edit user
	 *
	 * @param null|User $entity
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createForm($entity = null)
	{
		$builder = $this->getService('form')->create();
		$builder->setValidatorService($this->getService('validator'));
		$builder->setFormatter(new BasicFormFormatter());
		$builder->setSubmitTags(array('cancel' => true));
		$builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\User'));

        $emailField=$builder->getField('email');
        $emailField->setTag('readonly',true);
        $emailField->setRequired(false);

		if ($entity) {
			$helper = $this->getService('form.helper');
			$data = $helper->entityToArray($entity);
			$builder->setData($data);
		}
		$builder->submit($this->getRequest());
		$builder->render();

		return $builder;
	}

	/**
	 * Setting entity from form data
	 *
	 * @param \Entity\User $entity
	 * @param $data
	 */
	private function setEntity($entity, $data)
	{
		$entity->setFirstName($data['firstName']);
		$entity->setLastName($data['lastName']);
		$entity->setLocation($this->cast('Mapper\Location', $data['location']));
		$this->persist($entity);
	}

}