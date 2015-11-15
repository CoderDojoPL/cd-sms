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
class Version20150908203900 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();
        $schema = $this->createSchema();

        $device = $schema->getTable('devices');
        $device->dropColumn('dimensions');
        $device->dropColumn('weight');

        $devicelog = $schema->getTable('device_logs');
        $devicelog->dropColumn('dimensions');
        $devicelog->dropColumn('weight');
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
        $devices->addColumn('dimensions','string', array('default' => '?'));
        $devices->addColumn('weight','string', array('default' => '?'));

        $devicelog = $schema->getTable('device_logs');
        $devicelog->addColumn('dimensions','string', array('default' => '?'));
        $devicelog->addColumn('weight','string',array('default' => '?'));

        $this->updateSchema($schema);

        $this->commitTransaction();
    }
}