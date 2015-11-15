<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Command;

use Arbor\Core\Command;

/**
 * Detect expiration hires and send email notify.
 * @package Command
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class CheckHireDate extends Command{

    /**
     * Execute method
     */
    public function run(){
        $this->sendNotify();
        $this->freeRecord();
    }

    /**
     * Detect records with near expiration date
     */
    private function sendNotify(){
        $records=$this->getDoctrine()->getEntityManager()->createQuery(
            'SELECT d FROM Entity\Device d
              WHERE d.user is not null
              and CURRENT_DATE() between DATE_SUB(d.hireExpirationDate,2,\'day\') and d.hireExpirationDate' //without limit. It's ok?
        )->getResult();
        foreach($records as $record){
            $this->preapreNotify($record);
        }
    }

    /**
     * Send emails
     */
    private function preapreNotify($record){
        $users=$this->getDoctrine()->getEntityManager()->createQuery(
            'SELECT u FROM Entity\User u
              join u.role r
              join r.functionalities f
              WHERE u.location=:location and f.id=:functionality
              '
        )
        ->setParameters(array(
            'location'=>$record->getLocation()
            ,'functionality'=>$this->cast('Mapper\Functionality',15)
        ))
        ->getResult();
        $config=$this->getService('config');

        $subject='SMS - Hire device expiration date';
        $body="Hello.
        <br/>Device: ".$record->__toString()."
        <br/>Location: ".$record->getLocation()->__toString()."
        <br/>User: ".$record->getUser()->__toString()."
        <br/>Expiration date: ".$record->getHireExpirationDate()->format('Y-m-d')."
        <br/>Click <a href='".rtrim($config->getHost(),'/')."/device/prolongation/".$record->getId()."'>here</a> to prolongation";
        foreach($users as $user){
            $this->sendEmail($user->getEmail(),$subject,$body);
            $this->writeLn("Sended email to ".$user->__toString()." for device ".$record->__toString());
        }
    }


    /**
     * Detect records with expiration date and unhook from user.
     */
    private function freeRecord(){

        $records=$this->getDoctrine()->getEntityManager()->createQuery(
            'SELECT d FROM Entity\Device d
              WHERE d.user is not null
              and d.hireExpirationDate<CURRENT_DATE()' //without limit. It's ok?
        )->getResult();
        foreach($records as $record){
            $record->setUser(null);
            $record->setHireExpirationDate(null);
            $this->writeLn("Free record: ".$record->getId());
            //TODO save to log
        }

        $this->flush();
    }

    private function sendEmail($email, $subject, $body){
        $mailer=$this->getService('swiftmailer');
        $config=$this->getService('config');
        $from=$config->getSenderEmailAddress();
        $message=$mailer->createMessage($subject)
            ->setFrom(array($from=>$from))
            ->setTo(array($email))
            ->setBody($body,'text/html');
        $mailer->send($message);

    }

}