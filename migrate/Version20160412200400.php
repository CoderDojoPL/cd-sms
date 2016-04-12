<?php
namespace Migrate;

use Arbor\Core\Container;
use Common\MigrateHelper;

/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2016-04-12
 * Time: 20:04
 */

class Version20160412200400 extends MigrateHelper
{
	public function update(Container $container)
	{
		$this->beginTransaction();
		$schema = $this->createSchema();

		$queueMail= $schema->createTable('queue_emails');
		$queueMail->addColumn('id','integer',array('autoincrement'=>true));
		$queueMail->addColumn('email_to','string');
		$queueMail->addColumn('subject','string');
		$queueMail->addColumn('content','text');
		$queueMail->addColumn('sended_at','datetime',array('notnull'=>false));
		$queueMail->addColumn('created_at','datetime',array('notnull'=>false));
		$queueMail->setPrimaryKey(array('id'));
		$this->updateSchema($schema);
		$this->commitTransaction();
	}

	public function downgrade(Container $container)
	{
		$this->beginTransaction();
		$schema = $this->createSchema();
		$schema->dropTable('queue_emails');
		$this->updateSchema($schema);

		$this->commitTransaction();
	}
}