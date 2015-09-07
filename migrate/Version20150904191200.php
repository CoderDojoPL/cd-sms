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
class Version20150904191200 extends MigrateHelper{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container){

        $this->beginTransaction();
        $schema=$this->createSchema();
        $devices=$schema->getTable('devices');
        $devicesType=$devices->getColumn('type_id');
        $devicesType->setNotnull(false);

        $this->updateSchema($schema);

        $this->commitTransaction();

    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container){
        $this->beginTransaction();
        $schema=$this->createSchema();
        $devices=$schema->getTable('devices');
        $devicesType=$devices->getColumn('type_id');
        $devicesType->setNotnull(true);

        $this->updateSchema($schema);

        $this->commitTransaction();

    }

}