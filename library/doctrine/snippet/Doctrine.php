<?php

namespace Library\Doctrine\Snippet;
use Arbor\Core\Container;

class Doctrine {
	
	public function getDoctrine(Container $container){
		return $container->getService('doctrine');
	}

	public function getRepository(Container $container,$entity){
		return $container->getDoctrine()->getRepository('Entity\\'.$entity);
	}

	public function find(Container $container,$entity,$conditions=array(),$orders=array()){
		return $container->getRepository($entity)->findBy($conditions,$orders);
	}

	public function findOne(Container $container,$entity,$conditions=array()){
		return $container->getRepository($entity)->findOneBy($conditions);
	}

	public function flush(Container $container){
		return $container->getDoctrine()->getEntityManager()->flush();
	}

	public function remove(Container $container,$entity){
		return $container->getDoctrine()->getEntityManager()->remove($entity);
	}

	public function createQuery(Container $container,$dql){
		return $container->getDoctrine()->getEntityManager()->createQuery($dql);
	}

	public function persist(Container $container,$entity){
		return $container->getDoctrine()->getEntityManager()->persist($entity);
	}

	public function executeQuery(Container $container,$sql,$parameters=array()){
		$stmt=$container->getDoctrine()->getEntityManager()->getConnection()->prepare($sql);
		$stmt->execute($parameters);
    	return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function escape(Container $container,$value){
		return $container->getDoctrine()->getEntityManager()->getConnection()->quote($value);
	}	

}
