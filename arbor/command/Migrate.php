<?php

namespace Arbor\Command;

use Arbor\Core\Command;

/**
 * Migrate project from previous stage to next
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.18.0
 */
class Migrate extends Command{

	private $currentVersion=0;

	/**
	 * Update project
	 * @since 0.18.0
	 */	
	public function update(){

		if(file_exists($this->getFilePath()))
			$this->currentVersion=file_get_contents($this->getFilePath());

		$migrateFiles=array();
		$handleDir=opendir(__DIR__."/../../migrate");
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}
		$versionBefore=$this->currentVersion;
		sort($migrateFiles);
		foreach($migrateFiles as $migrateFile){
			try{
				if($migrateFile>$this->currentVersion){
					$versionClassName='Migrate\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->up($this);
					$this->currentVersion=$migrateFile;
					file_put_contents($this->getFilePath(), $this->currentVersion);

				}

			}
			catch(\Exception $e){
				$this->writeLn("Error in version ".$migrateFile.": ".$e->getMessage());
				return;
			}
		}

		$this->writeLn("Modified version ".$versionBefore." to ".$this->currentVersion);

	}

	/**
	 * Downgrade project
	 * @since 0.18.0
	 */	
	public function downgrade(){

		if(file_exists($this->getFilePath()))
			$this->currentVersion=file_get_contents($this->getFilePath());

		$migrateFiles=array();
		$handleDir=opendir(__DIR__."/../../migrate");
		while($file=readdir($handleDir)){
			if($file!="." && $file!=".." && preg_match('/^Version([0-9]+)\.php$/',$file,$match)){
				$migrateFiles[]=$match[1];
			}
		}
		$versionBefore=$this->currentVersion;
		rsort($migrateFiles);
		foreach($migrateFiles as $migrateFile){
			try{
				if($migrateFile<=$this->currentVersion){
					$versionClassName='Migrate\Version'.$migrateFile;
					$versionObject=new $versionClassName();

					$versionObject->down($this);
					$this->currentVersion=$migrateFile;
					file_put_contents($this->getFilePath(), $this->currentVersion);

				}

			}
			catch(\Exception $e){
				$this->writeLn("Error in version ".$migrateFile.": ".$e->getMessage());
				return;
			}
		}

		$this->currentVersion=0; //TODO przy pełnym downgrade spada do 0. W innych Sytuacjach zapisujemy o nr niżej niż wskazany
		file_put_contents($this->getFilePath(), $this->currentVersion);

		$this->writeLn("Modified version ".$versionBefore." to ".$this->currentVersion);

	}

	/**
	 * get url to file with saved migrate stage
	 * @since 0.18.0
	 */
	private function getFilePath(){
		return __DIR__."/../../config/migrate.".$this->getEnviorment()->getName().".txt";
	}
}

?>
