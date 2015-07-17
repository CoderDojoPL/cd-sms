<?php 
namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

class Version20150711112810 extends MigrateHelper{
	
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

		$orders->addUnnamedForeignKeyConstraint($users, array('owner_id'),array('id'));
		$orders->addUnnamedForeignKeyConstraint($devices, array('device_id'),array('id'));
		$orders->addUnnamedForeignKeyConstraint($orderStates, array('state_id'),array('id'));
		$orders->addUnnamedForeignKeyConstraint($users, array('performer_id'),array('id'));
		$users->addUnnamedForeignKeyConstraint($locations, array('location_id'),array('id'));
		$devices->addUnnamedForeignKeyConstraint($deviceTypes, array('type_id'),array('id'));
		$devices->addUnnamedForeignKeyConstraint($deviceStates, array('state_id'),array('id'));
		$devices->addUnnamedForeignKeyConstraint($locations, array('location_id'),array('id'));
		$devicesTags->addUnnamedForeignKeyConstraint($devices, array('device_id'),array('id'));
		$devicesTags->addUnnamedForeignKeyConstraint($deviceTags, array('tag_id'),array('id'));

		$this->updateSchema($schema);

		$deviceTypeEntity=new \Entity\DeviceType(1);
		$deviceTypeEntity->setName('Refill');
		$this->persist($deviceTypeEntity);

		$deviceTypeEntity=new \Entity\DeviceType(2);
		$deviceTypeEntity->setName('Hardware');
		$this->persist($deviceTypeEntity);

		$deviceStateEntity=new \Entity\DeviceState(1);
		$deviceStateEntity->setName('Available');
		$this->persist($deviceStateEntity);

		$deviceStateEntity=new \Entity\DeviceState(2);
		$deviceStateEntity->setName('Busy');
		$this->persist($deviceStateEntity);

		$deviceStateEntity=new \Entity\DeviceState(3);
		$deviceStateEntity->setName('In service');
		$this->persist($deviceStateEntity);

		$orderStateEntity=new \Entity\OrderState(1);
		$orderStateEntity->setName('Open');
		$this->persist($orderStateEntity);

		$orderStateEntity=new \Entity\OrderState(2);
		$orderStateEntity->setName('In progress');
		$this->persist($orderStateEntity);

		$orderStateEntity=new \Entity\OrderState(3);
		$orderStateEntity->setName('Closed');
		$this->persist($orderStateEntity);

		$this->flush();

	}

	public function downgrade(Container $container){
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

	}

}