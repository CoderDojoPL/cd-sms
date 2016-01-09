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
class Version20160109164600 extends MigrateHelper
{

    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {

        $this->beginTransaction();

        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 21,
            'name' => 'Assign my device to location'
        ));

        $this->commitTransaction();
    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();

        $this->executeQuery("DELETE FROM logs WHERE log_action_id in (:id)", array(
            'id' => 21
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id=:id", array(
            'id' => 21,
        ));

        $this->commitTransaction();
    }

}