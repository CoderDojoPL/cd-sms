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
use Common\BasicDataManager;
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
 * Class DeviceType
 *
 * @package Controller
 * @author Slawek Nowak (s.nowak@coderdojo.org.pl)
 */
class DeviceType extends Controller
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
     * Creates grid for display device types list
     *
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createGrid()
    {
        $builder = $this->getService('grid')->create();
        $builder->setFormatter(new BasicGridFormatter('devicetype'));
        $builder->setDataManager(new BasicDataManager(
            $this->getDoctrine()->getEntityManager()
            , 'Entity\DeviceType'
        ));

        $builder->setLimit(10);
        $query = $this->getRequest()->getQuery();
        if (!isset($query['page'])) {
            $query['page'] = 1;
        }
        $builder->setPage($query['page']);

        $builder->addColumn('#', 'id');
        $builder->addColumn('Name', 'name');
        $builder->addColumn('Symbol', 'symbolPrefix');
        $builder->addColumn('Action', 'id', new ActionColumnFormatter('devicetype', array('edit')));
//        $builder->addColumn('Action', array('id','stateId'),new FreeColumnFormatter('device/my'));
        return $builder;
    }

    /**
     * Save new device type to database
     *
     * @return Response|array
     */
    public function add()
    {
        $form = $this->createForm();

        if ($form->isValid()) {
            $data = $form->getData();

            $entity = new \Entity\DeviceType();
            $this->setEntity($entity, $data);
            $this->flush();

            $response = new Response();
            $response->redirect('/devicetype');

            return $response;

        }
        return compact('form');
    }


    /**
     * Create form helper for add/edit device types
     *
     * @param null|\Entity\DeviceType $entity
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createForm($entity = null)
    {
        $builder = $this->getService('form')->create();
        $builder->setValidatorService($this->getService('validator'));
        $builder->setFormatter(new BasicFormFormatter());
        $builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\DeviceType'));

        $builder->removeField('current');

        if ($entity) {
            $builder->removeField('symbolPrefix');
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
     * @param \Entity\DeviceType $entity
     * @param $data
     */
    private function setEntity($entity, $data)
    {
        $entity->setName($data['name']);
        if (array_key_exists('symbolPrefix',$data))
            $entity->setSymbolPrefix($data['symbolPrefix']);
        $this->persist($entity);
    }

    /**
     * Save changes on device after edit
     *
     * @param \Entity\DeviceType $deviceType
     * @return Response|array
     */
    public function edit($deviceType)
    {
        $form = $this->createForm($deviceType);

        if ($form->isValid()) {
            $data = $form->getData();
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();
            $conn->beginTransaction();

            $this->setEntity($deviceType, $data);
            $this->flush();
            $conn->commit();

            $response = new Response();
            $response->redirect('/devicetype');

            return $response;

        }

        return compact('form');
    }
}