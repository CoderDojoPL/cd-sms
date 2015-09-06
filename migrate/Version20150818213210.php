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
class Version20150818213210 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){

		$this->beginTransaction();
		$schema=$this->createSchema();
		$deviceLogs=$schema->getTable('device_logs');
		$devices=$schema->getTable('devices');
		$users=$schema->getTable('users');

		$devices->addColumn('user_id','integer',array('notnull'=>false));

		$devices->addNamedForeignKeyConstraint('FK_11074E9AA76ED395',$users, array('user_id'),array('id'),array('onDelete'=>'CASCADE'));

		$deviceLogs->addColumn('user_id','integer',array('notnull'=>false));
		$this->updateSchema($schema);


		$this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)",array(
			'id'=>17
		,'name'=>'Set free state device.'
		));

		$this->commitTransaction();

	}


	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){
		$this->beginTransaction();
		$schema=$this->createSchema();
		$deviceLogs=$schema->getTable('device_logs');
		$devices=$schema->getTable('devices');

		// $devices->removeForeignKey('fk_11074e9aa76ed395');
		$devices->dropColumn('user_id');

		$deviceLogs->dropColumn('user_id');

		$this->updateSchema($schema);

		$this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
			'id'=>17
		));

		$this->commitTransaction();

	}
}