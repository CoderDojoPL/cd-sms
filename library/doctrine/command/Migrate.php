<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Library\Doctrine\Command;

use Arbor\Core\Command;

class Migrate extends Command{

	private $currentVersion=0;
	
	public function update(){

		if(file_exists($this->getFilePath()))
			$this->currentVersion=file_get_contents($this->getFilePath());

		$migrateFiles=array();
		$handleDir=opendir("../database");
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}
		$versionBefore=$this->currentVersion;
		sort($migrateFiles);
		$doctrine=$this->getService('doctrine');
		$conn=$doctrine->getEntityManager()->getConnection();
		$conn->beginTransaction();
		try{
			foreach($migrateFiles as $migrateFile){
				if($migrateFile>$this->currentVersion){
					$versionClassName='Database\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->up($conn);
					$this->currentVersion=$migrateFile;
				}
			}

			file_put_contents($this->getFilePath(), $this->currentVersion);

			$this->writeLn("Modified version ".$versionBefore." to ".$this->currentVersion);
			$conn->commit(); //FIXME nie działa poprawnie zakładanie transakcji. Baza nie jest na końcu odblokowywana!
		}
		catch(\Exception $e){
			$conn->rollback();
			throw $e;
		}

	}

	public function downgrade(){

		if(file_exists($this->getFilePath()))
			$this->currentVersion=file_get_contents($this->getFilePath());

		$migrateFiles=array();
		$handleDir=opendir("../database");
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}
		$versionBefore=$this->currentVersion;
		rsort($migrateFiles);
		$doctrine=$this->getService('doctrine');
		$conn=$doctrine->getEntityManager()->getConnection();
		$conn->beginTransaction();

		try{
			foreach($migrateFiles as $migrateFile){

				if($migrateFile<=$this->currentVersion){
					$versionClassName='Database\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->down($conn);
					$this->currentVersion=$migrateFile;
				}
			}			
			$this->currentVersion=0; //TODO przy pełnym downgrade spada do 0. W innych Sytuacjach zapisujemy o nr niżej niż wskazany
			$conn->commit();
			file_put_contents($this->getFilePath(), $this->currentVersion);

			$this->writeLn("Modified version ".$versionBefore." to ".$this->currentVersion);

		}
		catch(\Exception $e){
			$conn->rollback();
			throw $e;
		}


	}

	private function getFilePath(){
		return "../config/migrate.".$this->getEnviorment()->getName().".txt";
	}
}

?>
