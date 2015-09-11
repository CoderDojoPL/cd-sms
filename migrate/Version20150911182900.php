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
 * @author Slawek Mowak (s.nowak@coderdojo.org.pl)
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

    }

}