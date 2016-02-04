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
use Arbor\Component\Grid\Column;

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
     * @param \Entity\DeviceSpecimen $entity
     * @return array
     */
    public function freeConfirm($entity)
    {
        return compact('entity');
    }

    /**
     * Set free status
     *
     * @param \Entity\DeviceSpecimen $entity
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
     * View with form config for assign device
     *
     * @param \Entity\DeviceSpecimen $entity
     * @return array
     */
    public function assignConfirm($entity)
    {
        return compact('entity');
    }

    /**
     * Assign device to my location
     *
     * @param \Entity\DeviceSpecimen $entity
     * @return Response
     */
    public function assign($entity)
    {
        if ($entity->getUser()->getId() != $this->getUser()->getId()) {
            throw new YouAreNotOwnerException();
        }

        $entity->setUser(null);
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
        $builder = $this->getService('grid')->create($this->getRequest());
        $builder->setFormatter(new BasicGridFormatter('device/my',false));
        $builder->setDataManager(new DqlDataManager(
            $this->getDoctrine()->getEntityManager()
            ,'SELECT i.id,d.name,i.serialNumber,t.name as type , s.name state,s.id as stateId FROM Entity\DeviceSpecimen i JOIN i.state s JOIN i.device d JOIN d.type t WHERE i.user=:user'
            ,'SELECT count(i) as c FROM Entity\DeviceSpecimen i WHERE i.user=:user'
            ,array('user'=>$this->getUser())
        ));

        $builder->setLimit(10);

        $builder->addColumn(new Column('id','#'));
        $builder->addColumn(new Column('name','Name'));
        $builder->addColumn(new Column('serialNumber','Serial number'));
        $builder->addColumn(new Column('type','Type'));
        $builder->addColumn(new Column('state','State'));

        $freeColumn=new FreeColumnFormatter('device/my');
        $freeColumn->addButton('assign','Assign to location');
        $builder->addColumn(new Column(array('id','stateId'),'Action',$freeColumn,array()));
        return $builder;
    }

}