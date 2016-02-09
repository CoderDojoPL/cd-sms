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
class Version20150711112810 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){
		$schema=$this->createSchema();

		$deviceTags=$schema->createTable('device_tags');
		$deviceTags->addColumn('id','integer',array('autoincrement'=>true));
		$deviceTags->addColumn('name','string');
		$deviceTags->setPrimaryKey(array('id'));

		$files=$schema->createTable('files');
		$files->addColumn('id','integer',array('autoincrement'=>true));
		$files->addColumn('file_name','string');
		$files->addColumn('description','string',array('notnull'=>false));
		$files->addColumn('size','bigint');
		$files->addColumn('mime_type','string');
		$files->setPrimaryKey(array('id'));

		$deviceTypes=$schema->createTable('device_types');
		$deviceTypes->addColumn('id','integer');
		$deviceTypes->addColumn('name','string');
		$deviceTypes->setPrimaryKey(array('id'));

		$orders=$schema->createTable('orders');
		$orders->addColumn('id','integer',array('autoincrement'=>true));
		$orders->addColumn('owner_id','integer');
		$orders->addColumn('device_id','integer');
		$orders->addColumn('state_id','integer');
		$orders->addColumn('performer_id','integer',array('notnull'=>false));
		$orders->addColumn('fetched_at','datetime',array('notnull'=>false));
		$orders->addColumn('closed_at','datetime',array('notnull'=>false));
		$orders->addColumn('created_at','datetime');
		$orders->addColumn('updated_at','datetime');
		$orders->setPrimaryKey(array('id'));

		$orderStates=$schema->createTable('order_states');
		$orderStates->addColumn('id','integer');
		$orderStates->addColumn('name','string');
		$orderStates->setPrimaryKey(array('id'));

		$deviceStates=$schema->createTable('device_states');
		$deviceStates->addColumn('id','integer');
		$deviceStates->addColumn('name','string');
		$deviceStates->setPrimaryKey(array('id'));

		$users=$schema->createTable('users');
		$users->addColumn('id','integer',array('autoincrement'=>true));
		$users->addColumn('location_id','integer',array('notnull'=>false));
		$users->addColumn('email','string');
		$users->addColumn('first_name','string');
		$users->addColumn('last_name','string');
		$users->setPrimaryKey(array('id'));

		$devices=$schema->createTable('devices');
		$devices->addColumn('id','integer',array('autoincrement'=>true));
		$devices->addColumn('type_id','integer',array('notnull'=>false));
		$devices->addColumn('state_id','integer');
		$devices->addColumn('location_id','integer');
		$devices->addColumn('name','string');
		$devices->addColumn('photo','string',array('notnull'=>false));
		$devices->addColumn('dimensions','string');
		$devices->addColumn('weight','string');
		$devices->addColumn('serial_number','string');
		$devices->addColumn('created_at','datetime');
		$devices->addColumn('updated_at','datetime');
		$devices->addColumn('warranty_expiration_date','datetime',array('notnull'=>false));
		$devices->addColumn('price','decimal',array('scale'=>2,'notnull'=>false));
		$devices->addColumn('note','text',array('notnull'=>false));
		$devices->setPrimaryKey(array('id'));


		$devicesTags=$schema->createTable('devices_tags');
		$devicesTags->addColumn('device_id','integer');
		$devicesTags->addColumn('tag_id','integer');
		$devicesTags->setPrimaryKey(array('device_id','tag_id'));


		$locations=$schema->createTable('locations');
		$locations->addColumn('id','integer',array('autoincrement'=>true));
		$locations->addColumn('name','string');
		$locations->addColumn('city','string');
		$locations->addColumn('street','string');
		$locations->addColumn('number','string');
		$locations->addColumn('apartment','string',array('notnull'=>false));
		$locations->addColumn('postal','string');
		$locations->addColumn('phone','string');
		$locations->addColumn('email','string');
		$locations->addColumn('created_at','datetime');
		$locations->addColumn('updated_at','datetime');
		$locations->setPrimaryKey(array('id'));

		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE7E3C61F9',$users, array('owner_id'),array('id'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE94A4C7D4',$devices, array('device_id'),array('id'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE5D83CC1',$orderStates, array('state_id'),array('id'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE6C6B33F3',$users, array('performer_id'),array('id'));
		$users->addNamedForeignKeyConstraint('FK_1483A5E964D218E',$locations, array('location_id'),array('id'));
		$devices->addNamedForeignKeyConstraint('FK_11074E9AC54C8C93',$deviceTypes, array('type_id'),array('id'));
		$devices->addNamedForeignKeyConstraint('FK_11074E9A5D83CC1',$deviceStates, array('state_id'),array('id'));
		$devices->addNamedForeignKeyConstraint('FK_11074E9A64D218E',$locations, array('location_id'),array('id'));
		$devicesTags->addNamedForeignKeyConstraint('FK_8472C11794A4C7D4',$devices, array('device_id'),array('id'));
		$devicesTags->addNamedForeignKeyConstraint('FK_8472C117BAD26311',$deviceTags, array('tag_id'),array('id'));

		$this->updateSchema($schema);

		$this->executeQuery("INSERT INTO device_types(id,name) VALUES(:id,:name);",array(
			'id'=>1
			,'name'=>'Refill'
		));

		$this->executeQuery("INSERT INTO device_types(id,name) VALUES(:id,:name)",array(
			'id'=>2
			,'name'=>'Hardware'
		));

		$this->executeQuery("INSERT INTO device_states(id,name) VALUES(:id,:name)",array(
			'id'=>1
			,'name'=>'Available'
		));

		$this->executeQuery("INSERT INTO device_states(id,name) VALUES(:id,:name)",array(
			'id'=>2
			,'name'=>'Busy'
		));

		$this->executeQuery("INSERT INTO device_states(id,name) VALUES(:id,:name)",array(
			'id'=>3
			,'name'=>'In service'
		));

		$this->executeQuery("INSERT INTO order_states(id,name) VALUES(:id,:name)",array(
			'id'=>1
			,'name'=>'Open'
		));

		$this->executeQuery("INSERT INTO order_states(id,name) VALUES(:id,:name)", array(
			'id'=>2
			,'name'=>'In progress'
		));

		$this->executeQuery("INSERT INTO order_states(id,name) VALUES(:id,:name)",array(
			'id'=>3
			,'name'=>'Closed'
		));

	}

	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){
		$this->beginTransaction();
		$schema=$this->createSchema();

		$schema->dropTable('device_tags');
		
		$schema->dropTable('files');

		$schema->dropTable('device_types');

		$schema->dropTable('orders');

		$schema->dropTable('order_states');

		$schema->dropTable('device_states');

		$schema->dropTable('users');

		$schema->dropTable('devices');


		$schema->dropTable('devices_tags');

		$schema->dropTable('locations');

		// print_r($schema);exit;
		$this->updateSchema($schema);
		$this->commitTransaction();

	}

}