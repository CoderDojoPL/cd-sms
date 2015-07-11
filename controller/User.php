<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-12
 * Time: 16:53
 */

namespace Controller;

use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Common\ActionColumnFormatter;
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Library\Doctrine\Form\DoctrineDesigner;

class User extends Controller
{
    public function index()
    {
        $grid = $this->createGrid();
        return compact('grid');
    }

    private function createGrid()
    {
        $builder = $this->getService('grid')->create();
        $builder->setFormatter(new BasicGridFormatter('user',false));//prefix
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
//        $builder->addColumn('Location', 'location');
//        $builder->render();
        $builder->addColumn('Action', 'id', new ActionColumnFormatter('user', array('edit')));
        return $builder;
    }

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

    private function createForm($entity = null)
    {
        $builder = $this->getService('form')->create();
        $builder->setValidatorService($this->getService('validator'));
        $builder->setFormatter(new BasicFormFormatter());
        $builder->setSubmitTags(array('cancel' => true));
        $builder->setDesigner(new DoctrineDesigner($this->getDoctrine(), 'Entity\User'));


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