<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

/**
 * @package Migrate
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Version20150911182900 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();
        $devices=$schema->getTable('devices');
        $devices->addColumn('hire_expiration_date','datetime',array('notnull' => false));

        $deviceLogs=$schema->getTable('device_logs');
        $deviceLogs->addColumn('hire_expiration_date','datetime',array('notnull' => false));

        $this->updateSchema($schema);

        $this->executeQuery("INSERT INTO functionalities(id,name,description) VALUES(:id,:name,:description)",array(
            'id'=>15
            ,'name'=>'Hire date prolongation'
            ,'description'=>''
        ));

        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 20,
            'name' => 'Prolongation hire date for device'
        ));

        $this->commitTransaction();

    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();
        $devices=$schema->getTable('devices');
        $devices->dropColumn('hire_expiration_date');

        $deviceLogs=$schema->getTable('device_logs');
        $deviceLogs->dropColumn('hire_expiration_date');

        $this->updateSchema($schema);

        $this->commitTransaction();

        $this->executeQuery("DELETE FROM roles_functionalities WHERE functionality_id=:id",array(
            'id'=>15
        ));

        $this->executeQuery("DELETE FROM functionalities WHERE id=:id",array(
            'id'=>15
        ));

        $this->executeQuery("DELETE FROM device_logs WHERE log_left_id in (select id from logs where log_action_id=:id) or log_right_id in (select id from logs where log_action_id=:id)", array(
            'id' => 20
        ));

        $this->executeQuery("DELETE FROM logs WHERE log_action_id in (:id)", array(
            'id' => 20
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id in (:id)", array(
            'id' => 20
        ));

    }

}