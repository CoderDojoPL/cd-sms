<?php

namespace Event;
use Arbor\Core\Event;
use Arbor\Event\ExecutePresenterEvent;
use Arbor\Exception\PermissionDeniedException;
use Exception\UserNotFoundException;
use Exception\LogEntityNotFoundException;
use Arbor\Exception\ValueNotFoundException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Arbor\Event\ExecuteActionEvent;
use Exception\LogNotFoundException;
use Arbor\Exception\HeaderNotFoundException;

/**
 * Event to verify permission
 *
 * @package Event
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Permission extends Event{

    /**
     * @param ExecuteActionEvent $event
     */
    public function onExecuteAction(ExecuteActionEvent $event){

        if($event->getResponse()){//ignore when setted response by other event.
            return;
        }

        $request=$event->getRequest();
        $verify=false;
        foreach($request->getExtra() as $extra){
            foreach($extra as $parameter=>$config){
                if($parameter=='permission'){
                    $verify=true;
                    if($this->isAllow($event,$config['functionality'])){
                        return;
                    }
                }
            }
        }

        if($verify){
            throw new PermissionDeniedException();
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

    /**
     * Check permission
     *
     * @param ExecuteActionEvent $event
     * @param array $functionality
     * @return boolean
     */
    private function isAllow($event,$functionality){
        $user=$this->getSessionUser($event);
        if(!$user){
            return false;
        }

        if(!$user->getRole()){
            return false;
        }

        foreach($user->getRole()->getFunctionalities() as $userFunctionality){
            if($userFunctionality->getId()==$functionality){
                return true;
            }
        }

        return false;
    }

}