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
		$orders=$schema->getTable('orders');
		$orderLogs=$schema->getTable('order_logs');

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
 
 		$orders->dropColumn('device_id');
		$orderLogs->dropColumn('device_id');
		$this->updateSchema($schema);

		$schema = $this->createSchema();

		$deviceStates=$schema->getTable('device_states');
		$locations=$schema->getTable('locations');
		$users=$schema->getTable('users');
		$logs=$schema->getTable('logs');
		$orders=$schema->getTable('orders');
		$orderLogs=$schema->getTable('order_logs');

 		$orderLogs->addColumn('device_specimen_id','integer');
		$orders->addColumn('device_specimen_id','integer');

 		$deviceSpecimenLogs=$schema->createTable('device_specimen_logs');
 		$deviceSpecimenLogs->addColumn('id','integer',array('autoincrement'=>true));
 		$deviceSpecimenLogs->addColumn('device_id','integer');
 		$deviceSpecimenLogs->addColumn('serial_number','string');
 		$deviceSpecimenLogs->addColumn('created_at','datetime');
 		$deviceSpecimenLogs->addColumn('state_id','integer');
 		$deviceSpecimenLogs->addColumn('location_id','integer');
 		$deviceSpecimenLogs->addColumn('user_id','integer',array('notnull'=>false));
 		$deviceSpecimenLogs->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
 		$deviceSpecimenLogs->addColumn('purchase_date','datetime',array('notnull'=>false));
 		$deviceSpecimenLogs->addColumn('symbol','text');
 		$deviceSpecimenLogs->addColumn('hire_expiration_date','datetime',array('default'=>'1970-01-01 00:00:00'));
		$deviceSpecimenLogs->addColumn('log_left_id','integer');
		$deviceSpecimenLogs->addColumn('log_right_id','integer',array('notnull'=>false));
		$deviceSpecimenLogs->addColumn('removed','boolean');
        $deviceSpecimenLogs->setPrimaryKey(array('id', 'log_left_id'));



 		$deviceSpecimens=$schema->createTable('device_specimens');
 		$deviceSpecimens->addColumn('id','integer',array('autoincrement'=>true));
 		$deviceSpecimens->addColumn('device_id','integer');
 		$deviceSpecimens->addColumn('serial_number','string');
 		$deviceSpecimens->addColumn('created_at','datetime');
 		$deviceSpecimens->addColumn('updated_at','datetime');
 		$deviceSpecimens->addColumn('state_id','integer');
 		$deviceSpecimens->addColumn('location_id','integer');
 		$deviceSpecimens->addColumn('user_id','integer',array('notnull'=>false));
 		$deviceSpecimens->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
 		$deviceSpecimens->addColumn('purchase_date','datetime',array('notnull'=>false));
 		$deviceSpecimens->addColumn('symbol','text');
 		$deviceSpecimens->addColumn('hire_expiration_date','datetime',array('default'=>'1970-01-01 00:00:00'));
		$deviceSpecimens->setPrimaryKey(array('id'));

		$deviceSpecimenLogs->addNamedForeignKeyConstraint('FK_FD230B37DAA1F695',$logs, array('log_left_id'),array('id'),array('onDelete'=>'CASCADE'));
		$deviceSpecimenLogs->addNamedForeignKeyConstraint('FK_8A0E8A953AC4A3EA',$logs, array('log_right_id'),array('id'),array('onDelete'=>'CASCADE'));

		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F94A4C7D4',$devices, array('device_id'),array('id'),array('onDelete'=>'CASCADE'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F5D83CC1',$deviceStates, array('state_id'),array('id'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258F64D218E',$locations, array('location_id'),array('id'),array('onDelete'=>'CASCADE'));
		$deviceSpecimens->addNamedForeignKeyConstraint('FK_CE79258FA76ED395',$users, array('user_id'),array('id'),array('onDelete'=>'SET NULL'));
		// $orders->removeForeignKey('FK_E52FFDEE94A4C7D4');

		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE94A4C7D4',$deviceSpecimens, array('device_specimen_id'),array('id'),array('onDelete'=>'CASCADE'));

		$this->updateSchema($schema);

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>23
		,'name'=>'Add device specimen.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>24
		,'name'=>'Edit device specimen.'
		));

		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>25
			,'name'=>'Remove device specimen.'
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

		$deviceStates=$schema->getTable('device_states');
		$locations=$schema->getTable('locations');
		$users=$schema->getTable('users');
		$logs=$schema->getTable('logs');
		$orders=$schema->getTable('orders');
		$orderLogs=$schema->getTable('order_logs');
		$devices=$schema->getTable('devices');
		$deviceLogs=$schema->getTable('device_logs');
		
		$tmplocationId=$this->executeQuery('SELECT id FROM locations LIMIT 1',array(),true)[0]['id'];
		$deviceLogs->addColumn('serial_number','string',array('default'=>''));
		$deviceLogs->addColumn('state_id','integer',array('default'=>1));
		$deviceLogs->addColumn('location_id','integer',array('default'=>$tmplocationId));
		$deviceLogs->addColumn('user_id','integer',array('notnull'=>false));
		$deviceLogs->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
		$deviceLogs->addColumn('purchase_date','datetime',array('notnull'=>false));
		$deviceLogs->addColumn('symbol','text',array('default'=>''));
		$deviceLogs->addColumn('hire_expiration_date','datetime',array('default'=>'1970-01-01 00:00:00'));

		$deviceLogs->addNamedForeignKeyConstraint('FK_79B613665D83CC1',$deviceStates, array('state_id'),array('id'));

		$devices->addColumn('serial_number','string',array('default'=>''));
		$devices->addColumn('state_id','integer',array('default'=>1));
		$devices->addColumn('location_id','integer',array('default'=>$tmplocationId));
		$devices->addColumn('user_id','integer',array('notnull'=>false));
		$devices->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
		$devices->addColumn('purchase_date','datetime',array('notnull'=>false));
		$devices->addColumn('symbol','text',array('default'=>''));
		$devices->addColumn('hire_expiration_date','datetime',array('default'=>'1970-01-01 00:00:00'));

 		$orders->dropColumn('device_specimen_id');
 		$orderLogs->dropColumn('device_specimen_id');

		$this->updateSchema($schema);

		$schema = $this->createSchema();

		$tmpDeviceId=$this->executeQuery('SELECT id FROM devices LIMIT 1',array(),true);
		if(count($tmpDeviceId)){
			$tmpDeviceId=$tmpDeviceId[0]['id'];
		}
		else{
			$tmpDeviceId=null;
		}

		$deviceStates=$schema->getTable('device_states');
		$locations=$schema->getTable('locations');
		$users=$schema->getTable('users');
		$logs=$schema->getTable('logs');
		$orders=$schema->getTable('orders');
		$orderLogs=$schema->getTable('order_logs');
		$devices=$schema->getTable('devices');

		$orders->addColumn('device_id','integer',array('default'=>$tmpDeviceId));
		$orderLogs->addColumn('device_id','integer',array('default'=>$tmpDeviceId));

		$devices->addNamedForeignKeyConstraint('FK_11074E9A5D83CC1',$deviceStates, array('state_id'),array('id'));
		$devices->addNamedForeignKeyConstraint('FK_11074E9A64D218E',$locations, array('location_id'),array('id'));
		$devices->addNamedForeignKeyConstraint('FK_11074E9AA76ED395',$users, array('user_id'),array('id'),array('onDelete'=>'CASCADE'));

		// $orders->removeForeignKey('FK_E52FFDEE94A4C7D4');

		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE94A4C7D4',$devices, array('device_id'),array('id'),array('onDelete'=>'CASCADE'));
		 
		$schema->dropTable('device_specimen_logs');
		$schema->dropTable('device_specimens');


		$this->updateSchema($schema);

        $this->executeQuery("DELETE FROM device_type_logs WHERE log_left_id in (select id from logs where log_action_id in(?,?,?)) or log_right_id in (select id from logs where log_action_id in(?,?,?))", array(
            23,24,25
            ,23,24,25
        ));

        $this->executeQuery("DELETE FROM logs WHERE log_action_id in (?,?,?)", array(
			23,24,25
        ));

		$this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
			'id'=>23
		));

		$this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
			'id'=>24
		));

		$this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
			'id'=>25
		));

		$this->commitTransaction();
	}

}