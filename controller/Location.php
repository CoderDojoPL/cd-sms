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
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Common\LocationIndexColumnFormatter;
use Exception\UnableToDeleteOwnLocationException;
use Library\Doctrine\Form\DoctrineDesigner;
use Arbor\Component\Form\EmailField;

/**
 * Class Location
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Location extends Controller
{
    /**
     * Prepare data for index view
     *
     * @return array
     */
    public function index()
    {
        $grid = $this->createGrid();
        return compact('grid');
    }

    /**
     * Creates grid for locations list
     *
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createGrid()
    {
        $builder = $this->getService('grid')->create();
        $builder->setFormatter(new BasicGridFormatter('location'));//prefix
        $builder->setDataManager(new BasicDataManager(
            $this->getDoctrine()->getEntityManager()
            , 'Entity\Location'
        ));

        $builder->setLimit(10);
        $query = $this->getRequest()->getQuery();
        if (!isset($query['page'])) {
            $query['page'] = 1;
        }
        $builder->setPage($query['page']);

        $builder->addColumn('#', 'id');
        $builder->addColumn('Name', 'name');
        $builder->addColumn('City', 'city');
        $builder->addColumn('Street', 'street');
        $builder->addColumn('Number', 'number');

//        $builder->addColumn('Serial number','serialNumber');
//        $builder->addColumn('Type','type');
        $builder->addColumn('Action', 'id', new LocationIndexColumnFormatter('location', array('edit', 'remove'), $this->getUser()));
        return $builder;
    }

    /**
     * Save new location to database
     *
     * @return Response|array
     */
    public function add()
    {
        $form = $this->createForm();

        if ($form->isValid()) {
            $data = $form->getData();

            $entity = new \Entity\Location();
            $this->setEntity($entity, $data);
            $this->flush();

            $response = new Response();
            $response->redirect('/location');

            return $response;

        }
        return compact('form');
    }

    /**
     * Save change to location after edit
     *
     * @param \Entity\Location $entity
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
            $response->redirect('/location');

            return $response;

        }
        return compact('form');
    }

    /**
     * Setting entity from form data
     *
     * @param \Entity\Location $entity
     * @param $data
     */
    private function setEntity($entity, $data)
    {
        $entity->setName($data['name']);
        $entity->setApartment($data['apartment']);
        $entity->setCity($data['city']);
        $entity->setNumber($data['number']);
        $entity->setPostal($data['postal']);
        $entity->setStreet($data['street']);
        $entity->setPhone($data['phone']);
        $entity->setEmail($data['email']);
        $this->persist($entity);
    }

    /**
     * Create form helper for add/edit location
     *
     * @param null|\Entity\Location $entity
     * @return mixed
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createForm($entity = null)
    {
        $builder = $this->getService('form')->create();
        $builder->setValidatorService($this->getService('validator'));
        $builder->setFormatter(new BasicFormFormatter());
        $builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\Location'));

        $builder->removeField('updatedAt');
        $builder->removeField('createdAt');
        $builder->removeField('email');
        $builder->addField(new EmailField(array(
            'name' => 'email'
        , 'label' => 'Email'
        , 'required' => true
        )));

        //FIXME pattern for phone and email
        $postal = $builder->getField('postal');
        $postal->setPattern('^[0-9]{2}\-[0-9]{3}$');//FIXME to config
        $postal->setTag('placeholder', 'eg.: 00-000');
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
     * Method for create PHP confirm remove screen
     *
     * @param $entity
     * @return array
     */
    public function removeConfirm($entity)
    {
        return compact('entity');
    }

    /**
     * Removes location entity
     *
     * @param \Entity\Location $entity
     * @return Response
     */
    public function remove($entity)
    {
        if ($entity == $this->getUser()->getLocation()){
            throw new UnableToDeleteOwnLocationException();
        }
        $this->getDoctrine()->getEntityManager()->remove($entity);
        $this->flush();

        $response = new Response();
        $response->redirect('/location');
        return $response;

    }

}