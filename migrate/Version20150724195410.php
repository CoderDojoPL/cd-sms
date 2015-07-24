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
class Version20150724195410 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){

		$this->beginTransaction();
		$schema=$this->createSchema();
		$devicesTags=$schema->getTable('devices_tags');
		$devices=$schema->getTable('devices');
		$deviceTags=$schema->getTable('device_tags');

		$devicesTags->removeForeignKey('FK_8472C11794A4C7D4');
		$devicesTags->removeForeignKey('FK_8472C117BAD26311');


		$devicesTags->addNamedForeignKeyConstraint('FK_8472C11794A4C7D4',$devices, array('device_id'),array('id'),array('onDelete'=>'CASCADE'));
		$devicesTags->addNamedForeignKeyConstraint('FK_8472C117BAD26311',$deviceTags, array('tag_id'),array('id'),array('onDelete'=>'CASCADE'));


		$this->updateSchema($schema);

		$this->commitTransaction();

	}


	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){
		$this->beginTransaction();
		$schema=$this->createSchema();
		$devicesTags=$schema->getTable('devices_tags');
		$devices=$schema->getTable('devices');
		$deviceTags=$schema->getTable('device_tags');

		$devicesTags->removeForeignKey('FK_8472C11794A4C7D4');
		$devicesTags->removeForeignKey('FK_8472C117BAD26311');


		$devicesTags->addNamedForeignKeyConstraint('FK_8472C11794A4C7D4',$devices, array('device_id'),array('id'));
		$devicesTags->addNamedForeignKeyConstraint('FK_8472C117BAD26311',$deviceTags, array('tag_id'),array('id'));


		$this->updateSchema($schema);

		$this->commitTransaction();

	}

}