<?php

namespace Event;
use Arbor\Core\Event;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Exception\PermissionDeniedException;
use Exception\UserNotFoundException;
use Arbor\Exception\ValueNotFoundException;
use Library\Twig\Presenter\Twig;

/**
 * Event to verify permission
 *
 * @package Event
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class AppendSystemData extends Event{

    /**
     * @param ExecutePresenterEvent $event
     */
    public function onExecutePresenter(ExecutePresenterEvent $event){
        $response=$event->getResponse();
        if($response->getPresenter() instanceof Twig){
            $data=$response->getContent();
            $data['_functionalities']=$this->getFunctionalities($event);
            $response->setContent($data);
        }
    }

    /**
     * @param $event
     * @return \Entity\User
     * @throws UserNotFoundException
     * @throws \Arbor\Exception\ServiceNotFoundException
     */
    private function getSessionUser($event){
        try{
            $userId=$event->getRequest()->getSession()->get('user.id');

            $doctrine=$this->getService('doctrine');
            $user=$doctrine->getRepository('Entity\User')->findOneById($userId);

            if(!$user)
                throw new UserNotFoundException();

            return $user;
        }
        catch(ValueNotFoundException $e){
            return null;
        }

    }

    private function getFunctionalities($event){
        $user=$this->getSessionUser($event);
        $result=array();
        if(!$user || !$user->getRole()){
            return $result;
        }

        foreach($user->getRole()->getFunctionalities() as $functionality){
            $result[]=$functionality->getId();
        }

        return $result;

    }
}