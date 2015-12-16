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
class Version20151216185000 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();
        $devices = $schema->getTable('devices');
        $deviceLogs = $schema->getTable('device_logs');

        $devices->removeForeignKey('FK_11074E9AC54C8C93');

        $deviceLogs->removeForeignKey('FK_79B61366C54C8C93');
        
        $this->updateSchema($schema);

        $schema=$this->createSchema();
        $devices = $schema->getTable('devices');
        $deviceTypes = $schema->getTable('device_types');

        $devices->addNamedForeignKeyConstraint('FK_11074E9AC54C8C93',$deviceTypes, array('type_id'),array('id'),array('onDelete'=>'CASCADE'));

        $this->updateSchema($schema);
 
 
        $this->commitTransaction();

    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();
        $devices = $schema->getTable('devices');

        $devices->removeForeignKey('FK_11074E9AC54C8C93');

        $this->updateSchema($schema);

        $this->executeQuery('DELETE FROM device_logs WHERE type_id NOT IN(1,2)');

        $schema=$this->createSchema();
        $devices = $schema->getTable('devices');
        $deviceTypes = $schema->getTable('device_types');
        $deviceLogs = $schema->getTable('device_logs');

        $devices->addNamedForeignKeyConstraint('FK_11074E9AC54C8C93',$deviceTypes, array('type_id'),array('id'));

        $deviceLogs->addNamedForeignKeyConstraint('FK_79B61366C54C8C93',$deviceTypes, array('type_id'),array('id'));

        $this->updateSchema($schema);
 
 
        $this->commitTransaction();

    }

}