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
class Version20160117123500 extends MigrateHelper
{

	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container)
	{

		$this->beginTransaction();
		$schema = $this->createSchema();

		$deviceStates=$schema->getTable('device_states');
		$locations=$schema->getTable('locations');
		$users=$schema->getTable('users');
		$logs=$schema->getTable('logs');

		$deviceLogs=$schema->getTable('device_logs');
		$deviceLogs->dropColumn('serial_number');
		$deviceLogs->dropColumn('state_id');
		$deviceLogs->dropColumn('location_id');
		$deviceLogs->dropColumn('user_id');
		$deviceLogs->dropColumn('warranty_expiration_date');
		$deviceLogs->dropColumn('purchase_date');
		$deviceLogs->dropColumn('symbol');
		$deviceLogs->dropColumn('hire_expiration_date');

		$devices=$schema->getTable('devices');

 		// $devices->removeForeignKey('FK_11074E9A5D83CC1'); //state_id
 		// $devices->removeForeignKey('FK_11074E9A64D218E'); //location_id
 		// $devices->removeForeignKey('FK_11074E9AA76ED395'); //user_id

		$devices->dropColumn('serial_number');
		$devices->dropColumn('state_id');
		$devices->dropColumn('location_id');
		$devices->dropColumn('user_id');
		$devices->dropColumn('warranty_expiration_date');
		$devices->dropColumn('purchase_date');
		$devices->dropColumn('symbol');
		$devices->dropColumn('hire_expiration_date');
 

 		$deviceSpecimenLogs=$schema->createTable('device_specimen_logs');
 		$deviceSpecimenLogs->addColumn('id','integer',array('autoincrement'=>true));
 		$deviceSpecimenLogs->addColumn('device_id','integer');
 		$deviceSpecimenLogs->addColumn('serial_number','string');
 		$deviceSpecimenLogs->addColumn('created_at','datetime');
 		$deviceSpecimenLogs->addColumn('updated_at','datetime');
 		$deviceSpecimenLogs->addColumn('state_id','integer');
 		$deviceSpecimenLogs->addColumn('location_id','integer');
 		$deviceSpecimenLogs->addColumn('user_id','integer');
 		$deviceSpecimenLogs->addColumn('warranty_expiration_date','datetime');
 		$deviceSpecimenLogs->addColumn('purchase_date','datetime');
 		$deviceSpecimenLogs->addColumn('symbol','text');
 		$deviceSpecimenLogs->addColumn('hire_expiration_date','datetime');
		$deviceSpecimenLogs->addColumn('log_left_id','integer');
		$deviceSpecimenLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$deviceSpecimenLogs->addColumn('removed','boolean');



 		$deviceSpecimens=$schema->createTable('device_specimens');
 		$deviceSpecimens->addColumn('id','integer',array('autoincrement'=>true));
 		$deviceSpecimens->addColumn('device_id','integer');
 		$deviceSpecimens->addColumn('serial_number','string');
 		$deviceSpecimens->addColumn('created_at','datetime');
 		$deviceSpecimens->addColumn('updated_at','datetime');
 		$deviceSpecimens->addColumn('state_id','integer');
 		$deviceSpecimens->addColumn('location_id','integer');
 		$deviceSpecimens->addColumn('user_id','integer');
 		$deviceSpecimens->addColumn('warranty_expiration_date','datetime');
 		$deviceSpecimens->addColumn('purchase_date','datetime');
 		$deviceSpecimens->addColumn('symbol','text');
 		$deviceSpecimens->addColumn('hire_expiration_date','datetime');

		$deviceSpecimenLogs->addNamedForeignKeyConstraint('FK_FD230B37DAA1F695',$logs, array('log_left_id'),array('id'));
		$deviceSpecimenLogs->addNamedForeignKeyConstraint('FK_8A0E8A953AC4A3EA',$logs, array('log_right_id'),array('id'));

		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F94A4C7D4',$devices, array('device_id'),array('id'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F5D83CC1',$deviceStates, array('state_id'),array('id'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F64D218E',$locations, array('location_id'),array('id'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258FA76ED395',$users, array('user_id'),array('id'));

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

		$this->commitTransaction();
	}

}