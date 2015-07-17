<?php 
namespace Common;

use Arbor\Core\Container;

abstract class MigrateHelper{
	private $container;

	public final function up(Container $container){
		$this->container=$container;
		$this->schema=$this->createSchema();
		$this->update($container);

	}

	public final function down(Container $container){
		$this->container=$container;
		$this->schema=$this->createSchema();
		$this->downgrade($container);

	}

	protected function createSchema(){
		$manager=$this->container->getDoctrine()->getEntityManager();

		$schemaManager=$manager->getConnection()->getSchemaManager();

		return $schemaManager->createSchema();

	}


	protected function updateSchema($schema){
		$manager=$this->container->getDoctrine()->getEntityManager();
        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $fromSchema=$this->schema;
        $schemaDiff = $comparator->compare($fromSchema, $schema);
        $platform=$manager->getConnection()->getDatabasePlatform();
        $sqls=$schemaDiff->toSql($platform);
        $conn=$manager->getConnection();
        $conn->beginTransaction();
        try{
        	foreach($sqls as $sql){
				$manager->getConnection()->exec($sql);
        	}
			$conn->commit();

        }
        catch(\Exception $e){
        	$conn->rollback();
        	throw $e;
        }
	}

	protected function persist($entity){
		$manager=$this->container->getDoctrine()->getEntityManager();
		$manager->persist($entity);
	}

	protected function flush(){
		$manager=$this->container->getDoctrine()->getEntityManager();
		$manager->flush();
	}



}