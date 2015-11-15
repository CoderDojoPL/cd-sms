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
 * Class Role
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Role extends Controller
{

    /**
     * Shows list of roles
     *
     * @return array
     */
    public function index()
    {
        $grid = $this->createGrid();
        return compact('grid');
    }

    /**
     * Helper for creating grid with functionalities
     *
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createGrid()
    {
        $builder = $this->getService('grid')->create($this->getRequest());
        $builder->setFormatter(new BasicGridFormatter('role',$this->isAllow(8)));//prefix
        $builder->setDataManager(new BasicDataManager(
            $this->getDoctrine()->getEntityManager()
            , 'Entity\Role'
        ));

        $builder->setLimit(10);

        $builder->addColumn('#', 'id',null,'id');
        $builder->addColumn('Name', 'name',null,'name');
//        $builder->render();

        $builder->addColumn('Action', 'id', new ActionColumnFormatter('role', array('edit')));
        return $builder;
    }

    /**
     * Save new roles to database
     *
     * @return Response|array
     */
    public function add()
    {
        $form = $this->createForm();

        if ($form->isValid()) {
            $data = $form->getData();

            $entity = new \Entity\Role();
            $this->setEntity($entity, $data);
            $this->flush();

            $response = new Response();
            $response->redirect('/role');

            return $response;

        }
        return compact('form');
    }

    /**
     * Saves user entity to database after edit
     *
     * @param Role $entity
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
            $response->redirect('/role');

            return $response;

        }
        return compact('form');
    }

    /**
     * Create form for edit Role
     *
     * @param null|Role $entity
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createForm($entity = null)
    {
        $builder = $this->getService('form')->create();
        $builder->setValidatorService($this->getService('validator'));
        $builder->setFormatter(new BasicFormFormatter());
        $builder->setSubmitTags(array('cancel' => true));
        $builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\Role'));
        $builder->getField('functionalities')->setRequired(true);

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
     * @param \Entity\Role $entity
     * @param $data
     */
    private function setEntity($entity, $data)
    {
        $entity->setName($data['name']);
        $entity->getFunctionalities()->clear();
        $this->flush();
        foreach ($data['functionalities'] as $oneF) {
            $entity->getFunctionalities()->add($this->cast('Mapper\Functionality', $oneF));
        }
        $this->persist($entity);
    }

}