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
 * @author Slawek Nowak (s.nowak@coderdojo.org.pl)
 */
class Version20150913191100 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();

        $schema->createSequence('device_types_id_seq', 1, 3);
        $deviceTypes = $schema->getTable('device_types');
        $deviceTypes->changeColumn('id', array('auto_increment' => true));
        $this->updateSchema($schema);
        $this->commitTransaction();

        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 18,
            'name' => 'Add device type'
        ));
        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 19,
            'name' => 'Edit device type'
        ));

        $this->executeQuery("INSERT INTO functionalities(id,name,description) VALUES(:id, :name,:description)", array(
            'id' => 16,
            'name' => 'Device type add / edit',
            'description' => 'Add or edit device types'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();
        $deviceTypes = $schema->getTable('device_types');
        $schema->dropSequence('device_types_id_seq');
        $deviceTypes->changeColumn('id', array('auto_increment' => false));
        $this->updateSchema($schema);
        $this->commitTransaction();

        $this->executeQuery("DELETE FROM device_type_logs");

        $this->executeQuery("DELETE FROM logs WHERE log_action_id in (:id)", array(
            'id' => 19
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id in (:id)", array(
            'id' => 19
        ));

        $this->executeQuery("DELETE FROM logs WHERE log_action_id in (:id)", array(
            'id' => 18
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id in (:id)", array(
            'id' => 18
        ));

        $this->executeQuery("DELETE FROM roles_functionalities WHERE functionality_id = :id", array(
            'id' => 16
        ));

        $this->executeQuery("DELETE FROM functionalities WHERE id = :id", array(
           'id' => 16
        ));

    }

}