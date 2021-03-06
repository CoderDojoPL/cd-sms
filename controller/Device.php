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
use Common\ImageColumnFormatter;
use Doctrine\Common\Version;
use Entity\DeviceType;
use Exception\DeviceNotFoundException;
use Library\Doctrine\Form\DoctrineDesigner;
use Arbor\Provider\Response;
use Common\BasicDataManager;
use Arbor\Component\Grid\Column;

/**
 * Class Device
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Device extends Controller
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
     * Save new device to database
     *
     * @return Response|array
     */
    public function add()
    {
        $form = $this->createForm();

        if ($form->isValid()) {
            $data = $form->getData();

            if ($data['photo']) {//save uploaded photo to cache file
                $data['tmpPhoto'] = $this->saveTmpPhoto($data['photo']);
            }

            $this->getRequest()->getSession()->set('device.info', $data);

            $response = new Response();
            $response->redirect('/device/add/serialNumber');

            return $response;

        }
        return compact('form');
    }

    /**
     * Saving uploaded photo to cache file
     *
     * @param $photo
     * @return string file path
     */
    private function saveTmpPhoto($photo)
    {
        $tmpName = basename(tempnam(sys_get_temp_dir(), "hms_"));
        $photo->save(sys_get_temp_dir(), $tmpName);
        return sys_get_temp_dir() . '/' . $tmpName;

    }

    /**
     * Method for create PHP confirm remove screen
     *
     * @param \Entity\Device $entity
     * @return array
     */
    public function removeConfirm($entity)
    {
        return compact('entity');
    }

    /**
     * Removing device from database
     *
     * @param \Entity\Device $entity
     * @return Response
     */
    public function remove($entity)
    {
        $this->getDoctrine()->getEntityManager()->remove($entity);
        $this->flush();

        $response = new Response();
        $response->redirect('/device');
        return $response;

    }

    /**
     * Preparing form for input serial numbers
     *
     * @return Response|array
     */
    public function serialNumber()
    {
        $data = $this->getRequest()->getSession()->get('device.info');

        $form = $this->createFormBuilder();
        for ($i = 0; $i < $data['count']; $i++) {
            $form->addField(new TextField(array(
                'name' => 'serialNumber[' . $i . ']'
            , 'label' => ($i + 1) . '.'
            , 'required' => true
            )));

        }

        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $serialNumbersData = $form->getData();
            $this->saveEntities($data, $serialNumbersData['serialNumber']);

            $response = new Response();
            $response->redirect('/device');
            $this->getRequest()->getSession()->remove('device.info');

            return $response;

        }

        return compact('form', 'data');

    }

    /**
     * Helper for saving Devices
     *
     * @param $data
     * @param $serialNumber
     */
    private function saveEntities($data, $serialNumber)
    {
        $conn = $this->getDoctrine()->getEntityManager()->getConnection();
        $conn->beginTransaction();

        for ($i = 0; $i < $data['count']; $i++) {
            $deviceEntity = new \Entity\Device();
            $this->saveEntity($deviceEntity, $data, $serialNumber[$i], 1);
        }


        $this->flush();

        $conn->commit();

    }

    /**
     * Save device to database
     *
     * @param \Entity\Device $entity
     * @param $data
     * @param $serialNumber
     * @param null $state
     */
    private function saveEntity($entity, $data, $serialNumber, $state = null)
    {
        $deviceType = $this->cast('Mapper\DeviceType', $data['type']);
        /* @var $deviceType \Entity\DeviceType */
        $entity->setName($data['name']);
        $entity->setType($deviceType);
        $entity->setSerialNumber($serialNumber);
        $entity->setWarrantyExpirationDate($data['warrantyExpirationDate'] ? new \DateTime($data['warrantyExpirationDate']) : NULL);
        $entity->setPurchaseDate($data['purchaseDate'] ? new \DateTime($data['purchaseDate']) : NULL);
        $entity->setNote($data['note']);
        $entity->setPrice($data['price'] ? $data['price'] : NULL);


        if ($state)
            $entity->setState($this->cast('Mapper\DeviceState', $state));

        if (isset($data['location'])) {
            $entity->setLocation($this->cast('Mapper\Location', $data['location']));
            $current = $deviceType->getCurrent();
            $prefix = $deviceType->getSymbolPrefix();
            $entity->setSymbol($prefix . ++$current);
            $deviceType->setCurrent($current);
        }

        if (isset($data['user']) && $data['user']) {
            $entity->setUser($this->cast('Mapper\User', $data['user']));
        }

        $this->persist($entity);

        $tagsPart = explode(',', $data['tags']);
        $entity->getTags()->clear();
        $this->flush();
        foreach ($tagsPart as $tag) {
            $tag = trim($tag);
            $tagEntity = $this->findOne('DeviceTag', array('name' => $tag));

            if ($tagEntity == null) {
                $tagEntity = new \Entity\DeviceTag();
                $tagEntity->setName($tag);
                $this->persist($tagEntity);

            }

            $entity->getTags()->add($tagEntity);
        }

        if ($data['photo']) {
            $dir = 'uploaded/device/photo/';

            $name = '';
            do {
                $name = rand() . '_' . $data['photo']->getName();

            } while (file_exists($dir . $name));
            copy($data['tmpPhoto'], $dir . $name);
            $entity->setPhoto($name);
        }

    }

    /**
     * Save changes on device after edit
     *
     * @param \Entity\Device $device
     * @return Response|array
     */
    public function edit($device)
    {
        $form = $this->createForm($device);

        if ($form->isValid()) {
            $data = $form->getData();
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();
            $conn->beginTransaction();

            if ($data['photo']) {//save uploaded photo to cache file
                $data['tmpPhoto'] = $this->saveTmpPhoto($data['photo']);
            }

            $this->saveEntity($device, $data, $data['serialNumber']);
            $this->flush();
            $conn->commit();

            $response = new Response();
            $response->redirect('/device');

            return $response;

        }

        return compact('form');
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
        $builder->setFormatter(new BasicGridFormatter('device', $this->isAllow(1)));
        $builder->setDataManager(new BasicDataManager(
            $this->getDoctrine()->getEntityManager()
            , 'Entity\Device'
        ));

        $builder->setLimit(10);

        $builder->addColumn(new Column('id','#'));
        $builder->addColumn(new Column('photo','Photo', new ImageColumnFormatter(),array()));
        $builder->addColumn(new Column('name','Name'));
        $builder->addColumn(new Column('serialNumber','Serial number'));
        $builder->addColumn(new Column('type','Type'));
        $builder->addColumn(new Column('symbol','Symbol'));
        $builder->addColumn(new Column(array('location', 'user'),'Location'));
        $builder->addColumn(new Column('id','Action', new ActionColumnFormatter('device', array('edit', 'remove')),array()));
        return $builder;
    }

    /**
     * Create and configure FormBuilder
     *
     * @return \Arbor\Component\Form\FormBuilder
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
     * Creates form for Add / Edit Device
     *
     * @param null|\Entity\Device $entity
     * @return \Arbor\Component\Form\FormBuilder
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function createForm($entity = null)
    {
        $builder = $this->createFormBuilder();
        $builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\Device'));
        $builder->removeField('photo');
        $builder->removeField('serialNumber');
        $builder->removeField('updatedAt');
        $builder->removeField('createdAt');
        $builder->removeField('state');
        $builder->removeField('symbol');
        $builder->removeField('hireExpirationDate');

        $builder->addField(new FileField(array(
            'name' => 'photo'
            , 'label' => 'Photo'
            , 'accept' => 'image/*'
            , 'maxSize' => 1048576
        )));

        $builder->removeField('tags');

        $builder->addField(new TextField(array(
            'name' => 'tags'
        , 'label' => 'Tags'
        , 'required' => true
        , 'data-role' => 'tagsinput'
        )));

        //TODO set required for photo in global configuration

        if ($entity) {
            $builder->removeField('location');
            $builder->removeField('user');
            $builder->addField(new TextField(array(
                'name' => 'serialNumber'
            , 'label' => 'Serial number'
            , 'required' => true
            )));

            $helper = $this->getService('form.helper');
            $data = $helper->entityToArray($entity, array('Entity\DeviceTag' => 'getName'));
            $data['tags'] = implode(',', $data['tags']);
            $builder->setData($data);


        } else {

            $builder->addField(new NumberField(array(
                'name' => 'count'
            , 'label' => 'Count'
            , 'required' => true
            , 'value' => 1
            , 'min' => 1
            )));
        }


        $builder->submit($this->getRequest());

        return $builder;

    }

    /**
     * Prolongation device hire date
     *
     * @param \Entity\Device $entity
     * @return mixed
     * @throws DeviceNotFoundException
     */
    public function prolongation($entity)
    {
        if ($entity->getLocation()->getId() != $this->getUser()->getLocation()->getId()
        || !$entity->getHireExpirationDate() || !$entity->getUser()) {
            throw new DeviceNotFoundException();
        }

        $hireDate=$entity->getHireExpirationDate();
        $hireDate->sub(new \DateInterval('P2D'));

        if($hireDate > new \DateTime()){
            throw new DeviceNotFoundException();
        }
        $now=new \DateTime();
        $now->add(new \DateInterval('P14D'));
        $entity->setHireExpirationDate($now);
        $this->flush();
    }

}