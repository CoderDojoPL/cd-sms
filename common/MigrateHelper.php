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
	protected $container;

	/**
	 * Implement migrate rule.
	 *
	 * @param Container $container
	 */
	abstract public function update(Container $container);

	/**
	 * Implement migrate rule.
	 *
	 * @param Container $container
	 */
	abstract public function downgrade(Container $container);

	/**
	 * Method executed when update project
	 *
	 * @param \Arbor\Core\Container $container
	 */
	public final function up(Container $container){
		$this->container=$container;
		$this->update($container);

	}

	/**
	 * Method executed when downgrade project
	 *
	 * @param \Arbor\Core\Container $container
	 */
	public final function down(Container $container){
		$this->container=$container;
		$this->downgrade($container);

	}

	/**
	 * Create Doctrine schema with loaded current database stage 
	 *
	 * @return \Doctrine\DBAL\Schema\Schema
	 */
	protected function createSchema(){
		$manager=$this->container->getDoctrine()->getEntityManager();
		$schemaManager=$manager->getConnection()->getSchemaManager();

		return $schemaManager->createSchema();

	}

	/**
	 * Check different in schemas (current and modified by migrate script) and executed sql
	 *
	 * @return \Doctrine\DBAL\Schema\Schema $schema
	 */
	protected function updateSchema($schema){
		$manager=$this->container->getDoctrine()->getEntityManager();
        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $fromSchema=$this->createSchema();
        $schemaDiff = $comparator->compare($fromSchema, $schema);
        $platform=$manager->getConnection()->getDatabasePlatform();
        $sqls=$schemaDiff->toSql($platform);

    	foreach($sqls as $sql){
    		echo $sql.";\n";
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
	 * Execute raw sql.
	 *
	 * @param string $sql raw sql query
	 * @param array $parameters parameters inside sql query
	 * @param boolean $result only for query who return data
	 * @return array|null
	 */
	protected function executeQuery($sql,$parameters=array(),$result=false){
		$stmt=$this->container->getDoctrine()->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute($parameters);
		if($result){
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	/**
	 * Get driver name for current db connection.
	 *
	 * @return string
	 */
	protected function getDriver(){
		return $this->container->getDoctrine()->getEntityManager()->getConnection()->getDriver()->getName();
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