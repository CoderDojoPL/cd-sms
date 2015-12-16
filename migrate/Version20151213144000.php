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
class Version20151213144000 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();
        $schema->dropSequence('device_types_id_seq');
        $devices = $schema->getTable('devices');
        $deviceLogs = $schema->getTable('device_logs');
        $devices->removeForeignKey('FK_11074E9AC54C8C93');

        $deviceLogs->removeForeignKey('FK_79B61366C54C8C93');
        $this->updateSchema($schema);

        $schema = $this->createSchema();
        
        $deviceTypes = $schema->getTable('device_types');
     
        $deviceTypes->changeColumn('id', array('autoincrement' => true));
        $this->updateSchema($schema);

        $schema = $this->createSchema();
     
        $deviceLogs = $schema->getTable('device_logs');
        $devices = $schema->getTable('devices');
        $deviceTypes = $schema->getTable('device_types');
    
        $devices->addNamedForeignKeyConstraint('FK_11074E9AC54C8C93',$deviceTypes, array('type_id'),array('id'));
        $deviceLogs->addNamedForeignKeyConstraint('FK_79B61366C54C8C93',$deviceTypes, array('type_id'),array('id'));

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
        $deviceLogs = $schema->getTable('device_logs');
        $devices->removeForeignKey('FK_11074E9AC54C8C93');

        $deviceLogs->removeForeignKey('FK_79B61366C54C8C93');


        $this->updateSchema($schema);

        $schema = $this->createSchema();
        
        $deviceTypes = $schema->getTable('device_types');
        $deviceLogs = $schema->getTable('device_logs');
        $devices = $schema->getTable('devices');

        $deviceTypes->changeColumn('id', array('autoincrement' => false));

        $devices->addNamedForeignKeyConstraint('FK_11074E9AC54C8C93',$deviceTypes, array('type_id'),array('id'));
        $deviceLogs->addNamedForeignKeyConstraint('FK_79B61366C54C8C93',$deviceTypes, array('type_id'),array('id'));

     
        $this->updateSchema($schema);
 
 
        $this->commitTransaction();

    }

}