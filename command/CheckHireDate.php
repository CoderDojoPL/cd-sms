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
            $this->sendEmail($record);
        }
    }

    /**
     * Send emails
     */
    private function sendEmail($record){
        //TODO copy from another project
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

}