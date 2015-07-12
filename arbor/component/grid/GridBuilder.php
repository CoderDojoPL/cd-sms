<?php

namespace Arbor\Component\Grid;

use Arbor\Component\Grid\GridFormatter;
use Arbor\Component\Grid\GridDataManager;
use Arbor\Component\Grid\BasicColumnFormatter;

/**
 * @since 0.17.0
 */
class GridBuilder{
	private $formatter;
	private $dataManager;
	private $limit=10;
	private $page=1;
	private $columns=array();

	/**
	 * Set formatter with html rule pattern
	 * @param Arbor\Component\Grid\GridFormatter $formatter
	 * @since 0.17.0
	 */
	public function setFormatter(GridFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set formatter with html rule pattern
	 * @param Arbor\Component\Grid\GridDataManager $dataManager
	 * @since 0.17.0
	 */
	public function setDataManager(GridDataManager $dataManager){
		$this->dataManager=$dataManager;
	}

	/**
	 * get DataManager
	 * @return Arbor\Component\Grid\GridDataManager
	 * @since 0.18.0
	 */
	public function getDataManager(){
		return $this->dataManager;
	}

	/**
	 * get columns data
	 * @return array
	 * @since 0.18.0
	 */
	public function getColumns(){
		return $this->columns;
	}

	/**
	 * get records
	 * @return array
	 * @since 0.18.0
	 */
	public function getRecords(){
		return $this->dataManager->getRecords($this->limit,$this->page);
	}

	/**
	 * get total count records
	 * @return int
	 * @since 0.18.0
	 */
	public function getTotalCount(){
		return $this->dataManager->getTotalCount();
	}
	/**
	 * Set limit records on single page
	 * @param int $limit - items on page
	 * @since 0.17.0
	 */
	public function setLimit($limit){
		$this->limit=$limit;
	}

	/**
	 * Set current page
	 * @param int $page - current page
	 * @since 0.17.0
	 */
	public function setPage($page){
		$this->page=$page;
	}

	/**
	 * add column
	 * @param string $label - column name
	 * @param string $key - mapped key for record
	 * @since 0.17.0
	 */
	public function addColumn($label,$key,$formatter=null){
		if(!$formatter){			
			$formatter=new BasicColumnFormatter();
		}
		$this->columns[]=array('label'=>$label,'key'=>$key,'formatter'=>$formatter);
	}

	/**
	 * Generate html grid string
	 * @return string with html form
	 * @since 0.17.0
	 */
	public function render(){

		return $this->formatter->render($this->columns,$this->dataManager->getRecords($this->limit,$this->page)
			,$this->dataManager->getTotalCount(),$this->limit,$this->page);
	}


	public function __toString(){
		return $this->render();
	}
}