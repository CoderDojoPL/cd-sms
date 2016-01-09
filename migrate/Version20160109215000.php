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
class Version20160109215000 extends MigrateHelper
{

	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container)
	{

		$this->beginTransaction();
		$schema = $this->createSchema();

		$deviceLogs=$schema->getTable('device_logs');
		$deviceLogs->addColumn('purchase_date','datetime',array('notnull'=>false));

		$devices=$schema->getTable('devices');
		$devices->addColumn('purchase_date','datetime',array('notnull'=>false));
 
		$this->updateSchema($schema);
 
		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
			'id' => 22,
			'name' => 'Assign location device to me'
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

		$deviceLogs=$schema->getTable('device_logs');
		$deviceLogs->dropColumn('purchase_date');

		$devices=$schema->getTable('devices');
		$devices->dropColumn('purchase_date');
 
		$this->updateSchema($schema);

		$this->executeQuery("DELETE FROM device_logs WHERE log_left_id in (select id from logs where log_action_id=:id) or log_right_id in (select id from logs where log_action_id=:id)", array(
			'id' => 22
		));

		$this->executeQuery("DELETE FROM logs WHERE log_action_id in (:id)", array(
			'id' => 22
		));

		$this->executeQuery("DELETE FROM log_actions WHERE id=:id", array(
			'id' => 22,
		));

		$this->commitTransaction();
	}

}