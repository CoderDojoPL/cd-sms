<?php 
namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

class Version20150717214510 extends MigrateHelper{
	
	public function update(Container $container){
		$schema=$this->createSchema();
		$users=$schema->getTable('users');
		$devices=$schema->getTable('devices');
		$locations=$schema->getTable('locations');

		$orders=$schema->getTable('orders');
		$orders->removeForeignKey('FK_E52FFDEE7E3C61F9');
		$orders->removeForeignKey('FK_E52FFDEE94A4C7D4');
		$orders->removeForeignKey('FK_E52FFDEE6C6B33F3');

		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE7E3C61F9',$users, array('owner_id'),array('id'),array('onDelete'=>'CASCADE'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE94A4C7D4',$devices, array('device_id'),array('id'),array('onDelete'=>'CASCADE'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE6C6B33F3',$users, array('performer_id'),array('id'),array('onDelete'=>'CASCADE'));

		$users->removeForeignKey('FK_1483A5E964D218E');
		$users->addNamedForeignKeyConstraint('FK_1483A5E964D218E',$locations, array('location_id'),array('id'),array('onDelete'=>'SET NULL'));

		$devices->removeForeignKey('FK_11074E9A64D218E');
		
		$devices->addNamedForeignKeyConstraint('FK_11074E9A64D218E',$locations, array('location_id'),array('id'),array('onDelete'=>'CASCADE'));
				
		$this->updateSchema($schema);

	}


	public function downgrade(Container $container){
		$schema=$this->createSchema();

		$orders=$schema->getTable('orders');
		$users=$schema->getTable('users');
		$devices=$schema->getTable('devices');
		$locations=$schema->getTable('locations');

		$orders->removeForeignKey('FK_E52FFDEE7E3C61F9');
		$orders->removeForeignKey('FK_E52FFDEE94A4C7D4');
		$orders->removeForeignKey('FK_E52FFDEE6C6B33F3');

		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE7E3C61F9',$users, array('owner_id'),array('id'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE94A4C7D4',$devices, array('device_id'),array('id'));
		$orders->addNamedForeignKeyConstraint('FK_E52FFDEE6C6B33F3',$users, array('performer_id'),array('id'));

		$users->removeForeignKey('FK_1483A5E964D218E');
		$users->addNamedForeignKeyConstraint('FK_1483A5E964D218E',$locations, array('location_id'),array('id'));

		$devices->removeForeignKey('FK_11074E9A64D218E');
		
		$devices->addNamedForeignKeyConstraint('FK_11074E9A64D218E',$locations, array('location_id'),array('id'));
				
		$this->updateSchema($schema);

	}

}