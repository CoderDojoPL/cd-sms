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
use Common\BasicGridFormatter;
use Common\ActionColumnFormatter;
use Common\BasicFormFormatter;
use Arbor\Component\Form\TextField;
use Arbor\Component\Form\NumberField;
use Arbor\Component\Form\SelectField;
use Arbor\Component\Form\CheckboxField;
use Arbor\Component\Form\FileField;
use Common\FreeColumnFormatter;
use Common\ImageColumnFormatter;
use Doctrine\Common\Version;
use Library\Doctrine\Form\DoctrineDesigner;
use Arbor\Provider\Response;
use Common\DqlDataManager;
use Exception\OrderNotBusyException;

/**
 * Class DeviceMy
 *
 * @package Controller
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class DeviceMy extends Controller
{

    /**
     * Prepare data for Index view
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
     * Method for create PHP confirm free screen
     *
     * @param \Entity\Device $entity
     * @return array
     */
    public function freeConfirm($entity)
    {
        return compact('entity');
    }

    /**
     * Set free status
     *
     * @param \Entity\Device $entity
     * @return Response
     */
    public function free($entity)
    {
        if ($entity->getUser()->getId() != $this->getUser()->getId()) {
            throw new YouAreNotOwnerException();
        }

        if ($entity->getState()->getId() != 2) {
            throw new OrderNotBusyException();
        }

        $entity->setState($this->cast('Mapper\DeviceState',1));
        $this->flush();

        $response = new Response();
        $response->redirect('/device/my');
        return $response;

    }


    /**
     * Creates grid for display devices list
     *
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createGrid()
    {
        $builder = $this->getService('grid')->create();
        $builder->setFormatter(new BasicGridFormatter('device'));
        $builder->setDataManager(new DqlDataManager(
            $this->getDoctrine()->getEntityManager()
            ,'SELECT i.id,i.name,i.serialNumber,t.name as type , s.name state,s.id as stateId FROM Entity\Device i JOIN i.state s  JOIN i.type t WHERE i.user=:user ORDER BY i.id'
            ,'SELECT count(i) as c FROM Entity\Device i WHERE i.user=:user'
            ,array('user'=>$this->getUser())
        ));

        $builder->setLimit(10);
        $query = $this->getRequest()->getQuery();
        if (!isset($query['page'])) {
            $query['page'] = 1;
        }
        $builder->setPage($query['page']);

        $builder->addColumn('#', 'id');
        $builder->addColumn('Name', 'name');
        $builder->addColumn('Serial number', 'serialNumber');
        $builder->addColumn('Type', 'type');
        $builder->addColumn('State', 'state');
        $builder->addColumn('Action', array('id','stateId'),new FreeColumnFormatter('device/my'));
        return $builder;
    }

}