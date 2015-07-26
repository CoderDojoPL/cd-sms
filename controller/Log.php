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
use Exception\OrderWrongLocationException;
use Arbor\Component\Form\SelectField;
use Arbor\Exception\OrderNotFetchedException;
use Common\LogSuccessColumnFormatter;

/**
 * Class Log
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Log extends Controller
{
	/**
	 * Preparing data for index view
	 *
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
	 *
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createGrid()
	{
		$builder = $this->getService('grid')->create();
		$builder->setFormatter(new BasicGridFormatter('log'));//prefix
		$builder->setDataManager(new BasicDataManager(
			$this->getDoctrine()->getEntityManager()
			, 'Entity\Log'
		));

		$builder->setLimit(10);
		$query = $this->getRequest()->getQuery();
		if (!isset($query['page'])) {
			$query['page'] = 1;
		}
		$builder->setPage($query['page']);

		$builder->addColumn('#', 'id');
		$builder->addColumn('Action', 'action');
		$builder->addColumn('User', 'user');
		$builder->addColumn('Date', 'createdAt');
		$builder->addColumn('Success', array('isSuccess','failMessage'),new LogSuccessColumnFormatter());
		$builder->addColumn('Modified entities', 'countModifiedEntities');

		$builder->addColumn('Action', 'id', new ActionColumnFormatter('log', array('show')));
		return $builder;
	}

	/**
	 * Shows log details
	 *
	 * @param \Entity\Order $entity
	 * @return array
	 */
	public function show($entity)
	{
		$em=$this->getDoctrine()->getEntityManager();

		$query=$em->createQuery('SELECT r FROM Entity\DeviceLog r WHERE r.logLeft=:logId');
		$query->setParameter('logId',$entity->getId());

		$addDevices = $query->getResult();

		$query=$em->createQuery('SELECT r FROM Entity\DeviceLog r WHERE r.logRight=:logId and r.removed=false');
		$query->setParameter('logId',$entity->getId());

		$editDevices = $query->getResult();

		$query=$em->createQuery('SELECT r FROM Entity\DeviceLog r WHERE r.logRight=:logId and r.removed=true');
		$query->setParameter('logId',$entity->getId());

		$removeDevices = $query->getResult();

		return compact('entity','addDevices','editDevices','removeDevices');
	}

}