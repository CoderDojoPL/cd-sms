<?php 

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

use Arbor\Core\Container;

/**
 * Hellper for migrate file version
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
abstract class MigrateHelper{
	private $container;

	/**
	 * Method executed when update project
	 *
	 * @param Arbor\Core\Container $container
	 */
	public final function up(Container $container){
		$this->container=$container;
		$this->schema=$this->createSchema();
		$this->update($container);

	}

	/**
	 * Method executed when downgrade project
	 *
	 * @param Arbor\Core\Container $container
	 */
	public final function down(Container $container){
		$this->container=$container;
		$this->schema=$this->createSchema();
		$this->downgrade($container);

	}

	/**
	 * Create Doctrine schema with loaded current database stage 
	 *
	 * @return Doctrine\DBAL\Schema\Schema
	 */
	protected function createSchema(){
		$manager=$this->container->getDoctrine()->getEntityManager();

		$schemaManager=$manager->getConnection()->getSchemaManager();

		return $schemaManager->createSchema();

	}

	/**
	 * Check diffrent in schemas (current and modified by migrate script) and executed sql
	 *
	 * @return Doctrine\DBAL\Schema\Schema $schema
	 */
	protected function updateSchema($schema){
		$manager=$this->container->getDoctrine()->getEntityManager();
        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $fromSchema=$this->schema;
        $schemaDiff = $comparator->compare($fromSchema, $schema);
        $platform=$manager->getConnection()->getDatabasePlatform();
        $sqls=$schemaDiff->toSql($platform);
    	foreach($sqls as $sql){
			$manager->getConnection()->exec($sql);
    	}

	}

	/**
	 * Begin database transaction.
	 */
	protected function beginTransaction(){
		$manager=$this->container->getDoctrine()->getEntityManager();
        $conn=$manager->getConnection();
        $conn->beginTransaction();

	}

	/**
	 * Commit database transaction.
	 */
	protected function commitTransaction(){
		$manager=$this->container->getDoctrine()->getEntityManager();
        $conn=$manager->getConnection();
        $conn->commit();

	}

	/**
	 * Mark entity to observe
	 *
	 * @return object $entity
	 */
	protected function persist($entity){
		$manager=$this->container->getDoctrine()->getEntityManager();
		$manager->persist($entity);
	}

	/**
	 * Flush changes in observed entities. Executed sqls
	 */
	protected function flush(){
		$manager=$this->container->getDoctrine()->getEntityManager();
		$manager->flush();
	}



}