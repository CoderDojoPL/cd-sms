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
class Version20150722191210 extends MigrateHelper{
	
	/**
	 * {@inheritdoc}
	 */
	public function update(Container $container){

		$schema=$this->createSchema();
		$logActions=$schema->createTable('log_actions');
		$users=$schema->getTable('users');

		$logs=$schema->createTable('logs');

		$logs->addColumn('id','integer',array('autoincrement'=>true));
		$logs->addColumn('user_id','integer',array('notnull'=>false));
		$logs->addColumn('log_action_id','integer');
		$logs->addColumn('arguments','text',array('notnull'=>false));
		$logs->addColumn('result','text',array('notnull'=>false));
		$logs->addColumn('ip_address','text');
		$logs->addColumn('user_agent','text',array('notnull'=>false));
		$logs->addColumn('is_success','boolean');
		$logs->addColumn('count_modified_entities','integer');
		$logs->addColumn('fail_message','text',array('notnull'=>false));
		$logs->addColumn('created_at','datetime');
		$logs->setPrimaryKey(array('id'));


		$locationLogs=$schema->createTable('location_logs');
		$locationLogs->addColumn('id','integer',array('autoincrement'=>true));
		$locationLogs->addColumn('name','string');
		$locationLogs->addColumn('city','string');
		$locationLogs->addColumn('street','string');
		$locationLogs->addColumn('number','string');
		$locationLogs->addColumn('apartment','string',array('notnull'=>false));
		$locationLogs->addColumn('postal','string');
		$locationLogs->addColumn('phone','string');
		$locationLogs->addColumn('email','string');
		$locationLogs->addColumn('created_at','datetime');
		$locationLogs->addColumn('log_left_id','integer');
		$locationLogs->addColumn('log_right_id','integer');
		$locationLogs->addColumn('origin_id','integer');
		$locationLogs->addColumn('removed','boolean');
		$locationLogs->setPrimaryKey(array('id'));


		$logActions->addColumn('id','integer');
		$logActions->addColumn('name','string');
		$logActions->setPrimaryKey(array('id'));


		$logs->addNamedForeignKeyConstraint('FK_F08FC65CA76ED395',$users, array('user_id'),array('id'));
		$logs->addNamedForeignKeyConstraint('FK_F08FC65C8B306CBB',$logActions, array('log_action_id'),array('id'));


		$locationLogs->addNamedForeignKeyConstraint('FK_708B731CDAA1F695',$logs, array('log_left_id'),array('id'));
		$locationLogs->addNamedForeignKeyConstraint('FK_708B731C3AC4A3EA',$logs, array('log_right_id'),array('id'));

		$this->updateSchema($schema);

		$actionLogEntity=new \Entity\LogAction(1);
		$actionLogEntity->setName('Edit user.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(2);
		$actionLogEntity->setName('Add location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(3);
		$actionLogEntity->setName('Edit location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(4);
		$actionLogEntity->setName('Remove location.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(5);
		$actionLogEntity->setName('Sign in.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(6);
		$actionLogEntity->setName('Sign out.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(7);
		$actionLogEntity->setName('Add device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(8);
		$actionLogEntity->setName('Edit device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(9);
		$actionLogEntity->setName('Remove device.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(10);
		$actionLogEntity->setName('Add order.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(11);
		$actionLogEntity->setName('Featch order.');
		$this->persist($actionLogEntity);

		$actionLogEntity=new \Entity\LogAction(12);
		$actionLogEntity->setName('Close order.');
		$this->persist($actionLogEntity);

		$this->flush();
	}


	/**
	 * {@inheritdoc}
	 */
	public function downgrade(Container $container){
		$schema=$this->createSchema();

		$logs=$schema->getTable('logs');
		$locationLogs=$schema->getTable('location_logs');

		$logs->removeForeignKey('FK_F08FC65CA76ED395');
		$logs->removeForeignKey('FK_F08FC65C8B306CBB');


		$locationLogs->removeForeignKey('FK_708B731CDAA1F695');
		$locationLogs->removeForeignKey('FK_708B731C3AC4A3EA');

		$schema->dropTable('log_actions');
		$schema->dropTable('logs');
		$schema->dropTable('location_logs');

				
		$this->updateSchema($schema);

	}

}